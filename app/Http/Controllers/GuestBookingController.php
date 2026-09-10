<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Services\BookingService;
use App\Services\PaymentService;

class GuestBookingController extends Controller
{
    public function __construct(
        protected BookingService $bookingService,
        protected PaymentService $paymentService,
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

    public function pay(Booking $booking)
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        if ($booking->status !== 'pending_payment') {
            return redirect()->route('dashboard.bookings.show', $booking)
                ->with('error', 'Only pending-payment bookings can be paid.');
        }

        try {
            $payment = $this->paymentService->initializeTransaction($booking);
        } catch (\Throwable $e) {
            return redirect()->route('dashboard.bookings.show', $booking)
                ->with('error', 'Could not reach the payment gateway. Please try again.');
        }

        return redirect()->away($payment['authorization_url']);
    }
}
