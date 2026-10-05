<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Tests\TestCase as BaseTestCase;

final class AdminBulkActionsTest extends BaseTestCase
{
    use DatabaseTransactions;

    protected function admin(): User
    {
        return User::where('role', 'admin')->firstOrFail();
    }

    protected function guest(): User
    {
        return User::where('role', 'guest')->firstOrFail();
    }

    protected function room(): Room
    {
        return Room::where('is_available', true)->firstOrFail();
    }

    protected function pendingBooking(): Booking
    {
        return Booking::create([
            'user_id' => $this->guest()->id,
            'guest_email' => $this->guest()->email,
            'room_id' => $this->room()->id,
            'check_in' => now()->addDays(30)->toDateString(),
            'check_out' => now()->addDays(32)->toDateString(),
            'guests_count' => 1,
            'total_amount' => 50000,
            'status' => 'pending_payment',
            'payment_reference' => 'BOOK-'.Str::random(12),
        ]);
    }

    public function test_admin_can_bulk_confirm_bookings(): void
    {
        $a = $this->pendingBooking();
        $b = $this->pendingBooking();

        $this->actingAs($this->admin())
            ->post(route('admin.bookings.bulk'), ['ids' => [$a->id, $b->id], 'action' => 'confirmed'])
            ->assertSessionHas('success');

        $this->assertSame('confirmed', $a->fresh()->status);
        $this->assertSame('confirmed', $b->fresh()->status);
    }

    public function test_admin_bulk_booking_requires_ids_and_valid_action(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.bookings.bulk'), ['ids' => [], 'action' => 'confirmed'])
            ->assertSessionHasErrors('ids');

        $this->actingAs($this->admin())
            ->post(route('admin.bookings.bulk'), ['ids' => [$this->pendingBooking()->id], 'action' => 'refunded'])
            ->assertSessionHasErrors('action');
    }

    public function test_guest_cannot_bulk_update_bookings(): void
    {
        $this->actingAs($this->guest())
            ->post(route('admin.bookings.bulk'), ['ids' => [$this->pendingBooking()->id], 'action' => 'confirmed'])
            ->assertForbidden();
    }

    public function test_admin_can_bulk_mark_rooms_maintenance(): void
    {
        $rooms = Room::where('is_available', true)->take(2)->get();
        $this->assertGreaterThanOrEqual(2, $rooms->count());

        $this->actingAs($this->admin())
            ->post(route('admin.rooms.bulk'), ['ids' => $rooms->pluck('id')->all(), 'action' => 'maintenance'])
            ->assertSessionHas('success');

        foreach ($rooms->fresh() as $room) {
            $this->assertSame('maintenance', $room->status);
            $this->assertFalse((bool) $room->is_available);
        }
    }

    public function test_admin_bulk_rooms_available_restores_bookability(): void
    {
        $room = $this->room();

        $this->actingAs($this->admin())
            ->post(route('admin.rooms.bulk'), ['ids' => [$room->id], 'action' => 'available'])
            ->assertSessionHas('success');

        $room = $room->fresh();
        $this->assertSame('available', $room->status);
        $this->assertTrue((bool) $room->is_available);
        $this->assertTrue($room->isAvailableForDates(
            now()->addDays(60)->toDateString(),
            now()->addDays(62)->toDateString()
        ));
    }
}
