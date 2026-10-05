<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;

class AdminGalleryController extends Controller
{
    public function __construct(protected CloudinaryService $images) {}

    public function index()
    {
        $gallery = Gallery::orderBy('sort_order')->latest()->paginate(24);

        return view('admin.gallery.index', compact('gallery'));
    }

    public function create()
    {
        return view('admin.gallery.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpg,jpeg,png,gif,webp|max:10240',
            'caption' => 'nullable|string|max:255',
            'sort_order' => 'integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['image_path'] = $this->images->upload($request->file('image'), 'gallery');
        $validated['is_active'] = $request->boolean('is_active');

        unset($validated['image']);

        $item = Gallery::create($validated);
        \App\Support\ActivityLogger::log('admin.gallery_created', $item, [], "Gallery item '{$item->title}' added");

        return redirect()->route('admin.gallery.index')
            ->with('success', 'Gallery image added successfully.');
    }

    public function edit(Gallery $gallery)
    {
        return view('admin.gallery.edit', compact('gallery'));
    }

    public function update(Request $request, Gallery $gallery)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:10240',
            'caption' => 'nullable|string|max:255',
            'sort_order' => 'integer|min:0',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            $this->images->delete($gallery->image_path);
            $validated['image_path'] = $this->images->upload($request->file('image'), 'gallery');
        }

        $validated['is_active'] = $request->boolean('is_active');

        unset($validated['image']);

        $gallery->update($validated);
        \App\Support\ActivityLogger::log('admin.gallery_updated', $gallery, [], "Gallery item '{$gallery->title}' updated");

        return redirect()->route('admin.gallery.index')
            ->with('success', 'Gallery image updated successfully.');
    }

    public function destroy(Gallery $gallery)
    {
        $this->images->delete($gallery->image_path);
        \App\Support\ActivityLogger::log('admin.gallery_deleted', $gallery, [], "Gallery item '{$gallery->title}' deleted");
        $gallery->delete();

        return redirect()->route('admin.gallery.index')
            ->with('success', 'Gallery image deleted successfully.');
    }
}
