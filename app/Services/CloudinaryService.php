<?php

namespace App\Services;

use Cloudinary\Cloudinary;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * Single doorway for all image uploads.
 *
 * - Cloudinary configured  → upload there, store the secure URL.
 * - No credentials (local dev without keys, tests) → local `public` disk,
 *   exactly like before. image_url() already handles both shapes.
 *
 * Deletion is backend-aware too: Cloudinary URLs are destroyed via the
 * API, local paths are removed from disk, external hotlinks (seed data)
 * are left alone. Never throws — a failed cleanup must not break the
 * admin flow; it only logs.
 */
class CloudinaryService
{
    public function enabled(): bool
    {
        return filled(config('cloudinary.cloud_name'))
            && filled(config('cloudinary.api_key'))
            && filled(config('cloudinary.api_secret'));
    }

    protected function cloud(): Cloudinary
    {
        return new Cloudinary([
            'cloud' => [
                'cloud_name' => config('cloudinary.cloud_name'),
                'api_key' => config('cloudinary.api_key'),
                'api_secret' => config('cloudinary.api_secret'),
            ],
            'url' => ['secure' => true],
        ]);
    }

    /**
     * Store an uploaded image. Returns the value to persist in the DB:
     * a Cloudinary secure URL, or a local relative path as fallback.
     */
    public function upload(UploadedFile $file, string $folder): string
    {
        if (! $this->enabled()) {
            return $file->store($folder, 'public');
        }

        $prefix = trim((string) config('cloudinary.folder_prefix', 'hotel-app'), '/');
        $folder = $prefix === '' ? $folder : $prefix.'/'.$folder;

        try {
            $result = $this->cloud()->uploadApi()->upload($file->getRealPath(), [
                'folder' => $folder,
                'resource_type' => 'image',
            ]);

            return $result['secure_url'];
        } catch (\Throwable $e) {
            Log::warning('Cloudinary upload failed, falling back to local disk: '.$e->getMessage());

            return $file->store($folder, 'public');
        }
    }

    /**
     * Remove a previously stored image (DB value). Safe to call with
     * blank values, local paths, Cloudinary URLs, or external hotlinks.
     */
    public function delete(?string $path): void
    {
        if (blank($path)) {
            return;
        }

        try {
            if (str_starts_with($path, 'http')) {
                $publicId = $this->publicIdFromUrl($path);

                // Not one of ours (e.g. Unsplash seed data) — leave it.
                if ($publicId === null) {
                    return;
                }

                if ($this->enabled()) {
                    $this->cloud()->uploadApi()->destroy($publicId, ['resource_type' => 'image']);
                }

                return;
            }

            Storage::disk('public')->delete($path);
        } catch (\Throwable $e) {
            Log::warning('Image cleanup failed for '.$path.': '.$e->getMessage());
        }
    }

    /**
     * Extract the public ID from a Cloudinary delivery URL, or null when
     * the URL is not a Cloudinary asset.
     */
    public function publicIdFromUrl(string $url): ?string
    {
        $cloudName = (string) config('cloudinary.cloud_name');

        if (! str_contains($url, 'res.cloudinary.com')) {
            return null;
        }

        // Our stored URLs are plain uploads: .../image/upload/[v123/]folder/file.jpg
        // (no transformations embedded), so everything after an optional
        // version segment is the public ID.
        if (! preg_match('#/image/upload/(?:v\d+/)?(.+)$#', $url, $m)) {
            return null;
        }

        $publicId = preg_replace('/\?.*$/', '', $m[1]);
        $publicId = preg_replace('/\.[a-zA-Z0-9]+$/', '', $publicId);

        // Only touch assets under our own cloud (paranoia against
        // destroying someone else's asset via a crafted URL).
        if ($cloudName !== '' && ! str_contains($url, "res.cloudinary.com/{$cloudName}/")) {
            return null;
        }

        return $publicId === '' ? null : $publicId;
    }
}
