<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        $query = Room::available()->with('category');

        if ($request->filled('category')) {
            $query->inCategory($request->category);
        }

        if ($request->filled('check_in') && $request->filled('check_out')) {
            $checkIn = $request->check_in;
            $checkOut = $request->check_out;

            $query->whereDoesntHave('bookings', function ($q) use ($checkIn, $checkOut) {
                $q->whereIn('status', ['confirmed', 'pending_payment'])
                    ->where('check_in', '<', $checkOut)
                    ->where('check_out', '>', $checkIn);
            });
        }

        $rooms = $query->latest()->paginate(12);
        $categories = Category::active()->ordered()->get();

        return view('rooms.index', compact('rooms', 'categories'));
    }

    public function show(string $slug)
    {
        $room = Room::with('category')->where('slug', $slug)->firstOrFail();

        return view('rooms.show', compact('room'));
    }
}
