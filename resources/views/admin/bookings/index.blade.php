<x-layouts.admin>
    @section('title', 'Bookings')
    @section('header', 'Bookings')

    <div class="mb-6">
        <form method="GET" class="flex items-center gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search reference, guest, room..." class="input-field max-w-md">
            <select name="status" class="input-field w-auto" onchange="this.form.submit()">
                <option value="">All Status</option>
                <option value="pending_payment" {{ request('status') == 'pending_payment' ? 'selected' : '' }}>Pending</option>
                <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
            </select>
            <button type="submit" class="btn-secondary">Search</button>
        </form>
    </div>

    <div class="card">
        @if($bookings->count())
            <form method="POST" action="{{ route('admin.bookings.bulk') }}" id="bookings-bulk-form">
                @csrf
                <div class="flex flex-wrap items-center gap-3 px-6 py-3 border-b border-gray-100 bg-gray-50/60">
                    <span class="text-xs font-medium uppercase tracking-wider text-gray-500"><span id="bookings-selected-count">0</span> selected</span>
                    <select name="action" class="input-field w-auto !py-2 text-sm" required>
                        <option value="">Bulk action…</option>
                        <option value="confirmed">Mark confirmed</option>
                        <option value="completed">Mark completed</option>
                        <option value="cancelled">Mark cancelled</option>
                    </select>
                    <button type="submit" class="btn-secondary !py-2 text-sm" onclick="return confirmBulkBookings(event)">Apply</button>
                </div>
            </form>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3"><input type="checkbox" id="bookings-select-all" class="rounded border-gray-300" aria-label="Select all"></th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Reference</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Guest</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Room</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Check-in</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Check-out</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($bookings as $booking)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4"><input type="checkbox" name="ids[]" value="{{ $booking->id }}" form="bookings-bulk-form" class="booking-checkbox rounded border-gray-300" aria-label="Select booking {{ $booking->payment_reference }}"></td>
                                <td class="px-6 py-4 text-sm font-mono text-gray-900">{{ $booking->payment_reference }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $booking->user?->name ?? $booking->guest_name ?? 'Guest' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $booking->room?->name ?? '—' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $booking->check_in->format('M d, Y') }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $booking->check_out->format('M d, Y') }}</td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">&#8358;{{ number_format($booking->total_amount, 2) }}</td>
                                <td class="px-6 py-4"><span class="{{ $booking->getStatusBadgeClass() }}">{{ str_replace('_', ' ', ucfirst($booking->status)) }}</span></td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('admin.bookings.show', $booking) }}" class="text-sm text-amber-600 hover:text-amber-700">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-4">{{ $bookings->withQueryString()->links() }}</div>
        @else
            <div class="p-12 text-center text-gray-500"><p>No bookings found.</p></div>
        @endif
    </div>
    <script>
        (function () {
            var selectAll = document.getElementById('bookings-select-all');
            var count = document.getElementById('bookings-selected-count');
            function boxes() { return Array.from(document.querySelectorAll('.booking-checkbox')); }
            function update() { if (count) count.textContent = boxes().filter(function (b) { return b.checked; }).length; }
            if (selectAll) selectAll.addEventListener('change', function () {
                boxes().forEach(function (b) { b.checked = selectAll.checked; });
                update();
            });
            boxes().forEach(function (b) { b.addEventListener('change', update); });
            window.confirmBulkBookings = function (e) {
                var form = document.getElementById('bookings-bulk-form');
                var action = form.querySelector('select[name=action]').value;
                var n = boxes().filter(function (b) { return b.checked; }).length;
                if (!n) { alert('Select at least one booking first.'); e.preventDefault(); return false; }
                if (!action) { alert('Choose a bulk action first.'); e.preventDefault(); return false; }
                if (action === 'cancelled' && !confirm('Cancel ' + n + ' booking(s)? This releases the rooms.')) { e.preventDefault(); return false; }
                return true;
            };
        })();
    </script>
</x-layouts.admin>
