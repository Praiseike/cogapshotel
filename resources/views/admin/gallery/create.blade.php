<x-layouts.admin>
    @section('title', 'Add Gallery Image')
    @section('header', 'Add Gallery Image')

    <div class="max-w-2xl">
        <form method="POST" action="{{ route('admin.gallery.store') }}" enctype="multipart/form-data" class="card p-6 space-y-6">
            @csrf
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                <input type="text" id="title" name="title" value="{{ old('title') }}" required class="input-field mt-1">
                @error('title') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="image" class="block text-sm font-medium text-gray-700">Image</label>
                <input type="file" id="image" name="image" accept="image/*" required class="input-field mt-1">
                @error('image') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="caption" class="block text-sm font-medium text-gray-700">Caption</label>
                <input type="text" id="caption" name="caption" value="{{ old('caption') }}" class="input-field mt-1">
            </div>
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label for="sort_order" class="block text-sm font-medium text-gray-700">Sort Order</label>
                    <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}" min="0" class="input-field mt-1">
                </div>
                <div class="flex items-end">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="rounded border-gray-300 text-amber-600 focus:ring-amber-500">
                        <span class="text-sm font-medium text-gray-700">Active</span>
                    </label>
                </div>
            </div>
            <div class="flex items-center gap-3 pt-4">
                <button type="submit" class="btn-primary">Upload Image</button>
                <a href="{{ route('admin.gallery.index') }}" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</x-layouts.admin>
