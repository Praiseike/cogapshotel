<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Category;
use App\Models\Contact;
use App\Models\Room;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalBookings = Booking::count();
        $totalRooms = Room::count();
        $totalRevenue = Booking::where('status', 'confirmed')->sum('total_amount');
        $totalCategories = Category::count();
        $pendingBookings = Booking::where('status', 'pending_payment')->count();
        $unreadContacts = Contact::unread()->count();
        $confirmedBookings = Booking::where('status', 'confirmed')->count();
        $cancelledBookings = Booking::where('status', 'cancelled')->count();

        $recentBookings = Booking::with(['user', 'room'])
            ->latest()
            ->limit(10)
            ->get();

        // Revenue last 6 months — DB-agnostic grouping in PHP (works for sqlite/mysql)
        $recentConfirmed = Booking::where('status', 'confirmed')
            ->where('paid_at', '>=', now()->subMonths(6)->startOfMonth())
            ->get(['paid_at', 'total_amount']);

        $revenueByMonth = $recentConfirmed
            ->groupBy(fn ($b) => $b->paid_at ? $b->paid_at->format('Y-m') : 'unknown')
            ->map(fn ($group) => $group->sum(fn ($b) => (float) $b->total_amount));

        // Fill 6 months even if empty
        $months = [];
        $revenueChart = [];
        for ($i = 5; $i >= 0; $i--) {
            $key = now()->subMonths($i)->format('Y-m');
            $label = now()->subMonths($i)->format('M');
            $months[] = $label;
            $revenueChart[] = (float) ($revenueByMonth[$key] ?? 0);
        }

        $maxRevenue = max($revenueChart) ?: 1;

        // Occupancy today — distinct rooms, not bookings
        $occupiedToday = Booking::where('status', 'confirmed')
            ->where('check_in', '<=', now()->toDateString())
            ->where('check_out', '>', now()->toDateString())
            ->distinct()
            ->count('room_id');
        $occupancyRate = $totalRooms > 0 ? round(($occupiedToday / $totalRooms) * 100) : 0;

        // Upcoming check-ins (next 7 days)
        $upcomingCheckIns = Booking::with(['user', 'room'])
            ->where('status', 'confirmed')
            ->whereBetween('check_in', [now()->toDateString(), now()->addDays(7)->toDateString()])
            ->orderBy('check_in')
            ->limit(5)
            ->get();

        // Pending expiries (older than 20 min)
        $expiringSoon = Booking::where('status', 'pending_payment')
            ->where('created_at', '<', now()->subMinutes(20))
            ->count();

        return view('admin.dashboard', compact(
            'totalBookings',
            'totalRooms',
            'totalRevenue',
            'totalCategories',
            'pendingBookings',
            'unreadContacts',
            'recentBookings',
            'confirmedBookings',
            'cancelledBookings',
            'months',
            'revenueChart',
            'maxRevenue',
            'occupiedToday',
            'occupancyRate',
            'upcomingCheckIns',
            'expiringSoon',
        ));
    }
}
