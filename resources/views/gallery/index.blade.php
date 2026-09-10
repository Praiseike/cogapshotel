<x-layouts.app>
    @section('title', 'Gallery')
    @section('meta_description', 'Take a visual tour of our beautiful hotel')

    <div class="bg-gray-900 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl font-display font-bold text-white">Gallery</h1>
            <p class="mt-4 text-lg text-gray-300">Take a visual tour of our hotel</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        @if($gallery->count())
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4" x-data="{ lightbox: null }">
                @foreach($gallery as $item)
                    <figure class="relative cursor-pointer group" @click="lightbox = {{ $loop->iteration }}">
                        <img src="{{ image_url($item->image_path) }}" alt="{{ $item->title }}" class="w-full h-64 object-cover rounded-xl group-hover:opacity-90 transition">
                        @if($item->caption)
                            <figcaption class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/70 to-transparent p-4 rounded-b-xl">
                                <p class="text-white text-sm">{{ $item->caption }}</p>
                            </figcaption>
                        @endif
                    </figure>
                @endforeach

                <div x-show="lightbox !== null" @keydown.escape.window="lightbox = null"
                     class="fixed inset-0 z-50 bg-black/90 flex items-center justify-center" style="display: none;">
                    <button @click="lightbox = null" class="absolute top-4 right-4 p-2 text-white hover:text-gray-300">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                    @foreach($gallery as $item)
                        <div x-show="lightbox === {{ $loop->iteration }}" x-transition
                             class="max-w-4xl mx-4">
                            <img src="{{ image_url($item->image_path) }}" alt="{{ $item->title }}" class="max-h-[80vh] w-auto mx-auto rounded-xl">
                            @if($item->caption)
                                <p class="text-center text-white mt-4">{{ $item->caption }}</p>
                            @endif
                        </div>
                    @endforeach
                    <div class="absolute bottom-8 left-0 right-0 flex items-center justify-center gap-4">
                        <button @click="lightbox = (lightbox - 1 < 1) ? {{ $gallery->count() }} : lightbox - 1" class="p-2 text-white hover:text-gray-300">
                            <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" /></svg>
                        </button>
                        <button @click="lightbox = (lightbox + 1 > {{ $gallery->count() }}) ? 1 : lightbox + 1" class="p-2 text-white hover:text-gray-300">
                            <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                        </button>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-12 text-gray-500"><p>Gallery coming soon.</p></div>
        @endif
    </div>
</x-layouts.app>