<x-layouts.admin>
    @section('title', 'Gallery')
    @section('header', 'Gallery')

    <div class="flex items-center justify-between mb-6">
        <p class="text-sm text-gray-600">{{ $gallery->total() }} images</p>
        <a href="{{ route('admin.gallery.create') }}" class="btn-primary">
            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
            Add Image
        </a>
    </div>

    <div class="card">
        @if($gallery->count())
            <div class="p-6 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                @foreach($gallery as $item)
                    <div class="group relative">
                        <img src="{{ image_url($item->image_path) }}" alt="{{ $item->title }}" class="w-full h-32 object-cover rounded-lg">
                        <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition rounded-lg flex items-end p-2">
                            <div class="w-full">
                                <p class="text-white text-xs font-medium truncate">{{ $item->title }}</p>
                                <div class="flex items-center gap-2 mt-1">
                                    <a href="{{ route('admin.gallery.edit', $item) }}" class="text-xs text-amber-400 hover:text-amber-300">Edit</a>
                                    <form method="POST" action="{{ route('admin.gallery.destroy', $item) }}" onsubmit="return confirm('Delete?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-xs text-red-400 hover:text-red-300">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="p-4">{{ $gallery->links() }}</div>
        @else
            <div class="p-12 text-center text-gray-500"><p>No gallery images yet.</p></div>
        @endif
    </div>
</x-layouts.admin>
