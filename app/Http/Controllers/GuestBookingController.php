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
        $this->linkEmailBookings();

        $bookings = Booking::where(function ($q) {
            $q->where('user_id', auth()->id())
                ->orWhere('guest_email', strtolower(auth()->user()->email));
        })
            ->with('room.category')
            ->latest()
            ->paginate(10);

        return view('dashboard.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        $this->authorizeBooking($booking);

        $booking->load('room.category', 'payment');

        return view('dashboard.bookings.show', compact('booking'));
    }

    public function cancel(Booking $booking)
    {
        $this->authorizeBooking($booking);

        $this->bookingService->cancelBooking($booking);
        \App\Support\ActivityLogger::log('booking.cancelled', $booking, [], "Booking {$booking->payment_reference} cancelled by guest");

        return redirect()->route('dashboard.bookings.show', $booking)
            ->with('success', 'Booking cancelled successfully.');
    }

    public function pay(Booking $booking)
    {
        $this->authorizeBooking($booking);

        if ($booking->status !== 'pending_payment') {
            return redirect()->route('dashboard.bookings.show', $booking)
                ->with('error', 'Only pending-payment bookings can be paid.');
        }

        try {
            \App\Support\ActivityLogger::log('booking.repay', $booking, [], "Retry payment started for {$booking->payment_reference}");
            $payment = $this->paymentService->initializeTransaction($booking);
        } catch (\Throwable $e) {
            return redirect()->route('dashboard.bookings.show', $booking)
                ->with('error', 'Could not reach the payment gateway. Please try again.');
        }

        return redirect()->away($payment['authorization_url']);
    }

    /**
     * A booking belongs to the signed-in user when the user id matches
     * OR the booking's guest email matches the account email (guest checkout).
     */
    protected function authorizeBooking(Booking $booking): void
    {
        $ownsById = $booking->user_id === auth()->id();
        $ownsByEmail = $booking->guest_email
            && strtolower($booking->guest_email) === strtolower(auth()->user()->email);

        if (! ($ownsById || $ownsByEmail)) {
            abort(403);
        }
    }

    /**
     * Retro-link: bookings made as a guest (same email) before the
     * account existed now belong to this user.
     */
    protected function linkEmailBookings(): void
    {
        Booking::whereNull('user_id')
            ->where('guest_email', strtolower(auth()->user()->email))
            ->update(['user_id' => auth()->id()]);
    }
}
