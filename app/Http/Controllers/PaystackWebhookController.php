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

        $booking = Booking::where('payment_reference', $reference)->first();

        if (! $booking) {
            Log::error("Webhook: Booking not found for reference {$reference}");

            return;
        }

        if ($booking->status === 'confirmed') {
            return;
        }

        $this->bookingService->confirmBooking($booking, $reference, $data);

        Log::info("Webhook: Booking {$booking->id} confirmed via webhook");
    }

    protected function handleChargeFailed(array $data): void
    {
        $reference = $data['reference'] ?? '';

        $booking = Booking::where('payment_reference', $reference)->first();

        if ($booking) {
            $booking->update(['status' => 'cancelled']);
            Log::info("Webhook: Booking {$booking->id} cancelled - payment failed");
        }
    }
}
