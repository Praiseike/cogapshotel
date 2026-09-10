<x-layouts.admin>
    @section('title', 'Settings')
    @section('header', 'Settings')

    <div class="max-w-2xl">
        <form method="POST" action="{{ route('admin.settings.update') }}" class="card p-6 space-y-6">
            @csrf @method('PUT')

            <div>
                <label for="hotel_name" class="block text-sm font-medium text-gray-700">Hotel Name</label>
                <input type="text" id="hotel_name" name="hotel_name" value="{{ $settings['hotel_name'] ?? '' }}" class="input-field mt-1">
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label for="hotel_email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="hotel_email" name="hotel_email" value="{{ $settings['hotel_email'] ?? '' }}" class="input-field mt-1">
                </div>
                <div>
                    <label for="hotel_phone" class="block text-sm font-medium text-gray-700">Phone</label>
                    <input type="text" id="hotel_phone" name="hotel_phone" value="{{ $settings['hotel_phone'] ?? '' }}" class="input-field mt-1">
                </div>
            </div>

            <div>
                <label for="hotel_address" class="block text-sm font-medium text-gray-700">Address</label>
                <input type="text" id="hotel_address" name="hotel_address" value="{{ $settings['hotel_address'] ?? '' }}" class="input-field mt-1">
            </div>

            <div>
                <label for="hotel_description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea id="hotel_description" name="hotel_description" rows="3" class="input-field mt-1">{{ $settings['hotel_description'] ?? '' }}</textarea>
            </div>

            <div class="flex items-center gap-3 pt-4">
                <button type="submit" class="btn-primary">Save Settings</button>
            </div>
        </form>
    </div>
</x-layouts.admin>
