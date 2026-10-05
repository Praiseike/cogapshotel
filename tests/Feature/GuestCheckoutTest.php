<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Tests\TestCase as BaseTestCase;

/**
 * Guest checkout: book + pay without an account, then keep history
 * by matching email when the account is created later.
 */
final class GuestCheckoutTest extends BaseTestCase
{
    use DatabaseTransactions;

    protected function room(): Room
    {
        return Room::where('is_available', true)->firstOrFail();
    }

    protected function dates(): array
    {
        return [
            'check_in' => now()->addDays(2)->toDateString(),
            'check_out' => now()->addDays(5)->toDateString(),
        ];
    }

    protected function fakePaystackInit(): void
    {
        Http::fake([
            'api.paystack.co/transaction/initialize' => Http::response([
                'status' => true,
                'message' => 'Authorization URL created',
                'data' => [
                    'authorization_url' => 'https://checkout.paystack.com/guest123',
                    'access_code' => 'guest123',
                    'reference' => 'BOOK-GUEST123',
                ],
            ], 200),
        ]);
    }

    public function test_guest_can_create_booking_without_login(): void
    {
        $this->fakePaystackInit();
        $room = $this->room();

        $response = $this
            ->withSession(['_token' => 'test-csrf-token'])
            ->post(route('booking.store'), array_merge([
                '_token' => 'test-csrf-token',
                'room_id' => $room->id,
                'guests_count' => 2,
                'guest_name' => 'Ada Guest',
                'guest_email' => 'ada@example.com',
                'guest_phone' => '+2348000000001',
            ], $this->dates()));

        $response->assertRedirect('https://checkout.paystack.com/guest123');

        $this->assertDatabaseHas('bookings', [
            'room_id' => $room->id,
            'guest_email' => 'ada@example.com',
            'status' => 'pending_payment',
        ]);

        $booking = Booking::where('guest_email', 'ada@example.com')->latest()->first();
        $this->assertNull($booking->user_id);
        $this->assertContains($booking->id, session('guest_bookings', []));
    }

    public function test_guest_booking_requires_email_when_logged_out(): void
    {
        $room = $this->room();

        $this
            ->withSession(['_token' => 'test-csrf-token'])
            ->post(route('booking.store'), array_merge([
                '_token' => 'test-csrf-token',
                'room_id' => $room->id,
                'guests_count' => 2,
                'guest_name' => 'No Email',
            ], $this->dates()))
            ->assertSessionHasErrors('guest_email');
    }

    public function test_guest_can_view_success_page_via_session(): void
    {
        $booking = Booking::create([
            'user_id' => null,
            'guest_name' => 'Ada Guest',
            'guest_email' => 'ada@example.com',
            'room_id' => $this->room()->id,
            'check_in' => now()->addDays(2)->toDateString(),
            'check_out' => now()->addDays(5)->toDateString(),
            'guests_count' => 1,
            'total_amount' => 50000,
            'status' => 'confirmed',
            'payment_reference' => 'BOOK-'.Str::random(12),
        ]);

        $this
            ->withSession(['guest_bookings' => [$booking->id]])
            ->get(route('booking.success', $booking))
            ->assertOk();
    }

    public function test_stranger_cannot_view_someone_elses_success_page(): void
    {
        $booking = Booking::create([
            'user_id' => null,
            'guest_name' => 'Ada Guest',
            'guest_email' => 'ada@example.com',
            'room_id' => $this->room()->id,
            'check_in' => now()->addDays(2)->toDateString(),
            'check_out' => now()->addDays(5)->toDateString(),
            'guests_count' => 1,
            'total_amount' => 50000,
            'status' => 'confirmed',
            'payment_reference' => 'BOOK-'.Str::random(12),
        ]);

        // Different session, no login → 403.
        $this->get(route('booking.success', $booking))->assertForbidden();
    }

