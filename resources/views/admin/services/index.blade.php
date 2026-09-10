<x-layouts.admin>
    @section('title', 'Services')
    @section('header', 'Services')

    <div class="flex items-center justify-between mb-6">
        <p class="text-sm text-gray-600">{{ $services->total() }} services</p>
        <a href="{{ route('admin.services.create') }}" class="btn-primary">
            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
            Add Service
        </a>
    </div>

    <div class="card">
        @if($services->count())
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($services as $service)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        @if($service->image)
                                            <img src="{{ image_url($service->image) }}" alt="" class="w-10 h-10 rounded-lg object-cover">
                                        @else
                                            <div class="w-10 h-10 rounded-lg bg-gray-200"></div>
                                        @endif
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $service->name }}</p>
                                            <p class="text-sm text-gray-500">{{ Str::limit($service->description, 40) }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $service->category ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $service->price ? '&#8358;' . number_format($service->price, 2) : 'Free' }}</td>
                                <td class="px-6 py-4"><span class="{{ $service->is_active ? 'badge-success' : 'badge-gray' }}">{{ $service->is_active ? 'Active' : 'Inactive' }}</span></td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.services.edit', $service) }}" class="text-sm text-amber-600 hover:text-amber-700">Edit</a>
                                        <form method="POST" action="{{ route('admin.services.destroy', $service) }}" onsubmit="return confirm('Delete?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-sm text-red-600 hover:text-red-700">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-4">{{ $services->links() }}</div>
        @else
            <div class="p-12 text-center text-gray-500"><p>No services yet.</p></div>
        @endif
    </div>
</x-layouts.admin>
