<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Room;
use Carbon\Carbon;

class AdminAnalyticsController extends Controller
{
    public function index()
    {
        $totalRooms = Room::count();
        $cancelledBookings = Booking::where('status', 'cancelled')->count();

        // 14-day occupancy forecast (confirmed bookings per day ahead)
        $forecastStart = now()->toDateString();
        $forecastEnd = now()->addDays(13)->toDateString();
        $upcoming = Booking::where('status', 'confirmed')
            ->where('check_out', '>', $forecastStart)
            ->where('check_in', '<=', $forecastEnd)
            ->get(['check_in', 'check_out']);

        $occupancyForecast = [];
        for ($i = 0; $i < 14; $i++) {
            $day = now()->addDays($i)->toDateString();
            $booked = $upcoming->filter(fn ($b) => $b->check_in <= $day && $b->check_out > $day)->count();
            $occupancyForecast[] = [
                'label' => now()->addDays($i)->format('D j'),
                'booked' => $booked,
                'rate' => $totalRooms > 0 ? round(($booked / $totalRooms) * 100) : 0,
            ];
        }
        $peakDay = collect($occupancyForecast)->sortByDesc('booked')->first();

        // Pricing power: ADR + RevPAR over the last 30 days (confirmed revenue)
        $monthBookings = Booking::where('status', 'confirmed')
            ->where('paid_at', '>=', now()->subDays(30))
            ->get(['total_amount', 'check_in', 'check_out']);

        $monthNights = $monthBookings->sum(fn ($b) => max(0, Carbon::parse($b->check_in)->diffInDays($b->check_out)));
        $monthRevenue = (float) $monthBookings->sum(fn ($b) => (float) $b->total_amount);
        $adr30 = $monthNights > 0 ? round($monthRevenue / $monthNights) : 0;
        $revpar30 = $totalRooms > 0 ? round($monthRevenue / ($totalRooms * 30)) : 0;

        // This month vs last month (confirmed revenue by payment date)
        $thisMonthRevenue = (float) Booking::where('status', 'confirmed')
            ->where('paid_at', '>=', now()->startOfMonth())
            ->sum('total_amount');
        $lastMonthRevenue = (float) Booking::where('status', 'confirmed')
            ->where('paid_at', '>=', now()->subMonth()->startOfMonth())
            ->where('paid_at', '<', now()->startOfMonth())
            ->sum('total_amount');
        $momChange = $lastMonthRevenue > 0
            ? round((($thisMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100)
            : ($thisMonthRevenue > 0 ? 100 : 0);

        // Cancellation rate (decided bookings only — pending excluded)
        $decided = Booking::whereIn('status', ['confirmed', 'completed', 'cancelled'])->count();
        $cancelRate = $decided > 0 ? round(($cancelledBookings / $decided) * 100) : 0;

        // Average booking lead time (created → check-in, confirmed bookings)
        $leadSample = Booking::where('status', 'confirmed')->get(['created_at', 'check_in']);
        $avgLeadDays = $leadSample->count() > 0
            ? (int) round($leadSample->avg(fn ($b) => $b->created_at->diffInDays($b->check_in)))
            : 0;

        // Top rooms by revenue, last 90 days (with booking counts)
        $topRooms = Booking::with('room')
            ->where('status', 'confirmed')
            ->where('paid_at', '>=', now()->subDays(90))
            ->get(['room_id', 'total_amount'])
            ->groupBy('room_id')
            ->map(function ($group) {
                $room = $group->first()->room;

                return [
                    'name' => $room?->name ?? '—',
                    'bookings' => $group->count(),
                    'revenue' => (float) $group->sum(fn ($b) => (float) $b->total_amount),
                ];
            })
            ->sortByDesc('revenue')
            ->take(5)
            ->values();

        // Repeat guests (same account or same email booked more than once)
        $repeatGuests = Booking::whereIn('status', ['confirmed', 'completed'])
            ->get(['user_id', 'guest_email'])
            ->groupBy(fn ($b) => $b->user_id ? 'u:'.$b->user_id : 'e:'.strtolower((string) $b->guest_email))
            ->filter(fn ($group, $key) => $key !== 'e:' && $group->count() > 1)
            ->count();

        // Check-in weekday pattern, last 90 days (which days guests arrive)
        $weekdays = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
        $weekdayCounts = array_fill_keys($weekdays, 0);
        Booking::where('status', 'confirmed')
            ->where('check_in', '>=', now()->subDays(90)->toDateString())
            ->get(['check_in'])
            ->each(function ($b) use (&$weekdayCounts) {
                $weekdayCounts[Carbon::parse($b->check_in)->format('D')]++;
            });
        $weekdayMax = max($weekdayCounts) ?: 1;

        // Demand pace: confirmed bookings per week, last 12 weeks
        $weeklyTrend = [];
        for ($i = 11; $i >= 0; $i--) {
            $start = now()->subWeeks($i)->startOfWeek();
            $end = (clone $start)->endOfWeek();
            $weeklyTrend[] = [
                'label' => $start->format('M j'),
                'count' => Booking::where('status', 'confirmed')
                    ->whereBetween('created_at', [$start, $end])
                    ->count(),
            ];
        }
        $weeklyMax = max(array_column($weeklyTrend, 'count')) ?: 1;

        // Booking source breakdown (confirmed, all time + revenue share)
        $sourceStats = Booking::where('status', 'confirmed')
            ->get(['source', 'total_amount'])
            ->groupBy(fn ($b) => $b->source ?: 'unknown')
            ->map(fn ($group) => [
                'bookings' => $group->count(),
                'revenue' => (float) $group->sum(fn ($b) => (float) $b->total_amount),
            ])
            ->sortByDesc('bookings');
        $sourceTotal = $sourceStats->sum('bookings') ?: 1;

        return view('admin.analytics.index', compact(
            'occupancyForecast',
            'peakDay',
            'adr30',
            'revpar30',
            'thisMonthRevenue',
            'lastMonthRevenue',
            'momChange',
            'cancelRate',
            'avgLeadDays',
            'topRooms',
            'repeatGuests',
            'weekdayCounts',
            'weekdayMax',
            'weeklyTrend',
            'weeklyMax',
            'sourceStats',
            'sourceTotal',
        ));
    }
}
