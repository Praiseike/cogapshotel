<x-layouts.app>
    @section('title', 'Our Rooms')
    @section('meta_description', 'Browse our selection of luxurious rooms and suites')

    <div class="bg-gray-900 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl font-display font-bold text-white">Our Rooms</h1>
            <p class="mt-4 text-lg text-gray-300">Find the perfect room for your stay</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="flex flex-wrap gap-3 mb-8">
            <a href="{{ route('rooms.index') }}" class="px-4 py-2 rounded-full text-sm font-medium transition {{ !request('category') ? 'bg-amber-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">All</a>
            @foreach($categories as $cat)
                <a href="{{ route('rooms.index', ['category' => $cat->id]) }}" class="px-4 py-2 rounded-full text-sm font-medium transition {{ request('category') == $cat->id ? 'bg-amber-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">{{ $cat->name }}</a>
            @endforeach
        </div>

        @if($rooms->count())
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($rooms as $room)
                    <a href="{{ route('rooms.show', $room->slug) }}" class="card group hover:shadow-lg transition">
                        <div class="relative h-56 overflow-hidden">
                            @if($room->getPrimaryImage())
                                <img src="{{ image_url($room->getPrimaryImage()) }}" alt="{{ $room->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                            @else
                                <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5z" /></svg>
                                </div>
                            @endif
                            <div class="absolute top-3 right-3">
                                <span class="badge-success bg-white/90 text-emerald-700">{{ $room->category->name }}</span>
                            </div>
                        </div>
                        <div class="p-5">
                            <h3 class="text-lg font-display font-semibold text-gray-900 group-hover:text-amber-600 transition">{{ $room->name }}</h3>
                            <div class="mt-2 flex items-center gap-4 text-sm text-gray-500">
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                                    {{ $room->capacity }} guests
                                </span>
                                @if($room->bed_type)
                                    <span>{{ $room->bed_type }}</span>
                                @endif
                            </div>
                            <div class="mt-4 flex items-center justify-between">
                                <p class="text-xl font-bold text-amber-600">&#8358;{{ number_format($room->price_per_night, 0) }}<span class="text-sm font-normal text-gray-500">/night</span></p>
                                <span class="text-sm text-amber-600 font-medium">Book Now</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
            <div class="mt-8">{{ $rooms->withQueryString()->links() }}</div>
        @else
            <div class="text-center py-12 text-gray-500">
                <svg class="w-12 h-12 mx-auto text-gray-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
                <p class="mt-4">No rooms found matching your criteria.</p>
            </div>
        @endif
    </div>
</x-layouts.app>