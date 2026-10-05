<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Room;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminRoomController extends Controller
{
    public function __construct(protected CloudinaryService $images) {}

    public function index(Request $request)
    {
        // Live occupancy: confirmed bookings covering today. The manual
        // `status` column alone can't show this — bookings never flip it.
        $today = now()->toDateString();
        $query = Room::with('category')->withCount([
            'bookings as occupied_now' => fn ($q) => $q
                ->where('status', 'confirmed')
                ->where('check_in', '<=', $today)
                ->where('check_out', '>', $today),
        ]);

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
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:5120',
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
                $paths[] = $this->images->upload($image, 'rooms');
            }
            $validated['images'] = $paths;
        }

        $room = Room::create($validated);

        \App\Support\ActivityLogger::log('admin.room_created', $room, [], "Room '{$room->name}' created");

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
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:5120',
            'remove_images' => 'nullable|array',
            'remove_images.*' => 'string',
            'is_available' => 'boolean',
            'status' => 'in:available,maintenance,booked',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_available'] = $request->boolean('is_available');

        if (! empty($validated['amenities'])) {
            $validated['amenities'] = array_map('trim', explode(',', $validated['amenities']));
        }

        unset($validated['remove_images']);

        // Remove ticked images first (files + Cloudinary assets cleaned up)…
        $paths = $room->images ?? [];
        foreach ((array) $request->input('remove_images', []) as $doomed) {
            if (in_array($doomed, $paths, true)) {
                $this->images->delete($doomed);
                $paths = array_values(array_diff($paths, [$doomed]));
            }
        }

        // …then append any new uploads.
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $paths[] = $this->images->upload($image, 'rooms');
            }
        }

        $validated['images'] = $paths;

        $removed = (array) $request->input('remove_images', []);
        $room->update($validated);

        \App\Support\ActivityLogger::log(
            'admin.room_updated',
            $room,
            ['images_removed' => count($removed)],
            "Room '{$room->name}' updated"
        );

        return redirect()->route('admin.rooms.index')
            ->with('success', 'Room updated successfully.');
    }

    public function destroy(Room $room)
    {
        if ($room->bookings()->withTrashed()->count() > 0) {
            return redirect()->route('admin.rooms.index')
                ->with('error', 'This room cannot be deleted because it has booking history. Set its availability to "Unavailable" instead to stop new bookings.');
        }

        foreach ($room->images ?? [] as $path) {
            $this->images->delete($path);
        }

        \App\Support\ActivityLogger::log('admin.room_deleted', $room, [], "Room '{$room->name}' deleted");
        $room->delete();

        return redirect()->route('admin.rooms.index')
            ->with('success', 'Room deleted successfully.');
    }

    public function bulk(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:rooms,id',
            'action' => 'required|in:available,maintenance,booked',
        ]);

        // Keep the public flag in sync: only "available" rooms are bookable.
        $count = Room::whereIn('id', $validated['ids'])->update([
            'status' => $validated['action'],
            'is_available' => $validated['action'] === 'available',
        ]);

        \App\Support\ActivityLogger::log(
            'admin.room_bulk',
            null,
            ['action' => $validated['action'], 'count' => $count],
            "Bulk: {$count} room(s) → {$validated['action']}"
        );

        return back()->with('success', "{$count} room(s) marked as {$validated['action']}.");
    }
}
