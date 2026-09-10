<x-layouts.app>
    @section('title', 'Booking Confirmed')

    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center">
        <div class="w-20 h-20 bg-emerald-100 rounded-full flex items-center justify-center mx-auto">
            <svg class="w-10 h-10 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        </div>

        <h1 class="mt-6 text-3xl font-display font-bold text-gray-900">Booking Confirmed!</h1>
        <p class="mt-3 text-lg text-gray-600">Thank you for your reservation. A confirmation email has been sent to you.</p>

        <div class="mt-8 card p-6 text-left">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Booking Details</h2>
            <dl class="grid grid-cols-2 gap-4">
                <div>
                    <dt class="text-sm text-gray-500">Reference</dt>
                    <dd class="font-mono font-medium text-gray-900">{{ $booking->payment_reference }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-500">Room</dt>
                    <dd class="font-medium text-gray-900">{{ $booking->room->name }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-500">Check-in</dt>
                    <dd class="font-medium text-gray-900">{{ $booking->check_in->format('M d, Y') }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-500">Check-out</dt>
                    <dd class="font-medium text-gray-900">{{ $booking->check_out->format('M d, Y') }}</dd>
                </div>
                <div class="col-span-2 border-t pt-4">
                    <dt class="text-sm text-gray-500">Total Paid</dt>
                    <dd class="text-2xl font-bold text-amber-600">&#8358;{{ number_format($booking->total_amount, 2) }}</dd>
                </div>
            </dl>
        </div>

        <div class="mt-8 flex items-center justify-center gap-4">
            <a href="{{ route('dashboard.index') }}" class="btn-primary">View My Bookings</a>
            <a href="{{ route('home') }}" class="btn-secondary">Back to Home</a>
        </div>
    </div>
</x-layouts.app>
