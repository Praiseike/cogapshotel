<x-layouts.admin>
    @section('title', 'Edit ' . $service->name)
    @section('header', 'Edit Service')

    <div class="max-w-2xl">
        <form method="POST" action="{{ route('admin.services.update', $service) }}" enctype="multipart/form-data" class="card p-6 space-y-6">
            @csrf @method('PUT')
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $service->name) }}" required class="input-field mt-1">
                </div>
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                    <input type="text" id="category" name="category" value="{{ old('category', $service->category) }}" class="input-field mt-1">
                </div>
            </div>
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea id="description" name="description" rows="3" class="input-field mt-1">{{ old('description', $service->description) }}</textarea>
            </div>
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700">Price (&#8358;)</label>
                    <input type="number" id="price" name="price" value="{{ old('price', $service->price) }}" step="0.01" min="0" class="input-field mt-1">
                </div>
                <div>
                    <label for="image" class="block text-sm font-medium text-gray-700">Image</label>
                    @if($service->image)
                        <img src="{{ image_url($service->image) }}" alt="" class="w-16 h-16 rounded-lg object-cover mb-2">
                    @endif
                    <input type="file" id="image" name="image" accept="image/*" class="input-field mt-1">
                </div>
            </div>
            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $service->is_active) ? 'checked' : '' }} class="rounded border-gray-300 text-amber-600 focus:ring-amber-500">
                <span class="text-sm font-medium text-gray-700">Active</span>
            </div>
            <div class="flex items-center gap-3 pt-4">
                <button type="submit" class="btn-primary">Update Service</button>
                <a href="{{ route('admin.services.index') }}" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</x-layouts.admin>
