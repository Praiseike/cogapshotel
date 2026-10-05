<?php

namespace Tests\Unit;

use App\Services\CloudinaryService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase as BaseTestCase;

final class CloudinaryServiceTest extends BaseTestCase
{
    protected function service(): CloudinaryService
    {
        return app(CloudinaryService::class);
    }

    public function test_disabled_without_credentials(): void
    {
        config()->set('cloudinary.cloud_name', null);
        config()->set('cloudinary.api_key', null);
        config()->set('cloudinary.api_secret', null);

        $this->assertFalse($this->service()->enabled());
    }

    public function test_upload_falls_back_to_local_disk_when_unconfigured(): void
    {
        config()->set('cloudinary.cloud_name', null);
        Storage::fake('public');

        $path = $this->service()->upload(UploadedFile::fake()->image('room.jpg')->size(200), 'rooms');

        $this->assertStringStartsWith('rooms/', $path);
        $this->assertTrue(Storage::disk('public')->exists($path));
    }

    public function test_delete_ignores_blank_and_external_hotlinks(): void
    {
        Storage::fake('public');

        // Must never throw, never touch the network.
        $this->service()->delete(null);
        $this->service()->delete('');
        $this->service()->delete('https://images.unsplash.com/photo-123?w=800');
        $this->service()->delete('https://res.cloudinary.com/someone-else/image/upload/v1/other.jpg');

        $this->assertTrue(true);
    }

    public function test_delete_removes_local_file(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('gallery/old.jpg', 'contents');

        $this->service()->delete('gallery/old.jpg');

        $this->assertFalse(Storage::disk('public')->exists('gallery/old.jpg'));
    }

    public function test_public_id_parsing(): void
    {
        config()->set('cloudinary.cloud_name', 'demo');

        $this->assertSame(
            'hotel-app/gallery/abc123',
            $this->service()->publicIdFromUrl('https://res.cloudinary.com/demo/image/upload/v1700000000/hotel-app/gallery/abc123.jpg')
        );
        $this->assertNull($this->service()->publicIdFromUrl('https://images.unsplash.com/photo-123'));
        $this->assertNull($this->service()->publicIdFromUrl('https://res.cloudinary.com/other/image/upload/v1/x.jpg'));
    }
}
