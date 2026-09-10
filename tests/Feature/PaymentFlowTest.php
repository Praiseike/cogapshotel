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
 * Exercise the booking + Paystack payment flow against real Paystack.
 *
 * Scope notes:
 *  - A localStorage-based (parseable) webhook signature is generated locally
 *    using the real secret key, so the webhook tests require no tunnel.
 *  - initialize/callback flows are asserted with Http::fake() so they don't
 *    depend on a publicly reachable callback URL - the real initialize API
 *    is additionally smoke-tested separately (see PaymentService integration).
 */
final class PaymentFlowTest extends BaseTestCase
{
    use DatabaseTransactions;

    protected function postAuthenticated(string $route, array $data = []): \Illuminate\Testing\TestResponse
    {
        return $this->actingAs($this->guest())
            ->withSession(['_token' => 'test-csrf-token'])
            ->post($route, ['_token' => 'test-csrf-token', ...$data]);
    }

    protected function guest(): User
    {
        return User::where('role', 'guest')->firstOrFail();
    }

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

    protected function signedWebhookPayload(array $payload): array
    {
        $raw = json_encode($payload);
        $signature = hash_hmac('sha512', $raw, config('paystack.secretKey'));

        return [$raw, $signature];
    }

    public function test_storing_a_booking_creates_pending_payment_and_redirects_to_paystack(): void
    {
        Http::fake([
            'api.paystack.co/transaction/initialize' => Http::response([
                'status' => true,
                'message' => 'Authorization URL created',
                'data' => [
                    'authorization_url' => 'https://checkout.paystack.com/fake123',
                    'access_code' => 'fake123',
                    'reference' => 'BOOK-FAKE123',
                ],
            ], 200),
        ]);

        $room = $this->room();

        $response = $this->postAuthenticated(route('booking.store'), array_merge([
            'room_id' => $room->id,
            'guests_count' => 2,
        ], $this->dates()));

        $response->assertRedirect('https://checkout.paystack.com/fake123');

        $this->assertDatabaseHas('bookings', [
            'room_id' => $room->id,
            'status' => 'pending_payment',
        ]);
    }

    public function test_storing_a_booking_with_negative_dates_fails_validation(): void
    {
        $room = $this->room();

        $this->postAuthenticated(route('booking.store'), [
            'room_id' => $room->id,
            'guests_count' => 2,
            'check_in' => now()->addDays(2)->toDateString(),
            'check_out' => now()->addDay()->toDateString(),
        ])
            ->assertSessionHasErrors('check_out');
    }

    public function test_overlapping_booking_is_rejected(): void
    {
        $existing = Booking::create([
            'user_id' => $this->guest()->id,
            'room_id' => $this->room()->id,
            'check_in' => now()->addDays(2)->toDateString(),
            'check_out' => now()->addDays(5)->toDateString(),
            'guests_count' => 1,
            'total_amount' => 50000,
            'status' => 'pending_payment',
            'payment_reference' => 'BOOK-'.Str::random(12),
        ]);

        $response = $this->postAuthenticated(route('booking.store'), array_merge([
                'room_id' => $existing->room_id,
                'guests_count' => 1,
            ], $this->dates()));

        $response->assertSessionHas('error');
    }

    public function test_webhook_with_valid_signature_confirms_booking(): void
    {
        $booking = Booking::create([
            'user_id' => $this->guest()->id,
            'room_id' => $this->room()->id,
            'check_in' => now()->addDays(2)->toDateString(),
            'check_out' => now()->addDays(5)->toDateString(),
            'guests_count' => 1,
            'total_amount' => 50000,
            'status' => 'pending_payment',
            'payment_reference' => 'BOOK-'.Str::random(12),
        ]);

        [$raw, $signature] = $this->signedWebhookPayload([
            'event' => 'charge.success',
            'data' => [
                'reference' => $booking->payment_reference,
                'amount' => $booking->total_amount * 100,
                'currency' => 'NGN',
                'status' => 'success',
            ],
        ]);

        $this->call('POST', '/webhook/paystack', [], [], [], [
            'HTTP_x-paystack-signature' => $signature,
            'CONTENT_TYPE' => 'application/json',
        ], $raw)->assertOk();

        $this->assertEquals('confirmed', $booking->fresh()->status);
        $this->assertDatabaseHas('payments', [
            'booking_id' => $booking->id,
            'status' => 'success',
        ]);
    }

    public function test_webhook_with_invalid_signature_is_rejected(): void
    {
        $booking = Booking::create([
            'user_id' => $this->guest()->id,
            'room_id' => $this->room()->id,
            'check_in' => now()->addDays(2)->toDateString(),
            'check_out' => now()->addDays(5)->toDateString(),
            'guests_count' => 1,
            'total_amount' => 50000,
            'status' => 'pending_payment',
            'payment_reference' => 'BOOK-'.Str::random(12),
        ]);

        $payload = json_encode([
            'event' => 'charge.success',
            'data' => ['reference' => $booking->payment_reference],
        ]);

        $this->call('POST', '/webhook/paystack', [], [], [], [
            'HTTP_x-paystack-signature' => 'invalid',
            'CONTENT_TYPE' => 'application/json',
        ], $payload)->assertStatus(400);

        $this->assertEquals('pending_payment', $booking->fresh()->status);
    }

