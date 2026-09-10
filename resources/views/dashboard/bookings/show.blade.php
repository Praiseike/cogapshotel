<x-layouts.app>
    @section('title', 'Booking Details')

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="mb-6">
            <a href="{{ route('dashboard.index') }}" class="text-sm text-amber-600 hover:text-amber-700">&larr; Back to My Bookings</a>
        </div>

        <div class="card p-6">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-display font-bold text-gray-900">Booking {{ $booking->payment_reference }}</h1>
                <span class="{{ $booking->getStatusBadgeClass() }}">{{ str_replace('_', ' ', ucfirst($booking->status)) }}</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-500">Room</p>
                        <p class="font-medium text-gray-900">{{ $booking->room->name }} ({{ $booking->room->category->name }})</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Check-in</p>
                        <p class="font-medium text-gray-900">{{ $booking->check_in->format('l, M d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Check-out</p>
                        <p class="font-medium text-gray-900">{{ $booking->check_out->format('l, M d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Duration</p>
                        <p class="font-medium text-gray-900">{{ $booking->getNightsCount() }} nights</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-500">Guests</p>
                        <p class="font-medium text-gray-900">{{ $booking->guests_count }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Total Amount</p>
                        <p class="text-2xl font-bold text-amber-600">&#8358;{{ number_format($booking->total_amount, 2) }}</p>
                    </div>
                    @if($booking->payment)
                        <div>
                            <p class="text-sm text-gray-500">Payment Status</p>
                            <p class="font-medium {{ $booking->payment->isSuccess() ? 'text-emerald-600' : 'text-red-600' }}">{{ ucfirst($booking->payment->status) }}</p>
                        </div>
                    @endif
                    <div>
                        <p class="text-sm text-gray-500">Booked on</p>
                        <p class="font-medium text-gray-900">{{ $booking->created_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>
            </div>

            @if($booking->status === 'pending_payment')
                <div class="mt-8 border-t pt-6">
                    <div class="flex flex-wrap gap-3">
                        <form method="POST" action="{{ route('dashboard.bookings.pay', $booking) }}">
                            @csrf
                            <button type="submit" class="btn-primary">Continue to Payment</button>
                        </form>
                        <form method="POST" action="{{ route('dashboard.bookings.cancel', $booking) }}" onsubmit="return confirm('Are you sure you want to cancel this booking?')">
                            @csrf
                            <button type="submit" class="btn-danger">Cancel Booking</button>
                        </form>
                    </div>
                </div>
            @elseif($booking->status === 'confirmed')
                <div class="mt-8 border-t pt-6">
                    <form method="POST" action="{{ route('dashboard.bookings.cancel', $booking) }}" onsubmit="return confirm('Are you sure you want to cancel this booking?')">
                        @csrf
                        <button type="submit" class="btn-danger">Cancel Booking</button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