    public function test_registering_with_same_email_links_previous_guest_bookings(): void
    {
        $email = 'newguest'.Str::random(6).'@example.com';

        $booking = Booking::create([
            'user_id' => null,
            'guest_name' => 'New Guest',
            'guest_email' => $email,
            'room_id' => $this->room()->id,
            'check_in' => now()->addDays(2)->toDateString(),
            'check_out' => now()->addDays(5)->toDateString(),
            'guests_count' => 1,
            'total_amount' => 50000,
            'status' => 'confirmed',
            'payment_reference' => 'BOOK-'.Str::random(12),
        ]);

        $this
            ->withSession(['_token' => 'test-csrf-token'])
            ->post('/register', [
                '_token' => 'test-csrf-token',
                'name' => 'New Guest',
                'email' => $email,
                'password' => 'password123',
                'password_confirmation' => 'password123',
            ])
            ->assertRedirect(route('dashboard.index'));

        $user = User::where('email', $email)->firstOrFail();
        $this->assertSame($user->id, $booking->fresh()->user_id);
    }

    public function test_dashboard_shows_bookings_made_with_account_email(): void
    {
        $user = User::where('role', 'guest')->firstOrFail();

        $booking = Booking::create([
            'user_id' => null, // booked before the account existed
            'guest_name' => $user->name,
            'guest_email' => $user->email,
            'room_id' => $this->room()->id,
            'check_in' => now()->addDays(2)->toDateString(),
            'check_out' => now()->addDays(5)->toDateString(),
            'guests_count' => 1,
            'total_amount' => 50000,
            'status' => 'confirmed',
            'payment_reference' => 'BOOK-'.Str::random(12),
        ]);

        $this->actingAs($user)
            ->get(route('dashboard.bookings.show', $booking))
            ->assertOk();
    }

    public function test_expiry_releases_only_stale_pending_bookings(): void
    {
        $stale = Booking::create([
            'user_id' => null,
            'guest_name' => 'Stale Guest',
            'guest_email' => 'stale'.Str::random(6).'@example.com',
            'room_id' => $this->room()->id,
            'check_in' => now()->addDays(2)->toDateString(),
            'check_out' => now()->addDays(5)->toDateString(),
            'guests_count' => 1,
            'total_amount' => 50000,
            'status' => 'pending_payment',
            'payment_reference' => 'BOOK-'.Str::random(12),
        ]);
        // created_at is not mass-assignable — backdate via save().
        $stale->created_at = now()->subHour();
        $stale->save();

        // Paid 40 minutes ago: must survive the expiry job.
        $paid = Booking::create([
            'user_id' => null,
            'guest_name' => 'Paid Guest',
            'guest_email' => 'paid'.Str::random(6).'@example.com',
            'room_id' => $this->room()->id,
            'check_in' => now()->addDays(20)->toDateString(),
            'check_out' => now()->addDays(22)->toDateString(),
            'guests_count' => 1,
            'total_amount' => 50000,
            'status' => 'confirmed',
            'payment_reference' => 'BOOK-'.Str::random(12),
        ]);
        $paid->created_at = now()->subMinutes(40);
        $paid->save();

        $released = app(\App\Services\BookingService::class)->releaseExpiredBookings();

        $this->assertGreaterThanOrEqual(1, $released);
        $this->assertSame('cancelled', $stale->fresh()->status);
        $this->assertSame('confirmed', $paid->fresh()->status);
    }

    public function test_dashboard_hides_bookings_with_different_email(): void
    {
        $user = User::where('role', 'guest')->firstOrFail();

        $booking = Booking::create([
            'user_id' => null,
            'guest_name' => 'Someone Else',
            'guest_email' => 'stranger'.Str::random(6).'@example.com',
            'room_id' => $this->room()->id,
            'check_in' => now()->addDays(2)->toDateString(),
            'check_out' => now()->addDays(5)->toDateString(),
            'guests_count' => 1,
            'total_amount' => 50000,
            'status' => 'confirmed',
            'payment_reference' => 'BOOK-'.Str::random(12),
        ]);

        $this->actingAs($user)
            ->get(route('dashboard.bookings.show', $booking))
            ->assertForbidden();
    }
}
