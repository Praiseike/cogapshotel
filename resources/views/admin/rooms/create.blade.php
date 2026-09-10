<x-layouts.admin>
    @section('title', 'Add Room')
    @section('header', 'Add Room')

    <div class="max-w-2xl">
        <form method="POST" action="{{ route('admin.rooms.store') }}" enctype="multipart/form-data" class="card p-6 space-y-6">
            @csrf

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700">Category</label>
                    <select id="category_id" name="category_id" required class="input-field mt-1">
                        <option value="">Select category</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Room Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required class="input-field mt-1">
                    @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea id="description" name="description" rows="3" class="input-field mt-1">{{ old('description') }}</textarea>
            </div>

            <div class="grid grid-cols-3 gap-6">
                <div>
                    <label for="price_per_night" class="block text-sm font-medium text-gray-700">Price/Night (&#8358;)</label>
                    <input type="number" id="price_per_night" name="price_per_night" value="{{ old('price_per_night') }}" step="0.01" min="0" required class="input-field mt-1">
                </div>
                <div>
                    <label for="capacity" class="block text-sm font-medium text-gray-700">Capacity</label>
                    <input type="number" id="capacity" name="capacity" value="{{ old('capacity', 1) }}" min="1" required class="input-field mt-1">
                </div>
                <div>
                    <label for="bed_type" class="block text-sm font-medium text-gray-700">Bed Type</label>
                    <input type="text" id="bed_type" name="bed_type" value="{{ old('bed_type') }}" placeholder="e.g. King, Queen" class="input-field mt-1">
                </div>
            </div>

            <div>
                <label for="amenities" class="block text-sm font-medium text-gray-700">Amenities (comma separated)</label>
                <input type="text" id="amenities" name="amenities" value="{{ old('amenities') }}" placeholder="WiFi, AC, TV, Minibar" class="input-field mt-1">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Images</label>
                <input type="file" name="images[]" accept="image/*" multiple class="input-field">
                <p class="mt-1 text-xs text-gray-500">Select multiple images for this room</p>
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select id="status" name="status" class="input-field mt-1">
                        <option value="available" {{ old('status') === 'available' ? 'selected' : '' }}>Available</option>
                        <option value="maintenance" {{ old('status') === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="is_available" value="1" {{ old('is_available', true) ? 'checked' : '' }} class="rounded border-gray-300 text-amber-600 focus:ring-amber-500">
                        <span class="text-sm font-medium text-gray-700">Available for Booking</span>
                    </label>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-4">
                <button type="submit" class="btn-primary">Create Room</button>
                <a href="{{ route('admin.rooms.index') }}" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</x-layouts.admin>
