<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Services\BookingService;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaystackWebhookController extends Controller
{
    public function __construct(
        protected BookingService $bookingService,
        protected PaymentService $paymentService,
    ) {}

    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $signature = $request->header('x-paystack-signature');

        if (! $this->paymentService->verifyWebhookSignature($payload, $signature)) {
            Log::warning('Invalid Paystack webhook signature');
            \App\Support\ActivityLogger::log('webhook.rejected', null, [], 'Rejected webhook: bad signature');

            return response('Invalid signature', 400);
        }

        $data = $request->all();
        $event = $data['event'] ?? '';

        try {
            match ($event) {
                'charge.success' => $this->handleChargeSuccess($data['data']),
                'charge.failed' => $this->handleChargeFailed($data['data']),
                default => Log::info("Unhandled Paystack event: {$event}"),
            };
        } catch (\Exception $e) {
            Log::error("Webhook processing error: {$e->getMessage()}");
        }

        return response('OK', 200);
    }

    protected function handleChargeSuccess(array $data): void
    {
        $reference = $data['reference'] ?? '';

        if ($reference === '') {
            Log::error('Webhook: charge.success missing reference');

            return;
        }

        $booking = Booking::where('payment_reference', $reference)->first();

        if (! $booking) {
            Log::error("Webhook: Booking not found for reference {$reference}");

            return;
        }

        if ($booking->status === 'confirmed') {
            return;
        }

        // Never confirm a cancelled/completed booking via webhook.
        if ($booking->status !== 'pending_payment') {
            Log::warning("Webhook: ignoring charge.success for booking {$booking->id} with status {$booking->status}");

            return;
        }

        // Verify the paid amount matches the booking (kobo vs NGN).
        $paidKobo = (int) ($data['amount'] ?? 0);
        $expectedKobo = (int) round((float) $booking->total_amount * 100);

        if ($paidKobo !== $expectedKobo) {
            Log::error("Webhook: amount mismatch for booking {$booking->id}: expected {$expectedKobo}, got {$paidKobo}");

            return;
        }

        // Only accept success statuses from Paystack.
        $gatewayStatus = $data['status'] ?? '';
        if ($gatewayStatus !== '' && $gatewayStatus !== 'success') {
            Log::warning("Webhook: ignoring non-success status '{$gatewayStatus}' for booking {$booking->id}");

            return;
        }

        $this->bookingService->confirmBooking($booking, $reference, $data, 'webhook');

        Log::info("Webhook: Booking {$booking->id} confirmed via webhook");
    }

    protected function handleChargeFailed(array $data): void
    {
        $reference = $data['reference'] ?? '';

        if ($reference === '') {
            return;
        }

        $booking = Booking::where('payment_reference', $reference)->first();

        // Never cancel an already-confirmed (paid) booking on a failed event.
        // A failed attempt for a new reference must not kill a paid booking.
        if ($booking && $booking->status === 'pending_payment') {
            $booking->update(['status' => 'cancelled']);
            Log::info("Webhook: Booking {$booking->id} cancelled - payment failed");
            \App\Support\ActivityLogger::log('booking.payment_failed', $booking, ['reference' => $reference], "Payment failed for {$reference}");
        }
    }
}
