<x-layouts.app>
    @section('title', 'Gallery')
    @section('meta_description', 'A portrait of the house — rooms, halls and quiet corners')

    <div class="page-hero">
        <div class="absolute inset-0 bg-gradient-to-b from-ink-900 to-ink-950"></div>
        <div class="absolute inset-5 border border-cream-50/10 pointer-events-none"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 md:py-24 text-center">
            <p class="eyebrow-light">✦ &nbsp; A Portrait of the House &nbsp; ✦</p>
            <h1 class="mt-4 font-display text-5xl md:text-6xl text-cream-50">Gallery</h1>
            <div class="gold-rule mt-6"><span class="text-brass-300 text-xs">✦</span></div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
        @if($gallery->count())
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3" x-data="{ lightbox: null }">
                @foreach($gallery as $item)
                    <figure class="relative cursor-pointer group overflow-hidden bg-ink-950" @click="lightbox = {{ $loop->iteration }}">
                        <img src="{{ image_url($item->image_path) }}" alt="{{ $item->title }}" class="w-full h-72 object-cover opacity-90 group-hover:opacity-70 group-hover:scale-[1.03] transition duration-700">
                        <div class="absolute inset-3 border border-cream-50/0 group-hover:border-cream-50/40 transition pointer-events-none"></div>
                        @if($item->caption)
                            <figcaption class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-ink-950/85 to-transparent p-5 pt-10">
                                <p class="font-display italic text-lg text-cream-50">{{ $item->caption }}</p>
                            </figcaption>
                        @endif
                    </figure>
                @endforeach

                <div x-show="lightbox !== null" @keydown.escape.window="lightbox = null"
                     class="fixed inset-0 z-50 bg-ink-950/95 flex items-center justify-center" style="display: none;">
                    <button @click="lightbox = null" class="absolute top-6 right-6 p-2 text-cream-50/70 hover:text-cream-50 text-[11px] uppercase tracking-[0.3em]">
                        Close ✕
                    </button>
                    @foreach($gallery as $item)
                        <div x-show="lightbox === {{ $loop->iteration }}" x-transition class="max-w-4xl mx-4 text-center">
                            <div class="border border-brass-400/30 p-2 bg-ink-950">
                                <img src="{{ image_url($item->image_path) }}" alt="{{ $item->title }}" class="max-h-[76vh] w-auto mx-auto">
                            </div>
                            @if($item->caption)
                                <p class="text-center font-display italic text-xl text-cream-50 mt-4">{{ $item->caption }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <p class="text-center py-14 font-display italic text-2xl text-ink-900/50">The album is being prepared.</p>
        @endif
    </div>
</x-layouts.app>
