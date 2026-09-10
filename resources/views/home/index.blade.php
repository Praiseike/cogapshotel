<x-layouts.app>
    @section('title', 'Welcome to ' . config('app.name'))

    <div class="relative bg-gray-900">
        <div class="absolute inset-0">
            <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?w=1920&q=80" alt="Hotel" class="w-full h-full object-cover opacity-40">
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-32 md:py-48">
            <div class="max-w-2xl">
                <h1 class="text-4xl md:text-6xl font-display font-bold text-white leading-tight">
                    Experience <span class="text-amber-400">Luxury</span> Like Never Before
                </h1>
                <p class="mt-6 text-lg text-gray-300 max-w-lg">
                    Indulge in world-class hospitality, breathtaking views, and unforgettable moments at the finest hotel destination.
                </p>

                <div class="mt-10 flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('rooms.index') }}" class="btn-primary text-lg px-8 py-4">
                        Book Now
                    </a>
                    <a href="{{ route('services.index') }}" class="inline-flex items-center justify-center px-8 py-4 bg-white/10 text-white font-medium rounded-lg border border-white/20 hover:bg-white/20 transition text-lg backdrop-blur-sm">
                        Our Services
                    </a>
                </div>
            </div>
        </div>
    </div>

    <section class="py-16 md:py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="section-title">Our Featured Rooms</h2>
                <p class="section-subtitle max-w-2xl mx-auto mt-4">Handpicked accommodations for an unforgettable experience</p>
            </div>

            @if($featuredRooms->count())
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-12">
                    @foreach($featuredRooms as $room)
                        <a href="{{ route('rooms.show', $room->slug) }}" class="card group hover:shadow-lg transition overflow-hidden">
                            <div class="relative h-56 overflow-hidden">
                                @if($room->getPrimaryImage())
                                    <img src="{{ image_url($room->getPrimaryImage()) }}" alt="{{ $room->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                @else
                                    <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                        <svg class="w-12 h-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5z" /></svg>
                                    </div>
                                @endif
                                <div class="absolute bottom-3 right-3">
                                    <span class="bg-white/90 text-gray-800 text-xs font-medium px-3 py-1.5 rounded-full">
                                        &#8358;{{ number_format($room->price_per_night, 0) }}/night
                                    </span>
                                </div>
                            </div>
                            <div class="p-5">
                                <div class="flex items-center justify-between mb-2">
                                    <h3 class="text-lg font-display font-semibold text-gray-900 group-hover:text-amber-600 transition">{{ $room->name }}</h3>
                                    <span class="badge-gray">{{ $room->category->name }}</span>
                                </div>
                                <p class="flex items-center gap-2 text-sm text-gray-500">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                                    {{ $room->capacity }} guests
                                    @if($room->bed_type)<span class="mx-1">&bull;</span> {{ $room->bed_type }}@endif
                                </p>
                            </div>
                        </a>
                    @endforeach
                </div>
                <div class="text-center mt-10">
                    <a href="{{ route('rooms.index') }}" class="btn-secondary">View All Rooms</a>
                </div>
            @else
                <div class="text-center mt-12 text-gray-500">
                    <p>Rooms coming soon.</p>
                </div>
            @endif
        </div>
    </section>

    <section class="py-16 md:py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="section-title">Hotel Categories</h2>
                <p class="section-subtitle max-w-2xl mx-auto mt-4">Choose from our range of room categories</p>
            </div>

            @if($categories->count())
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-12">
                    @foreach($categories as $cat)
                        <a href="{{ route('rooms.index', ['category' => $cat->id]) }}" class="card group p-6 text-center hover:shadow-lg transition relative overflow-hidden">
                            <div class="absolute inset-0 opacity-0 group-hover:opacity-10 bg-amber-500 transition"></div>
                            <div class="relative">
                                @if($cat->image)
                                    <img src="{{ image_url($cat->image) }}" alt="{{ $cat->name }}" class="w-24 h-24 rounded-full object-cover mx-auto">
                                @else
                                    <div class="w-24 h-24 rounded-full bg-amber-100 flex items-center justify-center mx-auto">
                                        <svg class="w-10 h-10 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" /></svg>
                                    </div>
                                @endif
                                <h3 class="mt-5 text-xl font-display font-semibold text-gray-900 group-hover:text-amber-600 transition">{{ $cat->name }}</h3>
                                <p class="mt-2 text-sm text-gray-600 line-clamp-2">{{ $cat->description }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <p class="text-center mt-8 text-gray-500">Categories coming soon.</p>
            @endif
        </div>
    </section>

    @if($services->count())
        <section class="py-16 md:py-24">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h2 class="section-title">Our Services</h2>
                    <p class="section-subtitle max-w-2xl mx-auto mt-4">World-class amenities available during your stay</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mt-12">
                    @foreach($services as $service)
                        <div class="card p-6 text-center hover:shadow-lg transition">
                            @if($service->image)
                                <img src="{{ image_url($service->image) }}" alt="{{ $service->name }}" class="w-20 h-20 rounded-full object-cover mx-auto">
                            @else
                                <div class="w-14 h-14 bg-amber-100 rounded-xl flex items-center justify-center mx-auto">
                                    <svg class="w-7 h-7 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z" /></svg>
                                </div>
                            @endif
                            <h3 class="mt-4 text-lg font-display font-semibold text-gray-900">{{ $service->name }}</h3>
                            <p class="mt-2 text-sm text-gray-600 line-clamp-3">{{ $service->description }}</p>
                        </div>
                    @endforeach
                </div>
                <div class="text-center mt-10">
                    <a href="{{ route('services.index') }}" class="btn-secondary">View All Services</a>
                </div>
            </div>
        </section>
    @endif

    <section class="py-16 md:py-24 bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="section-title text-white">Ready to Experience the Difference?</h2>
            <p class="section-subtitle max-w-2xl mx-auto mt-4 text-gray-400">Book your stay today and discover why guests choose us time and time again.</p>
            <div class="mt-8">
                <a href="{{ route('rooms.index') }}" class="btn-primary text-lg px-8 py-4">Make a Reservation</a>
            </div>
        </div>
    </section>
</x-layouts.app>