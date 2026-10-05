<x-layouts.app>
    @section('title', 'Our Rooms')
    @section('meta_description', 'Rooms and suites kept to the old standards — fine linen, deep beds, soft light')

    <div class="page-hero">
        <img src="https://images.unsplash.com/photo-1611892440504-42a792e24d32?w=1800&q=80" alt="Rooms" class="absolute inset-0 w-full h-full object-cover opacity-30">
        <div class="absolute inset-0 bg-gradient-to-b from-ink-950/70 via-ink-950/40 to-ink-950/85"></div>
        <div class="absolute inset-5 border border-cream-50/12 pointer-events-none"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 md:py-24 text-center">
            <p class="eyebrow-light">✦ &nbsp; Stay With Us &nbsp; ✦</p>
            <h1 class="mt-4 font-display text-5xl md:text-6xl text-cream-50">Rooms <span class="italic text-brass-200">& Suites</span></h1>
            <div class="gold-rule mt-6"><span class="text-brass-300 text-xs">✦</span></div>
            <p class="mt-5 font-light text-cream-50/70">Find the room that suits your manner of travel</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
        <div class="flex flex-wrap justify-center gap-3 mb-12">
            <a href="{{ route('rooms.index') }}" class="filter-pill {{ !request('category') ? 'filter-pill-active' : 'filter-pill-idle' }}">All Rooms</a>
            @foreach($categories as $cat)
                <a href="{{ route('rooms.index', ['category' => $cat->id]) }}" class="filter-pill {{ request('category') == $cat->id ? 'filter-pill-active' : 'filter-pill-idle' }}">{{ $cat->name }}</a>
            @endforeach
        </div>

        @if($rooms->count())
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($rooms as $room)
                    <a href="{{ route('rooms.show', $room->slug) }}" class="card group">
                        <div class="relative h-64 overflow-hidden">
                            @if($room->getPrimaryImage())
                                <img src="{{ image_url($room->getPrimaryImage()) }}" alt="{{ $room->name }}" class="w-full h-full object-cover group-hover:scale-[1.04] transition duration-700">
                            @else
                                <div class="w-full h-full bg-cream-200 flex items-center justify-center">
                                    <span class="font-display text-5xl text-brass-600/40">{{ substr($room->name,0,1) }}</span>
                                </div>
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-ink-950/50 via-transparent to-transparent"></div>
                            <div class="absolute top-4 left-4 bg-cream-50/95 px-3.5 py-2">
                                <p class="text-[10px] uppercase tracking-[0.24em] text-ink-900/60">{{ $room->category->name }}</p>
                            </div>
                        </div>
                        <div class="p-6">
                            <h3 class="font-display text-[26px] leading-tight text-ink-900 group-hover:text-brass-700 transition">{{ $room->name }}</h3>
                            <p class="mt-2 text-sm font-light text-ink-900/55">{{ $room->capacity }} Guests @if($room->bed_type)<span class="mx-2 text-brass-500">·</span>{{ $room->bed_type }}@endif</p>
                            <div class="mt-5 pt-5 border-t border-ink-900/10 flex items-center justify-between">
                                <p class="font-display text-[22px] text-ink-900">&#8358;{{ number_format($room->price_per_night, 0) }}<span class="font-sans text-xs font-light text-ink-900/50"> / night</span></p>
                                <span class="text-[11px] uppercase tracking-[0.24em] text-brass-700">Reserve →</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
            <div class="mt-10">{{ $rooms->withQueryString()->links() }}</div>
        @else
            <div class="text-center py-16">
                <div class="gold-rule"><span class="text-brass-500 text-xs">✦</span></div>
                <p class="mt-6 font-display italic text-2xl text-ink-900/60">No rooms found under this collection.</p>
            </div>
        @endif
    </div>
</x-layouts.app>
