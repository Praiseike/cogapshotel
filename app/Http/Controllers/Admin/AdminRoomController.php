<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminRoomController extends Controller
{
    public function index(Request $request)
    {
        $query = Room::with('category');

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $rooms = $query->latest()->paginate(20);
        $categories = Category::active()->ordered()->get();

        return view('admin.rooms.index', compact('rooms', 'categories'));
    }

    public function create()
    {
        $categories = Category::active()->ordered()->get();

        return view('admin.rooms.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'price_per_night' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1',
            'bed_type' => 'nullable|string|max:100',
            'amenities' => 'nullable|string',
            'images.*' => 'nullable|image|max:5120',
            'is_available' => 'boolean',
            'status' => 'in:available,maintenance,booked',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_available'] = $request->boolean('is_available');

        if (! empty($validated['amenities'])) {
            $validated['amenities'] = array_map('trim', explode(',', $validated['amenities']));
        }

        if ($request->hasFile('images')) {
            $paths = [];
            foreach ($request->file('images') as $image) {
                $paths[] = $image->store('rooms', 'public');
            }
            $validated['images'] = $paths;
        }

        Room::create($validated);

        return redirect()->route('admin.rooms.index')
            ->with('success', 'Room created successfully.');
    }

    public function edit(Room $room)
    {
        $categories = Category::active()->ordered()->get();

        return view('admin.rooms.edit', compact('room', 'categories'));
    }

    public function update(Request $request, Room $room)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'price_per_night' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1',
            'bed_type' => 'nullable|string|max:100',
            'amenities' => 'nullable|string',
            'images.*' => 'nullable|image|max:5120',
            'is_available' => 'boolean',
            'status' => 'in:available,maintenance,booked',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_available'] = $request->boolean('is_available');

        if (! empty($validated['amenities'])) {
            $validated['amenities'] = array_map('trim', explode(',', $validated['amenities']));
        }

        if ($request->hasFile('images')) {
            $paths = $room->images ?? [];
            foreach ($request->file('images') as $image) {
                $paths[] = $image->store('rooms', 'public');
            }
            $validated['images'] = $paths;
        }

        $room->update($validated);

        return redirect()->route('admin.rooms.index')
            ->with('success', 'Room updated successfully.');
    }

    public function destroy(Room $room)
    {
        if ($room->bookings()->withTrashed()->count() > 0) {
            return redirect()->route('admin.rooms.index')
                ->with('error', 'This room cannot be deleted because it has booking history. Set its availability to "Unavailable" instead to stop new bookings.');
        }

        $room->delete();

        return redirect()->route('admin.rooms.index')
            ->with('success', 'Room deleted successfully.');
    }
}