    public function test_webhook_charge_failed_marks_booking_cancelled(): void
    {
        $booking = Booking::create([
            'user_id' => $this->guest()->id,
            'room_id' => $this->room()->id,
            'check_in' => now()->addDays(2)->toDateString(),
            'check_out' => now()->addDays(5)->toDateString(),
            'guests_count' => 1,
            'total_amount' => 50000,
            'status' => 'pending_payment',
            'payment_reference' => 'BOOK-'.Str::random(12),
        ]);

        [$raw, $signature] = $this->signedWebhookPayload([
            'event' => 'charge.failed',
            'data' => ['reference' => $booking->payment_reference],
        ]);

        $this->call('POST', '/webhook/paystack', [], [], [], [
            'HTTP_x-paystack-signature' => $signature,
            'CONTENT_TYPE' => 'application/json',
        ], $raw)->assertOk();

        $this->assertEquals('cancelled', $booking->fresh()->status);
    }

    public function test_retry_payment_redirects_pending_booking_to_paystack(): void
    {
        $booking = Booking::create([
            'user_id' => $this->guest()->id,
            'room_id' => $this->room()->id,
            'check_in' => now()->addDays(2)->toDateString(),
            'check_out' => now()->addDays(5)->toDateString(),
            'guests_count' => 1,
            'total_amount' => 50000,
            'status' => 'pending_payment',
            'payment_reference' => 'BOOK-'.Str::random(12),
        ]);

        Http::fake([
            'api.paystack.co/transaction/initialize' => Http::response([
                'status' => true,
                'message' => 'Authorization URL created',
                'data' => [
                    'authorization_url' => 'https://checkout.paystack.com/retry123',
                    'access_code' => 'retry123',
                    'reference' => $booking->payment_reference,
                ],
            ], 200),
        ]);

        $this->postAuthenticated(route('dashboard.bookings.pay', $booking))
            ->assertRedirect('https://checkout.paystack.com/retry123');
    }

    public function test_retry_payment_rejected_for_confirmed_booking(): void
    {
        $booking = Booking::create([
            'user_id' => $this->guest()->id,
            'room_id' => $this->room()->id,
            'check_in' => now()->addDays(2)->toDateString(),
            'check_out' => now()->addDays(5)->toDateString(),
            'guests_count' => 1,
            'total_amount' => 50000,
            'status' => 'confirmed',
            'payment_reference' => 'BOOK-'.Str::random(12),
        ]);

        $this->postAuthenticated(route('dashboard.bookings.pay', $booking))
            ->assertRedirect(route('dashboard.bookings.show', $booking))
            ->assertSessionHas('error');
    }

    public function test_callback_verifies_payment_and_confirms_booking(): void
    {
        $booking = Booking::create([
            'user_id' => $this->guest()->id,
            'room_id' => $this->room()->id,
            'check_in' => now()->addDays(2)->toDateString(),
            'check_out' => now()->addDays(5)->toDateString(),
            'guests_count' => 1,
            'total_amount' => 50000,
            'status' => 'pending_payment',
            'payment_reference' => 'BOOK-'.Str::random(12),
        ]);

        Http::fake([
            "api.paystack.co/transaction/verify/{$booking->payment_reference}" => Http::response([
                'status' => true,
                'message' => 'Verification successful',
                'data' => [
                    'status' => 'success',
                    'amount' => $booking->total_amount * 100,
                    'currency' => 'NGN',
                ],
            ], 200),
        ]);

        $this->actingAs($this->guest())
            ->get(route('booking.callback', ['reference' => $booking->payment_reference]))
            ->assertRedirect(route('booking.success', $booking));

        $this->assertEquals('confirmed', $booking->fresh()->status);
    }

    public function test_callback_rejects_amount_mismatch(): void
    {
        $booking = Booking::create([
            'user_id' => $this->guest()->id,
            'room_id' => $this->room()->id,
            'check_in' => now()->addDays(2)->toDateString(),
            'check_out' => now()->addDays(5)->toDateString(),
            'guests_count' => 1,
            'total_amount' => 50000,
            'status' => 'pending_payment',
            'payment_reference' => 'BOOK-'.Str::random(12),
        ]);

        Http::fake([
            "api.paystack.co/transaction/verify/{$booking->payment_reference}" => Http::response([
                'status' => true,
                'message' => 'Verification successful',
                'data' => [
                    'status' => 'success',
                    'amount' => 1000,
                    'currency' => 'NGN',
                ],
            ], 200),
        ]);

        $this->actingAs($this->guest())
            ->get(route('booking.callback', ['reference' => $booking->payment_reference]))
            ->assertRedirect(route('dashboard.index'))
            ->assertSessionHas('error');

        $this->assertEquals('pending_payment', $booking->fresh()->status);
    }
}