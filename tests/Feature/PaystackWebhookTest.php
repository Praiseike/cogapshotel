<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Room;
use App\Models\User;
use App\Services\PaymentService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Tests\TestCase as BaseTestCase;

/**
 * Hard guarantees for the Paystack webhook (/webhook/paystack).
 *
 * These are the gaps that lose money on shared hosting if untested:
 *  - forged / missing signatures must never confirm a booking
 *  - amount mismatches must never confirm a booking
 *  - duplicate deliveries (Paystack retries) must be idempotent
 *  - charge.failed must never cancel an already-paid booking
 *  - unknown references / events must return 200 without crashing
 */
final class PaystackWebhookTest extends BaseTestCase
{
    use DatabaseTransactions;

    protected function guest(): User
    {
        return User::where('role', 'guest')->firstOrFail();
    }

    protected function room(): Room
    {
        return Room::where('is_available', true)->firstOrFail();
    }

    protected function makePendingBooking(array $overrides = []): Booking
    {
        return Booking::create(array_merge([
            'user_id' => $this->guest()->id,
            'room_id' => $this->room()->id,
            'check_in' => now()->addDays(2)->toDateString(),
            'check_out' => now()->addDays(5)->toDateString(),
            'guests_count' => 1,
            'total_amount' => 50000,
            'status' => 'pending_payment',
            'payment_reference' => 'BOOK-'.Str::random(12),
        ], $overrides));
    }

    /** @return array{0: string, 1: string} raw body + signature */
    protected function sign(array $payload): array
    {
        $raw = json_encode($payload);
        $signature = hash_hmac('sha512', $raw, config('paystack.secretKey'));

        return [$raw, $signature];
    }

    protected function postWebhook(string $raw, ?string $signature)
    {
        $server = ['CONTENT_TYPE' => 'application/json'];
        if ($signature !== null) {
            $server['HTTP_x-paystack-signature'] = $signature;
        }

        return $this->call('POST', '/webhook/paystack', [], [], [], $server, $raw);
    }

    public function test_charge_success_confirms_pending_booking_and_creates_payment(): void
    {
        $booking = $this->makePendingBooking();

        [$raw, $sig] = $this->sign([
            'event' => 'charge.success',
            'data' => [
                'reference' => $booking->payment_reference,
                'amount' => 50000 * 100,
                'currency' => 'NGN',
                'status' => 'success',
            ],
        ]);

        $this->postWebhook($raw, $sig)->assertOk();

        $this->assertSame('confirmed', $booking->fresh()->status);
        $this->assertDatabaseHas('payments', [
            'booking_id' => $booking->id,
            'paystack_reference' => $booking->payment_reference,
            'status' => 'success',
        ]);
    }

    public function test_missing_signature_is_rejected_with_400_and_changes_nothing(): void
    {
        $booking = $this->makePendingBooking();

        $raw = json_encode([
            'event' => 'charge.success',
            'data' => [
                'reference' => $booking->payment_reference,
                'amount' => 50000 * 100,
                'status' => 'success',
            ],
        ]);

        // No signature header at all — must not 500, must not confirm.
        $this->postWebhook($raw, null)->assertStatus(400);
        $this->assertSame('pending_payment', $booking->fresh()->status);
    }

    public function test_tampered_payload_fails_signature_check(): void
    {
        $booking = $this->makePendingBooking();

        [$raw, $sig] = $this->sign([
            'event' => 'charge.success',
            'data' => [
                'reference' => $booking->payment_reference,
                'amount' => 50000 * 100,
                'status' => 'success',
            ],
        ]);

        // Attacker modifies amount after signing.
        $tampered = json_encode([
            'event' => 'charge.success',
            'data' => [
                'reference' => $booking->payment_reference,
                'amount' => 100, // 1 NGN instead of 50,000
                'status' => 'success',
            ],
        ]);

        $this->postWebhook($tampered, $sig)->assertStatus(400);
        $this->assertSame('pending_payment', $booking->fresh()->status);
    }

