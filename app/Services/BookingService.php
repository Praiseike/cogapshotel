<?php

namespace App\Services;

use App\Exceptions\BookingConflictException;
use App\Mail\BookingConfirmed;
use App\Models\Booking;
use App\Models\Room;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class BookingService
{
    public function createBooking(
        int $roomId,
        string $checkIn,
        string $checkOut,
        int $guestsCount,
        ?int $userId,
        ?string $guestName = null,
        ?string $guestEmail = null,
        ?string $guestPhone = null,
        ?string $source = null,
    ): Booking {
        return DB::transaction(function () use ($roomId, $checkIn, $checkOut, $guestsCount, $userId, $guestName, $guestEmail, $guestPhone, $source) {
            $room = Room::where('id', $roomId)
                ->where('is_available', true)
                ->where('status', 'available')
                ->lockForUpdate()
                ->firstOrFail();

            $hasOverlap = Booking::where('room_id', $roomId)
                ->whereIn('status', ['confirmed', 'pending_payment'])
                ->where('check_in', '<', $checkOut)
                ->where('check_out', '>', $checkIn)
                ->exists();

            if ($hasOverlap) {
                throw new BookingConflictException('Room is not available for the selected dates.');
            }

            $nights = Carbon::parse($checkIn)->diffInDays($checkOut);
            $totalAmount = $room->price_per_night * $nights;

            return Booking::create([
                'user_id' => $userId,
                'guest_name' => $guestName,
                'guest_email' => $guestEmail ? strtolower($guestEmail) : null,
                'guest_phone' => $guestPhone,
                'room_id' => $roomId,
                'check_in' => $checkIn,
                'check_out' => $checkOut,
                'guests_count' => $guestsCount,
                'total_amount' => $totalAmount,
                'status' => 'pending_payment',
                'payment_reference' => 'BOOK-'.Str::random(12),
                'source' => $source && array_key_exists($source, Booking::SOURCES) ? $source : null,
            ]);
        });
    }

    public function confirmBooking(Booking $booking, string $paystackReference, array $gatewayResponse, ?string $via = null): Booking
    {
        if ($booking->status === 'confirmed') {
            return $booking;
        }

        DB::transaction(function () use ($booking, $paystackReference, $gatewayResponse) {
            $booking->update([
                'status' => 'confirmed',
                'payment_id' => $paystackReference,
                'paid_at' => now(),
            ]);

            // Idempotent: webhook + callback can both fire for the same reference.
            // paystack_reference is unique, so reuse the row instead of creating duplicates.
            $booking->payment()->updateOrCreate(
                ['paystack_reference' => $paystackReference],
                [
                    'booking_id' => $booking->id,
                    'amount' => $booking->total_amount,
                    'currency' => 'NGN',
                    'status' => 'success',
                    'gateway_response' => $gatewayResponse,
                    'paid_at' => now(),
                ]
            );
        });

        $fresh = $booking->fresh(['user', 'room']);

        // Email notifications (queued if queue driver configured, fallback to sync)
        try {
            $recipient = $fresh->user?->email ?? $fresh->guest_email;
            if ($recipient) {
                Mail::to($recipient)->send(new BookingConfirmed($fresh));
            }

            $adminEmail = Setting::getValue('hotel_email');
            if ($adminEmail && filter_var($adminEmail, FILTER_VALIDATE_EMAIL)) {
                Mail::to($adminEmail)->send(new BookingConfirmed($fresh));
            }
        } catch (\Throwable $e) {
            Log::warning('Booking confirmation email failed: '.$e->getMessage(), ['booking_id' => $booking->id]);
        }

        \App\Support\ActivityLogger::log(
            'booking.confirmed',
            $fresh,
            [
                'reference' => $paystackReference,
                'amount' => (float) $fresh->total_amount,
                'via' => $via,
            ],
            "Booking {$paystackReference} confirmed".($via ? " via {$via}" : '')
        );

        return $fresh;
    }

    public function cancelBooking(Booking $booking): Booking
    {
        if (in_array($booking->status, ['cancelled', 'completed'])) {
            return $booking;
        }

        $booking->update(['status' => 'cancelled']);

        return $booking->fresh();
    }

    public function releaseExpiredBookings(): int
    {
        // Single atomic UPDATE: a booking confirmed (paid) a millisecond
        // before this runs no longer matches status=pending_payment, so a
        // paid booking can never be cancelled by the expiry job.
        // (The old read-then-update loop had exactly that race.)
        return Booking::where('status', 'pending_payment')
            ->where('created_at', '<', now()->subMinutes(30))
            ->update(['status' => 'cancelled']);
    }

    public function checkAvailability(int $roomId, string $checkIn, string $checkOut): bool
    {
        $room = Room::find($roomId);
        if (! $room || ! $room->is_available) {
            return false;
        }

        return $room->isAvailableForDates($checkIn, $checkOut);
    }
}
