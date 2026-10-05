<?php

namespace App\Services;

use App\Models\Booking;
use Illuminate\Support\Facades\Http;

class PaymentService
{
    protected string $secretKey;

    protected string $baseUrl;

    public function __construct()
    {
        $this->secretKey = config('paystack.secretKey');
        $this->baseUrl = config('paystack.paymentUrl', 'https://api.paystack.co');
    }

    public function initializeTransaction(Booking $booking): array
    {
        $email = $booking->user?->email ?? $booking->guest_email;

        if (empty($email)) {
            throw new \RuntimeException('Booking has no email address for payment initialization.');
        }

        $response = Http::withToken($this->secretKey)
            ->post("{$this->baseUrl}/transaction/initialize", [
                'email' => $email,
                'amount' => (int) ($booking->total_amount * 100),
                'reference' => $booking->payment_reference,
                'callback_url' => route('booking.callback'),
                'metadata' => [
                    'booking_id' => $booking->id,
                    'booking_reference' => $booking->payment_reference,
                ],
            ]);

        $data = $response->json();

        if (! $data['status']) {
            throw new \RuntimeException('Failed to initialize payment: '.($data['message'] ?? 'Unknown error'));
        }

        return $data['data'];
    }

    public function verifyTransaction(string $reference): array
    {
        $response = Http::withToken($this->secretKey)
            ->get("{$this->baseUrl}/transaction/verify/{$reference}");

        $data = $response->json();

        if (! $data['status']) {
            throw new \RuntimeException('Failed to verify payment: '.($data['message'] ?? 'Unknown error'));
        }

        return $data['data'];
    }

    public function verifyWebhookSignature(string $payload, ?string $signature): bool
    {
        if (empty($signature)) {
            return false;
        }

        $secretKey = config('paystack.secretKey');

        if (empty($secretKey)) {
            return false;
        }

        $computedSignature = hash_hmac('sha512', $payload, $secretKey);

        if (strlen($computedSignature) !== strlen($signature)) {
            return false;
        }

        return hash_equals($computedSignature, $signature);
    }
}
