<x-layouts.admin>
    @section('title', 'Edit Gallery Image')
    @section('header', 'Edit Gallery Image')

    <div class="max-w-2xl">
        <form method="POST" action="{{ route('admin.gallery.update', $gallery) }}" enctype="multipart/form-data" class="card p-6 space-y-6">
            @csrf @method('PUT')
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                <input type="text" id="title" name="title" value="{{ old('title', $gallery->title) }}" required class="input-field mt-1">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Current Image</label>
                <img src="{{ image_url($gallery->image_path) }}" alt="" class="w-48 h-32 object-cover rounded-lg mb-2">
                <label for="image" class="block text-sm font-medium text-gray-700">Replace Image</label>
                <input type="file" id="image" name="image" accept="image/*" class="input-field mt-1">
            </div>
            <div>
                <label for="caption" class="block text-sm font-medium text-gray-700">Caption</label>
                <input type="text" id="caption" name="caption" value="{{ old('caption', $gallery->caption) }}" class="input-field mt-1">
            </div>
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label for="sort_order" class="block text-sm font-medium text-gray-700">Sort Order</label>
                    <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $gallery->sort_order) }}" min="0" class="input-field mt-1">
                </div>
                <div class="flex items-end">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $gallery->is_active) ? 'checked' : '' }} class="rounded border-gray-300 text-amber-600 focus:ring-amber-500">
                        <span class="text-sm font-medium text-gray-700">Active</span>
                    </label>
                </div>
            </div>
            <div class="flex items-center gap-3 pt-4">
                <button type="submit" class="btn-primary">Update</button>
                <a href="{{ route('admin.gallery.index') }}" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</x-layouts.admin>
