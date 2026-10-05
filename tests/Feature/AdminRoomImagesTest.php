<?php

namespace Tests\Feature;

use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase as BaseTestCase;

final class AdminRoomImagesTest extends BaseTestCase
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

    protected function room(): Room
    {
        $room = Room::firstOrFail();
        Storage::disk('public')->put('rooms/keep.jpg', 'keep');
        Storage::disk('public')->put('rooms/drop.jpg', 'drop');
        $room->images = ['rooms/keep.jpg', 'rooms/drop.jpg'];
        $room->save();

        return $room->fresh();
    }

    protected function updatePayload(Room $room, array $overrides = []): array
    {
        return array_merge([
            '_token' => 't',
            '_method' => 'PUT',
            'category_id' => $room->category_id,
            'name' => $room->name,
            'price_per_night' => $room->price_per_night,
            'capacity' => $room->capacity,
        ], $overrides);
    }

    public function test_ticked_images_are_removed_from_db_and_disk(): void
    {
        $room = $this->room();

        $this->actingAs($this->admin())
            ->withSession(['_token' => 't'])
            ->put(
                route('admin.rooms.update', $room),
                $this->updatePayload($room, ['remove_images' => ['rooms/drop.jpg']])
            )
            ->assertRedirect(route('admin.rooms.index'));

        $this->assertSame(['rooms/keep.jpg'], $room->fresh()->images);
        $this->assertTrue(Storage::disk('public')->exists('rooms/keep.jpg'));
        $this->assertFalse(Storage::disk('public')->exists('rooms/drop.jpg'));
    }

    public function test_unknown_remove_paths_are_ignored(): void
    {
        $room = $this->room();

        $this->actingAs($this->admin())
            ->withSession(['_token' => 't'])
            ->put(
                route('admin.rooms.update', $room),
                $this->updatePayload($room, ['remove_images' => ['rooms/elsewhere.jpg', 'https://evil.test/x.jpg']])
            )
            ->assertRedirect(route('admin.rooms.index'));

        $this->assertSame(['rooms/keep.jpg', 'rooms/drop.jpg'], $room->fresh()->images);
        $this->assertTrue(Storage::disk('public')->exists('rooms/drop.jpg'));
    }
}
