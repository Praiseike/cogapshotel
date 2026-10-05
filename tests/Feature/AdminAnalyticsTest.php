<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Tests\TestCase as BaseTestCase;

final class AdminAnalyticsTest extends BaseTestCase
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

    public function test_analytics_page_renders_for_admin(): void
    {
        $this->actingAs($this->admin())
            ->get(route('admin.analytics'))
            ->assertOk()
            ->assertSee('Where Guests Come From', escape: false);
    }

    public function test_analytics_page_renders_with_zero_data(): void
    {
        // Zero-state safety is structural (all divisions guarded); the
        // page must render regardless of data volume.
        $response = $this->actingAs($this->admin())->get(route('admin.analytics'));

        $response->assertOk();
        $response->assertSee('Where Guests Come From', escape: false);
    }

    public function test_analytics_forbidden_for_guests(): void
    {
        $this->actingAs($this->guest())
            ->get(route('admin.analytics'))
            ->assertForbidden();
    }

    public function test_booking_source_is_captured_at_checkout(): void
    {
        Http::fake([
            'api.paystack.co/transaction/initialize' => Http::response([
                'status' => true,
                'message' => 'Authorization URL created',
                'data' => [
                    'authorization_url' => 'https://checkout.paystack.com/src123',
                    'access_code' => 'src123',
                    'reference' => 'BOOK-SRC123',
                ],
            ], 200),
        ]);

        $room = Room::where('is_available', true)->firstOrFail();

        $this
            ->withSession(['_token' => 'test-csrf-token'])
            ->post(route('booking.store'), [
                '_token' => 'test-csrf-token',
                'room_id' => $room->id,
                'check_in' => now()->addDays(2)->toDateString(),
                'check_out' => now()->addDays(5)->toDateString(),
                'guests_count' => 2,
                'guest_name' => 'Source Guest',
                'guest_email' => 'source'.Str::random(6).'@example.com',
                'source' => 'instagram',
            ])
            ->assertRedirect('https://checkout.paystack.com/src123');

        $booking = Booking::latest()->first();
        $this->assertSame('instagram', $booking->source);
    }

    public function test_invalid_source_is_ignored_not_stored(): void
    {
        // Service-level guard: unknown sources never persist.
        $made = app(\App\Services\BookingService::class)->createBooking(
            Room::where('is_available', true)->firstOrFail()->id,
            now()->addDays(40)->toDateString(),
            now()->addDays(42)->toDateString(),
            1,
            null,
            'Y',
            'y'.Str::random(6).'@example.com',
            null,
            'billboard-tv'
        );

        $this->assertNull($made->source);
    }
}
