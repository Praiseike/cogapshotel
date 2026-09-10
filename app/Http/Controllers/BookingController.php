<?php

namespace App\Http\Controllers;

use App\Exceptions\BookingConflictException;
use App\Models\Booking;
use App\Services\BookingService;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function __construct(
        protected BookingService $bookingService,
        protected PaymentService $paymentService,
    ) {}

    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'guests_count' => 'required|integer|min:1|max:10',
        ]);

        try {
            $booking = $this->bookingService->createBooking(
                roomId: $validated['room_id'],
                checkIn: $validated['check_in'],
                checkOut: $validated['check_out'],
                guestsCount: $validated['guests_count'],
                userId: auth()->id(),
            );

            $paymentData = $this->paymentService->initializeTransaction($booking);

            return redirect($paymentData['authorization_url']);
        } catch (BookingConflictException $e) {
            return back()->with('error', $e->getMessage())->withInput();
        } catch (\Exception $e) {
            return back()->with('error', 'Unable to create booking. Please try again.')->withInput();
        }
    }

    public function callback(Request $request)
    {
        $reference = $request->query('reference');

        if (! $reference) {
            return redirect()->route('dashboard.index')
                ->with('error', 'No payment reference found.');
        }

        try {
            $paymentData = $this->paymentService->verifyTransaction($reference);

            $booking = Booking::where('payment_reference', $reference)->firstOrFail();

            if ($paymentData['status'] === 'success' && $paymentData['amount'] == $booking->total_amount * 100) {
                $this->bookingService->confirmBooking($booking, $reference, $paymentData);

                return redirect()->route('booking.success', $booking)
                    ->with('success', 'Booking confirmed successfully!');
            }

            return redirect()->route('dashboard.index')
                ->with('error', 'Payment was not successful. Please try again.');
        } catch (\Exception $e) {
            return redirect()->route('dashboard.index')
                ->with('error', 'Unable to verify payment. Please contact support.');
        }
    }

    public function success(Booking $booking)
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        $booking->load('room.category', 'payment');

        return view('bookings.success', compact('booking'));
    }
}
