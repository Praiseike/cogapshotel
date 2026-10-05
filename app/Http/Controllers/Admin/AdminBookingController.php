<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class AdminBookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['user', 'room']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('payment_reference', 'like', "%{$search}%")
                    ->orWhereHas('user', fn ($q) => $q->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('room', fn ($q) => $q->where('name', 'like', "%{$search}%"));
            });
        }

        $bookings = $query->latest()->paginate(20);

        return view('admin.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        $booking->load(['user', 'room.category', 'payment']);

        return view('admin.bookings.show', compact('booking'));
    }

    public function update(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending_payment,confirmed,cancelled,completed',
        ]);

        $from = $booking->status;
        $booking->update($validated);

        \App\Support\ActivityLogger::log(
            'admin.booking_updated',
            $booking,
            ['from' => $from, 'to' => $validated['status']],
            "Booking {$booking->payment_reference}: {$from} → {$validated['status']}"
        );

        return redirect()->route('admin.bookings.show', $booking)
            ->with('success', 'Booking status updated successfully.');
    }

    public function bulk(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:bookings,id',
            'action' => 'required|in:confirmed,cancelled,completed',
        ]);

        $count = Booking::whereIn('id', $validated['ids'])
            ->update(['status' => $validated['action']]);

        \App\Support\ActivityLogger::log(
            'admin.booking_bulk',
            null,
            ['action' => $validated['action'], 'count' => $count],
            "Bulk: {$count} booking(s) → {$validated['action']}"
        );

        return back()->with('success', "{$count} booking(s) marked as {$validated['action']}.");
    }
}
