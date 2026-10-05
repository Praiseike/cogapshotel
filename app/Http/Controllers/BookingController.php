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
            'guest_name' => [auth()->check() ? 'nullable' : 'required', 'string', 'max:255'],
            'guest_email' => [auth()->check() ? 'nullable' : 'required', 'email', 'max:255'],
            'guest_phone' => 'nullable|string|max:30',
            'source' => 'nullable|string|in:direct,google,instagram,facebook,referral,agent,other',
        ]);

        try {
            $booking = $this->bookingService->createBooking(
                roomId: $validated['room_id'],
                checkIn: $validated['check_in'],
                checkOut: $validated['check_out'],
                guestsCount: $validated['guests_count'],
                userId: auth()->id(),
                guestName: $validated['guest_name'] ?? auth()->user()?->name,
                guestEmail: $validated['guest_email'] ?? auth()->user()?->email,
                guestPhone: $validated['guest_phone'] ?? null,
                source: $validated['source'] ?? null,
            );

            // Let guests reach their success page without an account.
            if (! auth()->check()) {
                $request->session()->push('guest_bookings', $booking->id);
            }

            \App\Support\ActivityLogger::log(
                'booking.created',
                $booking,
                [
                    'room' => $booking->room?->name,
                    'check_in' => $validated['check_in'],
                    'check_out' => $validated['check_out'],
                    'amount' => (float) $booking->total_amount,
                    'guest' => ! auth()->check(),
                ],
                "Booking {$booking->payment_reference} started ({$booking->room?->name})"
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
        // Guests have no dashboard — send payment problems to a page they can see.
        $fallback = auth()->check() ? route('dashboard.index') : route('home');

        $reference = $request->query('reference');

        if (! $reference) {
            return redirect($fallback)
                ->with('error', 'No payment reference found.');
        }

        try {
            $paymentData = $this->paymentService->verifyTransaction($reference);

            $booking = Booking::where('payment_reference', $reference)->firstOrFail();

            if ($paymentData['status'] === 'success' && $paymentData['amount'] == $booking->total_amount * 100) {
                $this->bookingService->confirmBooking($booking, $reference, $paymentData, 'callback');

                // Keep guest access to the success page even if the session
                // was started on another device/tab before paying.
                if (! auth()->check()) {
                    $request->session()->push('guest_bookings', $booking->id);
                }

                return redirect()->route('booking.success', $booking)
                    ->with('success', 'Booking confirmed successfully!');
            }

            return redirect($fallback)
                ->with('error', 'Payment was not successful. Please try again.');
        } catch (\Exception $e) {
            return redirect($fallback)
                ->with('error', 'Unable to verify payment. Please contact support.');
        }
    }

    public function success(Request $request, Booking $booking)
    {
        // Owner, linked account (via guest email), or guest session from this browser.
        $guestIds = $request->session()->get('guest_bookings', []);
        $ownsById = auth()->check() && $booking->user_id === auth()->id();
        $ownsByEmail = auth()->check() && $booking->guest_email
            && strtolower($booking->guest_email) === strtolower(auth()->user()->email);
        $ownsBySession = in_array($booking->id, (array) $guestIds);

        if (! ($ownsById || $ownsByEmail || $ownsBySession)) {
            abort(403);
        }

        $booking->load('room.category', 'payment');

        return view('bookings.success', compact('booking'));
    }
}
