<x-layouts.app>
    @section('title', 'My Bookings')

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-display font-bold text-gray-900">My Bookings</h1>
                <p class="mt-2 text-gray-600">Manage your reservations and view booking history.</p>
            </div>
            <a href="{{ route('rooms.index') }}" class="btn-primary">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                Book a Room
            </a>
        </div>

        @if($bookings->count())
            <div class="space-y-4">
                @foreach($bookings as $booking)
                    <a href="{{ route('dashboard.bookings.show', $booking) }}" class="card p-6 flex flex-col sm:flex-row sm:items-center gap-4 hover:shadow-md transition">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <h3 class="font-semibold text-gray-900">{{ $booking->room->name }}</h3>
                                <span class="{{ $booking->getStatusBadgeClass() }}">{{ str_replace('_', ' ', ucfirst($booking->status)) }}</span>
                            </div>
                            <p class="text-sm text-gray-500">{{ $booking->room->category->name }}</p>
                            <div class="flex items-center gap-4 mt-2 text-sm text-gray-600">
                                <span>{{ $booking->check_in->format('M d') }} - {{ $booking->check_out->format('M d, Y') }}</span>
                                <span>{{ $booking->getNightsCount() }} nights</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-bold text-amber-600">&#8358;{{ number_format($booking->total_amount, 2) }}</p>
                            <p class="text-xs text-gray-500 mt-1 font-mono">{{ $booking->payment_reference }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
            <div class="mt-8">{{ $bookings->links() }}</div>
        @else
            <div class="text-center py-12 text-gray-500">
                <svg class="w-12 h-12 mx-auto text-gray-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" /></svg>
                <p class="mt-4">No bookings yet.</p>
                <a href="{{ route('rooms.index') }}" class="mt-4 inline-flex items-center text-amber-600 hover:text-amber-700 font-medium">
                    Browse Rooms
                    <svg class="ml-1 w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" /></svg>
                </a>
            </div>
        @endif
    </div>
</x-layouts.app>