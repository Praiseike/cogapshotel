<x-layouts.app>
    @section('title', 'Welcome to ' . config('app.name'))
    @php($hotelName = \App\Models\Setting::getValue('hotel_name', config('app.name', 'Hotel')))

    {{-- ══ HERO ══ --}}
    <div class="relative bg-ink-950 overflow-hidden">
        <div class="absolute inset-0">
            <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?w=1920&q=80" alt="Hotel" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-b from-ink-950/70 via-ink-950/45 to-ink-950/80"></div>
        </div>
        <div class="absolute inset-5 md:inset-8 border border-cream-50/15 pointer-events-none"></div>

        <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pt-24 pb-20 md:pt-36 md:pb-28 text-center">
            <p class="eyebrow-light">✦ &nbsp; A Grand House of Rest &nbsp; ✦</p>
            <h1 class="mt-6 font-display text-5xl md:text-7xl lg:text-[86px] font-medium text-cream-50 leading-[1.02]">
                Timeless Comfort,<br><span class="italic font-normal text-brass-200">Quiet Luxury</span>
            </h1>
            <div class="gold-rule mt-8"><span class="text-brass-300 text-sm">✦</span></div>
            <p class="mt-7 text-[17px] md:text-lg font-light text-cream-50/75 max-w-2xl mx-auto leading-relaxed">
                Fine rooms, attentive service and unhurried calm — in the very heart of the city. Your suite is waiting.
            </p>

            <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('rooms.index') }}" class="btn-primary !bg-brass-600 hover:!bg-brass-500 min-w-[220px]">
                    Reserve Your Stay
                </a>
                <a href="{{ route('services.index') }}" class="btn-outline-light min-w-[220px]">
                    Discover the House
                </a>
            </div>

            {{-- classic booking strip --}}
            <div class="mt-14 bg-cream-50/95 backdrop-blur text-left shadow-2xl">
                <div class="h-[3px] bg-gradient-to-r from-brass-700 via-brass-400 to-brass-700"></div>
                <form action="{{ route('rooms.index') }}" method="GET" class="grid grid-cols-2 md:grid-cols-4 divide-x divide-ink-900/10">
                    <div class="px-6 py-5">
                        <label class="input-label !mb-1">Arrival</label>
                        <input type="date" name="check_in" value="{{ request('check_in') }}" class="w-full bg-transparent text-[15px] text-ink-900 focus:outline-none">
                    </div>
                    <div class="px-6 py-5">
                        <label class="input-label !mb-1">Departure</label>
                        <input type="date" name="check_out" value="{{ request('check_out') }}" class="w-full bg-transparent text-[15px] text-ink-900 focus:outline-none">
                    </div>
                    <div class="px-6 py-5">
                        <label class="input-label !mb-1">Guests</label>
                        <select name="guests" class="w-full bg-transparent text-[15px] text-ink-900 focus:outline-none">
                            <option value="1">1 Guest</option>
                            <option value="2" selected>2 Guests</option>
                            <option value="3">3 Guests</option>
                            <option value="4">4+ Guests</option>
                        </select>
                    </div>
                    <button class="bg-ink-900 text-cream-50 text-[12px] uppercase tracking-[0.26em] hover:bg-brass-700 transition-colors px-6 py-5">
                        Check Availability →
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- ══ WELCOME / HERITAGE STRIP ══ --}}
    <section class="bg-cream-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 md:py-28 grid lg:grid-cols-2 gap-14 items-center">
            <div class="relative">
                <div class="grid grid-cols-12 gap-4">
                    <img src="https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=900&q=80" alt="Suite" class="col-span-8 h-[420px] object-cover">
                    <img src="https://images.unsplash.com/photo-1590490360182-c33d57733427?w=700&q=80" alt="Detail" class="col-span-4 h-[420px] object-cover mt-10">
                </div>
                <div class="absolute -bottom-6 left-6 md:left-10 bg-ink-950 text-cream-50 px-8 py-6 shadow-2xl">
                    <p class="font-display text-5xl text-brass-300">25<span class="text-2xl align-top">+</span></p>
                    <p class="mt-1 text-[11px] uppercase tracking-[0.28em] text-cream-50/60">Years of Hospitality</p>
                </div>
                <div class="absolute -top-4 -left-4 w-28 h-28 border-t border-l border-brass-500/60 pointer-events-none"></div>
            </div>
            <div>
                <p class="eyebrow">Welcome to {{ $hotelName }}</p>
                <h2 class="section-title mt-4">A House Kept<br><span class="italic font-normal">to the Old Standards</span></h2>
                <div class="mt-6 h-px w-24 bg-brass-500"></div>
                <p class="mt-6 text-[17px] font-light leading-[1.85] text-ink-900/65">
                    High ceilings, crisp linen, polished brass and unhurried mornings. Every room is prepared by hand,
                    every arrival greeted by name — the way grand hotels have always done it.
                </p>
                <div class="mt-8 grid grid-cols-3 gap-6 border-t border-ink-900/10 pt-8">
                    <div><p class="font-display text-3xl text-ink-900">48</p><p class="mt-1 text-[11px] uppercase tracking-[0.22em] text-ink-900/50">Rooms & Suites</p></div>
                    <div><p class="font-display text-3xl text-ink-900">4.9</p><p class="mt-1 text-[11px] uppercase tracking-[0.22em] text-ink-900/50">Guest Rating</p></div>
                    <div><p class="font-display text-3xl text-ink-900">24<span class="text-lg">/7</span></p><p class="mt-1 text-[11px] uppercase tracking-[0.22em] text-ink-900/50">Concierge</p></div>
                </div>
                <a href="{{ route('about') }}" class="btn-secondary mt-10">Our Heritage</a>
            </div>
        </div>
    </section>

    {{-- ══ FEATURED ROOMS ══ --}}
    <section class="bg-white border-y border-ink-900/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 md:py-28">
            <div class="text-center max-w-2xl mx-auto">
                <p class="eyebrow">✦ &nbsp; Stay With Us &nbsp; ✦</p>
                <h2 class="section-title mt-4">Rooms & <span class="italic font-normal">Suites</span></h2>
                <div class="gold-rule mt-6"><span class="text-brass-500 text-xs">✦</span></div>
                <p class="section-subtitle">Hand-kept rooms with fine linen, deep beds and soft morning light.</p>
            </div>

            @if($featuredRooms->count())
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mt-14">
                    @foreach($featuredRooms as $room)
                        <a href="{{ route('rooms.show', $room->slug) }}" class="card group">
                            <div class="relative h-64 overflow-hidden">
                                @if($room->getPrimaryImage())
                                    <img src="{{ image_url($room->getPrimaryImage()) }}" alt="{{ $room->name }}" class="w-full h-full object-cover group-hover:scale-[1.04] transition duration-700">
                                @else
                                    <div class="w-full h-full bg-cream-200 flex items-center justify-center">
                                        <span class="font-display text-5xl text-brass-600/40">{{ substr($room->name,0,1) }}</span>
                                    </div>
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-t from-ink-950/55 via-transparent to-transparent"></div>
                                <div class="absolute top-4 left-4 bg-cream-50/95 px-3.5 py-2">
                                    <p class="text-[10px] uppercase tracking-[0.24em] text-ink-900/60">{{ $room->category->name }}</p>
                                </div>
                                <div class="absolute bottom-4 left-5 right-5 flex items-end justify-between">
                                    <p class="font-display text-2xl text-cream-50">{{ $room->name }}</p>
                                </div>
                            </div>
                            <div class="px-6 py-5 flex items-center justify-between border-t border-ink-900/10">
                                <p class="text-[15px] text-ink-900/60 font-light">{{ $room->capacity }} Guests @if($room->bed_type)<span class="mx-2 text-brass-500">·</span>{{ $room->bed_type }}@endif</p>
                                <p class="font-display text-[22px] text-ink-900 whitespace-nowrap">&#8358;{{ number_format($room->price_per_night, 0) }} <span class="text-xs font-sans font-light text-ink-900/50">/ night</span></p>
                            </div>
                            <div class="px-6 pb-6">
                                <span class="inline-flex items-center gap-3 text-[11px] uppercase tracking-[0.26em] text-brass-700 group-hover:gap-5 transition-all">View Room <span>→</span></span>
                            </div>
                        </a>
                    @endforeach
                </div>
                <div class="text-center mt-12">
                    <a href="{{ route('rooms.index') }}" class="btn-secondary">View All Rooms</a>
                </div>
            @else
                <p class="text-center mt-12 font-display italic text-xl text-ink-900/50">Rooms are being prepared — please return shortly.</p>
            @endif
        </div>
    </section>

    {{-- ══ CATEGORIES ══ --}}
    @if($categories->count())
    <section class="bg-cream-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 md:py-24">
            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6">
                <div>
                    <p class="eyebrow">Choose Your Manner of Stay</p>
                    <h2 class="section-title mt-3">Collections</h2>
                </div>
                <a href="{{ route('rooms.index') }}" class="text-[12px] uppercase tracking-[0.24em] text-brass-700 hover:text-ink-900 transition">Browse all →</a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 mt-10">
                @foreach($categories as $cat)
                    <a href="{{ route('rooms.index', ['category' => $cat->id]) }}" class="group relative overflow-hidden bg-ink-950 h-80">
                        @if($cat->image)
                            <img src="{{ image_url($cat->image) }}" alt="{{ $cat->name }}" class="absolute inset-0 w-full h-full object-cover opacity-70 group-hover:opacity-50 group-hover:scale-[1.04] transition duration-700">
                        @else
                            <div class="absolute inset-0 bg-ink-800"></div>
                        @endif
                        <div class="absolute inset-4 border border-cream-50/25 pointer-events-none"></div>
                        <div class="relative h-full flex flex-col items-center justify-center text-center p-8">
                            <p class="text-[10px] uppercase tracking-[0.34em] text-brass-300">The</p>
                            <h3 class="mt-2 font-display text-4xl text-cream-50">{{ $cat->name }}</h3>
                            <span class="mt-4 h-px w-10 bg-brass-400 group-hover:w-16 transition-all"></span>
                            <p class="mt-4 text-sm font-light text-cream-50/70 line-clamp-2 max-w-[26ch]">{{ $cat->description }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- ══ SERVICES ══ --}}
    @if($services->count())
        <section class="bg-ink-950 relative overflow-hidden">
            <div class="absolute inset-0 opacity-[0.07]" style="background-image: radial-gradient(circle at 1px 1px, #c4a35f 1px, transparent 0); background-size: 26px 26px;"></div>
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 md:py-28">
                <div class="text-center max-w-2xl mx-auto">
                    <p class="eyebrow-light">✦ &nbsp; The House Provides &nbsp; ✦</p>
                    <h2 class="mt-4 font-display text-4xl md:text-5xl text-cream-50">Services & <span class="italic text-brass-200">Amenities</span></h2>
                    <div class="gold-rule mt-6"><span class="text-brass-400 text-xs">✦</span></div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-px bg-cream-50/10 mt-14 border border-cream-50/10">
                    @foreach($services as $service)
                        <div class="bg-ink-950 p-8 text-center hover:bg-ink-800 transition-colors duration-300">
                            @if($service->image)
                                <img src="{{ image_url($service->image) }}" alt="{{ $service->name }}" class="w-16 h-16 rounded-full object-cover mx-auto ring-1 ring-brass-400/50 ring-offset-4 ring-offset-ink-950">
                            @else
                                <div class="w-14 h-14 mx-auto flex items-center justify-center border border-brass-400/50 rotate-45">
                                    <span class="-rotate-45 font-display text-xl text-brass-300">{{ substr($service->name,0,1) }}</span>
                                </div>
                            @endif
                            <h3 class="mt-6 font-display text-[22px] text-cream-50">{{ $service->name }}</h3>
                            <span class="mt-3 block h-px w-8 bg-brass-500/70 mx-auto"></span>
                            <p class="mt-3 text-sm font-light text-cream-50/60 leading-relaxed line-clamp-3">{{ $service->description }}</p>
                        </div>
                    @endforeach
                </div>
                <div class="text-center mt-12">
                    <a href="{{ route('services.index') }}" class="btn-outline-light">View All Services</a>
                </div>
            </div>
        </section>
    @endif

    {{-- ══ QUOTE ══ --}}
    <section class="bg-cream-100">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 py-20 md:py-24 text-center">
            <span class="font-display text-7xl text-brass-400 leading-none">“</span>
            <p class="font-display text-3xl md:text-4xl leading-snug text-ink-900 italic -mt-4">Impeccable in every detail. The room felt like a private residence, and the staff remembered everything.</p>
            <div class="gold-rule mt-8"><span class="text-brass-500 text-xs">✦</span></div>
            <p class="mt-5 text-[11px] uppercase tracking-[0.3em] text-ink-900/50">A Recent Guest — The Palm Suite</p>
        </div>
    </section>

    {{-- ══ CTA ══ --}}
    <section class="relative bg-ink-950 overflow-hidden">
        <img src="https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?w=1800&q=80" alt="Hotel at dusk" class="absolute inset-0 w-full h-full object-cover opacity-25">
        <div class="absolute inset-0 bg-gradient-to-b from-ink-950/60 via-ink-950/40 to-ink-950/80"></div>
        <div class="absolute inset-5 border border-brass-400/25 pointer-events-none"></div>
        <div class="relative max-w-3xl mx-auto px-6 py-20 md:py-28 text-center">
            <p class="eyebrow-light">Reservations Are Open</p>
            <h2 class="mt-4 font-display text-4xl md:text-6xl text-cream-50 leading-tight">Your Suite Awaits<br><span class="italic text-brass-200">Its Guest</span></h2>
            <div class="gold-rule mt-7"><span class="text-brass-300 text-xs">✦</span></div>
            <p class="mt-6 font-light text-cream-50/70">Best rate when you reserve directly with the house.</p>
            <div class="mt-9 flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ route('rooms.index') }}" class="btn-primary !bg-brass-600 hover:!bg-brass-500 min-w-[220px]">Make a Reservation</a>
                <a href="{{ route('contact.show') }}" class="btn-outline-light min-w-[220px]">Speak to Concierge</a>
            </div>
        </div>
    </section>
</x-layouts.app>
