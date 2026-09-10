<?php

namespace App\Services;

use App\Exceptions\BookingConflictException;
use App\Models\Booking;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BookingService
{
    public function createBooking(
        int $roomId,
        string $checkIn,
        string $checkOut,
        int $guestsCount,
        int $userId,
    ): Booking {
        return DB::transaction(function () use ($roomId, $checkIn, $checkOut, $guestsCount, $userId) {
            $room = Room::where('id', $roomId)
                ->where('is_available', true)
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
                'room_id' => $roomId,
                'check_in' => $checkIn,
                'check_out' => $checkOut,
                'guests_count' => $guestsCount,
                'total_amount' => $totalAmount,
                'status' => 'pending_payment',
                'payment_reference' => 'BOOK-'.Str::random(12),
            ]);
        });
    }

    public function confirmBooking(Booking $booking, string $paystackReference, array $gatewayResponse): Booking
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

            $booking->payment()->create([
                'paystack_reference' => $paystackReference,
                'amount' => $booking->total_amount,
                'currency' => 'NGN',
                'status' => 'success',
                'gateway_response' => $gatewayResponse,
                'paid_at' => now(),
            ]);
        });

        return $booking->fresh();
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
        $expired = Booking::where('status', 'pending_payment')
            ->where('created_at', '<', now()->subMinutes(30))
            ->get();

        $count = 0;
        foreach ($expired as $booking) {
            $booking->update(['status' => 'cancelled']);
            $count++;
        }

        return $count;
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
