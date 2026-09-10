<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Services\BookingService;

class GuestBookingController extends Controller
{
    public function __construct(
        protected BookingService $bookingService,
    ) {}

    public function index()
    {
        $bookings = Booking::where('user_id', auth()->id())
            ->with('room.category')
            ->latest()
            ->paginate(10);

        return view('dashboard.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        $booking->load('room.category', 'payment');

        return view('dashboard.bookings.show', compact('booking'));
    }

    public function cancel(Booking $booking)
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        $this->bookingService->cancelBooking($booking);

        return redirect()->route('dashboard.bookings.show', $booking)
            ->with('success', 'Booking cancelled successfully.');
    }
}
