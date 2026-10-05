<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AvailabilityController extends Controller
{
    public function bookedDates(Room $room)
    {
        // Return all booked dates for calendar disabling (next 6 months)
        $bookings = $room->bookings()
            ->whereIn('status', ['confirmed', 'pending_payment'])
            ->where('check_out', '>=', now()->toDateString())
            ->get(['check_in', 'check_out']);

        $disabled = [];
        foreach ($bookings as $b) {
            $period = Carbon::parse($b->check_in)->daysUntil(Carbon::parse($b->check_out));
            foreach ($period as $date) {
                $disabled[] = $date->format('Y-m-d');
            }
        }

        return response()->json([
            'room_id' => $room->id,
            'disabled_dates' => array_values(array_unique($disabled)),
        ]);
    }

    public function check(Request $request, Room $room)
    {
        $validated = $request->validate([
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
        ]);

        $available = $room->isAvailableForDates($validated['check_in'], $validated['check_out']);

        return response()->json([
            'available' => $available,
            'room_id' => $room->id,
            'check_in' => $validated['check_in'],
            'check_out' => $validated['check_out'],
        ]);
    }

    public function quote(Request $request, Room $room)
    {
        $validated = $request->validate([
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
        ]);

        $checkIn = Carbon::parse($validated['check_in']);
        $checkOut = Carbon::parse($validated['check_out']);
        $nights = $checkIn->diffInDays($checkOut);
        $available = $room->isAvailableForDates($validated['check_in'], $validated['check_out']);
        $subtotal = (float) $room->price_per_night * $nights;

        return response()->json([
            'available' => $available,
            'nights' => $nights,
            'price_per_night' => (float) $room->price_per_night,
            'subtotal' => $subtotal,
            'total' => $subtotal,
            'currency' => 'NGN',
        ]);
    }
}
