<x-layouts.admin>
    @section('title', 'Edit ' . $room->name)
    @section('header', 'Edit Room')

    <div class="max-w-2xl">
        <form method="POST" action="{{ route('admin.rooms.update', $room) }}" enctype="multipart/form-data" class="card p-6 space-y-6">
            @csrf @method('PUT')

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700">Category</label>
                    <select id="category_id" name="category_id" required class="input-field mt-1">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $room->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Room Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $room->name) }}" required class="input-field mt-1">
                </div>
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea id="description" name="description" rows="3" class="input-field mt-1">{{ old('description', $room->description) }}</textarea>
            </div>

            <div class="grid grid-cols-3 gap-6">
                <div>
                    <label for="price_per_night" class="block text-sm font-medium text-gray-700">Price/Night (&#8358;)</label>
                    <input type="number" id="price_per_night" name="price_per_night" value="{{ old('price_per_night', $room->price_per_night) }}" step="0.01" min="0" required class="input-field mt-1">
                </div>
                <div>
                    <label for="capacity" class="block text-sm font-medium text-gray-700">Capacity</label>
                    <input type="number" id="capacity" name="capacity" value="{{ old('capacity', $room->capacity) }}" min="1" required class="input-field mt-1">
                </div>
                <div>
                    <label for="bed_type" class="block text-sm font-medium text-gray-700">Bed Type</label>
                    <input type="text" id="bed_type" name="bed_type" value="{{ old('bed_type', $room->bed_type) }}" class="input-field mt-1">
                </div>
            </div>

            <div>
                <label for="amenities" class="block text-sm font-medium text-gray-700">Amenities (comma separated)</label>
                <input type="text" id="amenities" name="amenities" value="{{ old('amenities', is_array($room->amenities) ? implode(', ', $room->amenities) : '') }}" class="input-field mt-1">
            </div>

            @if($room->images)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Current Images <span class="font-normal text-gray-400">(tick to remove)</span></label>
                    <div class="flex gap-3 flex-wrap">
                        @foreach($room->images as $img)
                            <label class="relative cursor-pointer group" title="Tick to remove">
                                <input type="checkbox" name="remove_images[]" value="{{ $img }}" class="peer absolute top-1 left-1 z-10 rounded border-gray-300">
                                <img src="{{ image_url($img) }}" alt="" class="w-16 h-16 rounded-lg object-cover peer-checked:opacity-40">
                                <span class="absolute inset-0 rounded-lg ring-2 ring-red-500 opacity-0 peer-checked:opacity-100 pointer-events-none"></span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Add More Images</label>
                <input type="file" name="images[]" accept="image/*" multiple class="input-field">
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select id="status" name="status" class="input-field mt-1">
                        <option value="available" {{ old('status', $room->status) === 'available' ? 'selected' : '' }}>Available</option>
                        <option value="maintenance" {{ old('status', $room->status) === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                        <option value="booked" {{ old('status', $room->status) === 'booked' ? 'selected' : '' }}>Booked</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="is_available" value="1" {{ old('is_available', $room->is_available) ? 'checked' : '' }} class="rounded border-gray-300 text-amber-600 focus:ring-amber-500">
                        <span class="text-sm font-medium text-gray-700">Available for Booking</span>
                    </label>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-4">
                <button type="submit" class="btn-primary">Update Room</button>
                <a href="{{ route('admin.rooms.index') }}" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</x-layouts.admin>
