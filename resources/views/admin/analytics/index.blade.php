<x-layouts.admin>
    @section('title', 'Analytics')
    @section('header', 'Analytics')

    <details class="card px-6 py-4 mb-6 text-sm text-gray-600">
        <summary class="cursor-pointer font-semibold text-gray-900">How to read this page</summary>
        <dl class="mt-3 space-y-2.5">
            <div><dt class="font-medium text-gray-900 inline">Avg Daily Rate (ADR) — </dt><dd class="inline">average price per sold night. If ADR falls while rooms still fill, you are discounting too much — raise rates.</dd></div>
            <div><dt class="font-medium text-gray-900 inline">RevPAR — </dt><dd class="inline">revenue per <em>available</em> room (sold or not). The single best health number: if it climbs, pricing and occupancy are working together.</dd></div>
            <div><dt class="font-medium text-gray-900 inline">This Month vs Last — </dt><dd class="inline">confirmed-revenue growth. Negative two months in a row means act: promotion, pricing, or marketing.</dd></div>
            <div><dt class="font-medium text-gray-900 inline">Cancellation Rate — </dt><dd class="inline">share of decided bookings cancelled. Above 20% (shown red) usually means guests book speculatively — consider part-payment or stricter terms.</dd></div>
            <div><dt class="font-medium text-gray-900 inline">Lead time — </dt><dd class="inline">average days between booking and arrival. Run promotions about this far ahead of the dates you want to fill.</dd></div>
            <div><dt class="font-medium text-gray-900 inline">Repeat guests — </dt><dd class="inline">guests who booked more than once. Growing number = loyalty is working; consider a return-guest offer.</dd></div>
            <div><dt class="font-medium text-gray-900 inline">Occupancy Forecast — </dt><dd class="inline">confirmed rooms per day for the next 14 days. Red days (80%+) may need extra staff; green days are promo opportunities.</dd></div>
            <div><dt class="font-medium text-gray-900 inline">Top Rooms — </dt><dd class="inline">highest-earning rooms in 90 days. Feature winners on the homepage; investigate rooms that never appear.</dd></div>
            <div><dt class="font-medium text-gray-900 inline">Demand Pace — </dt><dd class="inline">when reservations were <em>made</em> each week. Bookings up + revenue flat = discounting; both down = marketing problem.</dd></div>
            <div><dt class="font-medium text-gray-900 inline">Arrival Weekdays — </dt><dd class="inline">which days guests check in. Schedule housekeeping and front-desk shifts around the peaks.</dd></div>
            <div><dt class="font-medium text-gray-900 inline">Where Guests Come From — </dt><dd class="inline">bookings and revenue by how guests found you (collected at checkout since tracking began). Spend marketing money where the revenue bar is tallest.</dd></div>
        </dl>
    </details>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="card p-6">
            <p class="text-sm font-medium text-gray-500">Avg Daily Rate · 30d</p>
            <p class="mt-2 text-3xl font-bold text-gray-900">&#8358;{{ number_format($adr30, 0) }}</p>
            <p class="mt-1 text-xs text-gray-400">Per sold night</p>
        </div>
        <div class="card p-6">
            <p class="text-sm font-medium text-gray-500">RevPAR · 30d</p>
            <p class="mt-2 text-3xl font-bold text-gray-900">&#8358;{{ number_format($revpar30, 0) }}</p>
            <p class="mt-1 text-xs text-gray-400">Per available room</p>
        </div>
        <div class="card p-6">
            <p class="text-sm font-medium text-gray-500">This Month vs Last</p>
            <p class="mt-2 text-3xl font-bold {{ $momChange >= 0 ? 'text-emerald-600' : 'text-red-600' }}">{{ $momChange >= 0 ? '+' : '' }}{{ $momChange }}%</p>
            <p class="mt-1 text-xs text-gray-400">&#8358;{{ number_format($thisMonthRevenue, 0) }} vs &#8358;{{ number_format($lastMonthRevenue, 0) }}</p>
        </div>
        <div class="card p-6">
            <p class="text-sm font-medium text-gray-500">Cancellation Rate</p>
            <p class="mt-2 text-3xl font-bold {{ $cancelRate > 20 ? 'text-red-600' : 'text-gray-900' }}">{{ $cancelRate }}%</p>
            <p class="mt-1 text-xs text-gray-400">{{ $avgLeadDays }}d avg lead time · {{ $repeatGuests }} repeat guest(s)</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
        <div class="card p-6 lg:col-span-2">
            <div class="flex items-center justify-between mb-1">
                <h3 class="font-semibold text-gray-900">Occupancy Forecast — Next 14 Days</h3>
                @if($peakDay && $peakDay['booked'] > 0)
                    <span class="text-xs text-amber-700 bg-amber-50 border border-amber-200 rounded px-2 py-1">Peak: {{ $peakDay['label'] }} ({{ $peakDay['booked'] }} room(s))</span>
                @endif
            </div>
            <p class="text-xs text-gray-400 mb-5">Confirmed bookings per day — plan staffing &amp; housekeeping ahead.</p>
            <div class="flex items-end gap-1.5 h-36">
                @foreach($occupancyForecast as $day)
                    <div class="flex-1 flex flex-col items-center gap-1.5 min-w-0">
                        <div class="w-full flex justify-center" style="height: 96px; align-items: flex-end;">
                            <div class="w-full max-w-[28px] rounded-t {{ $day['rate'] >= 80 ? 'bg-red-400' : ($day['rate'] >= 50 ? 'bg-amber-400' : 'bg-emerald-300') }}"
                                 style="height: {{ max(5, $day['rate']) }}%"
                                 title="{{ $day['label'] }}: {{ $day['booked'] }} room(s)"></div>
                        </div>
                        <span class="text-[10px] text-gray-500 whitespace-nowrap">{{ $day['label'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>

            <div class="card p-6">
                <h3 class="font-semibold text-gray-900">Top Rooms · 90d Revenue</h3>
            <p class="text-xs text-gray-400 mb-4">Promote winners, review laggards.</p>
            @if($topRooms->count())
                <ul class="space-y-3">
                    @foreach($topRooms as $i => $room)
                        <li class="flex items-center justify-between gap-3">
                            <div class="flex items-center gap-3 min-w-0">
                                <span class="text-xs font-bold text-gray-400 w-4">{{ $i + 1 }}</span>
                                <div class="min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $room['name'] }}</p>
                                    <p class="text-xs text-gray-400">{{ $room['bookings'] }} booking(s)</p>
                                </div>
                            </div>
                            <p class="text-sm font-bold text-gray-900 whitespace-nowrap">&#8358;{{ number_format($room['revenue'], 0) }}</p>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-sm text-gray-400 py-6 text-center">No confirmed revenue in the last 90 days.</p>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
        <div class="card p-6 lg:col-span-2">
            <h3 class="font-semibold text-gray-900">Demand Pace — Bookings per Week · 12 wks</h3>
            <p class="text-xs text-gray-400 mb-5">When reservations are made — volume signal beside the revenue chart.</p>
            <div class="flex items-end gap-1.5 h-36">
                @foreach($weeklyTrend as $week)
                    <div class="flex-1 flex flex-col items-center gap-1.5 min-w-0">
                        <div class="w-full flex justify-center" style="height: 96px; align-items: flex-end;">
                            <div class="w-full max-w-[28px] rounded-t bg-sky-400"
                                 style="height: {{ max(5, ($week['count'] / $weeklyMax) * 100) }}%"
                                 title="Week of {{ $week['label'] }}: {{ $week['count'] }} booking(s)"></div>
                        </div>
                        <span class="text-[10px] text-gray-500 whitespace-nowrap">{{ $week['label'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="card p-6">
            <h3 class="font-semibold text-gray-900">Arrival Weekdays · 90d</h3>
            <p class="text-xs text-gray-400 mb-4">Which days guests check in — roster shifts accordingly.</p>
            <div class="flex items-end gap-1.5 h-36">
                @foreach($weekdayCounts as $day => $count)
                    <div class="flex-1 flex flex-col items-center gap-1.5 min-w-0">
                        <div class="w-full flex justify-center" style="height: 96px; align-items: flex-end;">
                            <div class="w-full max-w-[28px] rounded-t bg-indigo-400"
                                 style="height: {{ max(5, ($count / $weekdayMax) * 100) }}%"
                                 title="{{ $day }}: {{ $count }} arrival(s)"></div>
                        </div>
                        <span class="text-[10px] text-gray-500">{{ $day }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="card p-6 mt-6">
        <div class="flex items-center justify-between mb-1">
            <h3 class="font-semibold text-gray-900">Where Guests Come From</h3>
            <span class="text-xs text-gray-400">Tracking started today — history builds from here</span>
        </div>
        <p class="text-xs text-gray-400 mb-4">Confirmed bookings by source — spend marketing where the revenue is.</p>
        @if($sourceStats->count())
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Source</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bookings</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Share</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Revenue</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($sourceStats as $key => $row)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ \App\Models\Booking::SOURCES[$key] ?? ucfirst($key) }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $row['bookings'] }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    <div class="flex items-center gap-2">
                                        <div class="h-2 w-32 bg-gray-100 rounded-full overflow-hidden">
                                            <div class="h-full bg-emerald-500" style="width: {{ round(($row['bookings'] / $sourceTotal) * 100) }}%"></div>
                                        </div>
                                        <span>{{ round(($row['bookings'] / $sourceTotal) * 100) }}%</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm font-bold text-gray-900 text-right">&#8358;{{ number_format($row['revenue'], 0) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-sm text-gray-400 py-6 text-center">No confirmed bookings yet.</p>
        @endif
    </div>
</x-layouts.admin>