    public function test_amount_mismatch_with_valid_signature_does_not_confirm(): void
    {
        $booking = $this->makePendingBooking(['total_amount' => 50000]);

        // Correctly signed, but underpaid — must not confirm.
        [$raw, $sig] = $this->sign([
            'event' => 'charge.success',
            'data' => [
                'reference' => $booking->payment_reference,
                'amount' => 1000, // 10 NGN vs 50,000 NGN owed
                'currency' => 'NGN',
                'status' => 'success',
            ],
        ]);

        $this->postWebhook($raw, $sig)->assertOk();
        $this->assertSame('pending_payment', $booking->fresh()->status);
        $this->assertDatabaseMissing('payments', ['booking_id' => $booking->id]);
    }

    public function test_duplicate_charge_success_is_idempotent(): void
    {
        $booking = $this->makePendingBooking();

        [$raw, $sig] = $this->sign([
            'event' => 'charge.success',
            'data' => [
                'reference' => $booking->payment_reference,
                'amount' => 50000 * 100,
                'currency' => 'NGN',
                'status' => 'success',
            ],
        ]);

        // Paystack retries delivery — send the exact same webhook twice.
        $this->postWebhook($raw, $sig)->assertOk();
        $this->postWebhook($raw, $sig)->assertOk();

        $this->assertSame('confirmed', $booking->fresh()->status);
        $this->assertSame(
            1,
            \App\Models\Payment::where('booking_id', $booking->id)->count(),
            'Duplicate webhook must not create duplicate payment rows.'
        );
    }

    public function test_charge_failed_does_not_cancel_confirmed_booking(): void
    {
        $booking = $this->makePendingBooking(['status' => 'confirmed']);

        [$raw, $sig] = $this->sign([
            'event' => 'charge.failed',
            'data' => ['reference' => $booking->payment_reference],
        ]);

        $this->postWebhook($raw, $sig)->assertOk();
        $this->assertSame('confirmed', $booking->fresh()->status);
    }

    public function test_charge_failed_cancels_only_pending_booking(): void
    {
        $booking = $this->makePendingBooking();

        [$raw, $sig] = $this->sign([
            'event' => 'charge.failed',
            'data' => ['reference' => $booking->payment_reference],
        ]);

        $this->postWebhook($raw, $sig)->assertOk();
        $this->assertSame('cancelled', $booking->fresh()->status);
    }

    public function test_unknown_reference_returns_200_without_crash(): void
    {
        [$raw, $sig] = $this->sign([
            'event' => 'charge.success',
            'data' => [
                'reference' => 'BOOK-DOESNOTEXIST',
                'amount' => 50000 * 100,
                'status' => 'success',
            ],
        ]);

        $this->postWebhook($raw, $sig)->assertOk();
    }

    public function test_unhandled_event_returns_200_without_changing_booking(): void
    {
        $booking = $this->makePendingBooking();

        [$raw, $sig] = $this->sign([
            'event' => 'transfer.success',
            'data' => ['reference' => $booking->payment_reference],
        ]);

        $this->postWebhook($raw, $sig)->assertOk();
        $this->assertSame('pending_payment', $booking->fresh()->status);
    }

    public function test_signature_verification_unit_cases(): void
    {
        /** @var PaymentService $svc */
        $svc = app(PaymentService::class);
        $payload = '{"event":"charge.success"}';
        $good = hash_hmac('sha512', $payload, config('paystack.secretKey'));

        $this->assertTrue($svc->verifyWebhookSignature($payload, $good));
        $this->assertFalse($svc->verifyWebhookSignature($payload, 'invalid'));
        $this->assertFalse($svc->verifyWebhookSignature($payload, null));
        $this->assertFalse($svc->verifyWebhookSignature($payload, ''));
    }
}
