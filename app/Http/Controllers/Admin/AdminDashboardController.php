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

        $recentBookings = Booking::with(['user', 'room'])
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalBookings',
            'totalRooms',
            'totalRevenue',
            'totalCategories',
            'pendingBookings',
            'unreadContacts',
            'recentBookings',
        ));
    }
}
