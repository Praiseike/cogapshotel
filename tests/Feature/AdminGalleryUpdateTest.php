<?php

namespace Tests\Feature;

use App\Models\Gallery;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase as BaseTestCase;

final class AdminGalleryUpdateTest extends BaseTestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
    }

    protected function admin(): User
    {
        return User::where('role', 'admin')->firstOrFail();
    }

    protected function makeGallery(): Gallery
    {
        return Gallery::create([
            'title' => 'Old Title',
            'image_path' => 'gallery/old.jpg',
            'sort_order' => 1,
            'is_active' => true,
        ]);
    }

    protected function update(Gallery $gallery, array $overrides = [])
    {
        return $this->actingAs($this->admin())
            ->withSession(['_token' => 't'])
            ->put(route('admin.gallery.update', $gallery), array_merge([
                '_token' => 't',
                'title' => 'New Title',
                'image' => UploadedFile::fake()->image('new.jpg')->size(300),
                'caption' => 'cap',
                'sort_order' => 3,
                'is_active' => '1',
            ], $overrides));
    }

    public function test_replacing_image_updates_image_path_and_persists_file(): void
    {
        $gallery = $this->makeGallery();

        $this->update($gallery)->assertRedirect(route('admin.gallery.index'));

        $fresh = $gallery->fresh();

        $this->assertNotEquals('gallery/old.jpg', $fresh->image_path);
        $this->assertTrue(Storage::disk('public')->exists($fresh->image_path));
        $this->assertSame('New Title', $fresh->title);
        $this->assertSame(3, $fresh->sort_order);
    }

    public function test_updating_without_new_image_keeps_existing_image_path(): void
    {
        $gallery = $this->makeGallery();

        $this->update($gallery, ['image' => null])->assertRedirect(route('admin.gallery.index'));

        $this->assertSame('gallery/old.jpg', $gallery->fresh()->image_path);
    }

    public function test_oversized_image_is_rejected_with_validation_error(): void
    {
        $gallery = $this->makeGallery();

        $big = UploadedFile::fake()->image('big.jpg')->size(1024 * 11);

        $this->update($gallery, ['image' => $big])
            ->assertSessionHasErrors('image');

        $this->assertSame('gallery/old.jpg', $gallery->fresh()->image_path);
    }
}