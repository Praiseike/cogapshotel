<x-layouts.admin>
    @section('title', 'Admin Dashboard')
    @section('header', 'Dashboard')

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Bookings</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900">{{ $totalBookings }}</p>
                    <p class="mt-1 text-xs text-gray-400">{{ $confirmedBookings }} confirmed · {{ $cancelledBookings }} cancelled</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" /></svg>
                </div>
            </div>
        </div>

        <div class="card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Occupancy Today</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900">{{ $occupiedToday }} / {{ $totalRooms }}</p>
                    <p class="mt-1 text-xs {{ $occupancyRate > 80 ? 'text-red-600' : 'text-emerald-600' }}">{{ $occupancyRate }}% occupied</p>
                </div>
                <div class="w-12 h-12 bg-amber-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 21v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21m0 0h4.5V3.545M12.75 21h7.5V10.75M2.25 21h1.5m18 0h-18M2.25 9l4.5-1.636M18.75 3l-1.5.545m0 6.205l3 1m1.5.5l-1.5-.5M6.75 7.364V3h-3v18m3-13.636l10.5-3.819" /></svg>
                </div>
            </div>
            <div class="mt-4 h-2 bg-gray-100 rounded-full overflow-hidden">
                <div class="h-full bg-amber-500 transition-all" style="width: {{ $occupancyRate }}%"></div>
            </div>
        </div>

        <div class="card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Revenue (Confirmed)</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900">&#8358;{{ number_format($totalRevenue, 0) }}</p>
                    <p class="mt-1 text-xs text-gray-400">Last 6 months</p>
                </div>
                <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
            </div>
        </div>

        <div class="card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Pending / Expiring</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900">{{ $pendingBookings }}</p>
                    <p class="mt-1 text-xs {{ $expiringSoon > 0 ? 'text-amber-600' : 'text-gray-400' }}">{{ $expiringSoon }} expiring soon (30m)</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
            </div>
            @if($pendingBookings > 0)
            <a href="{{ route('admin.bookings.index', ['status' => 'pending_payment']) }}" class="mt-3 inline-block text-xs text-purple-600 hover:underline">View pending →</a>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-8">
        <div class="card p-6 lg:col-span-2">
            <div class="flex items-center justify-between mb-6">
                <h3 class="font-semibold text-gray-900">Revenue — Last 6 Months</h3>
                <span class="text-xs text-gray-400">Confirmed bookings only</span>
            </div>
            <div class="flex items-end gap-2 h-40">
                @foreach($revenueChart as $i => $val)
                    <div class="flex-1 flex flex-col items-center gap-2">
                        <div class="w-full flex justify-center" style="height: 120px; align-items: flex-end;">
                            <div class="w-full max-w-[48px] bg-gradient-to-t from-amber-600 to-amber-400 rounded-t transition-all"
                                 style="height: {{ $maxRevenue > 0 ? max(6, ($val / $maxRevenue) * 100) : 6 }}%"
                                 title="₦{{ number_format($val, 0) }}"></div>
                        </div>
                        <span class="text-[11px] uppercase tracking-wide text-gray-500">{{ $months[$i] }}</span>
                        <span class="text-[10px] text-gray-400">₦{{ number_format($val/1000, 0) }}k</span>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="card p-6">
            <h3 class="font-semibold text-gray-900">Upcoming Check-ins</h3>
            <p class="text-xs text-gray-400 mb-4">Next 7 days</p>
            @if($upcomingCheckIns->count())
                <ul class="space-y-3">
                    @foreach($upcomingCheckIns as $b)
                        <li class="flex items-center justify-between border border-gray-100 px-3 py-3">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $b->user?->name ?? $b->guest_name ?? 'Guest' }}</p>
                                <p class="text-xs text-gray-500">{{ $b->room?->name ?? '—' }} · {{ $b->check_in->format('M d') }} → {{ $b->check_out->format('M d') }}</p>
                            </div>
                            <span class="text-xs font-mono text-gray-600">{{ $b->payment_reference }}</span>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-sm text-gray-400 py-8 text-center">No upcoming check-ins.</p>
            @endif
            <div class="mt-4 flex gap-2">
                <a href="{{ route('admin.bookings.index', ['status' => 'confirmed']) }}" class="text-xs text-amber-600 hover:underline">All confirmed →</a>
                <span class="text-gray-300">·</span>
                <a href="{{ route('admin.contacts.index') }}" class="text-xs text-gray-500 hover:underline">{{ $unreadContacts }} unread messages</a>
            </div>
        </div>
    </div>

    <div class="mt-8 card">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900">Recent Bookings</h2>
            <a href="{{ route('admin.bookings.index') }}" class="text-sm text-amber-600 hover:text-amber-700 font-medium">View All</a>
        </div>
        @if($recentBookings->count())
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Reference</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Guest</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Room</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dates</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($recentBookings as $booking)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm font-mono text-gray-900">{{ $booking->payment_reference }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $booking->user?->name ?? $booking->guest_name ?? 'Guest' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $booking->room?->name ?? '—' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $booking->check_in->format('M d') }} - {{ $booking->check_out->format('M d') }}</td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">&#8358;{{ number_format($booking->total_amount, 2) }}</td>
                                <td class="px-6 py-4"><span class="{{ $booking->getStatusBadgeClass() }}">{{ str_replace('_', ' ', ucfirst($booking->status)) }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-6 text-center text-gray-500">No bookings yet.</div>
        @endif
    </div>
</x-layouts.admin>
