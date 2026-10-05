<x-layouts.app>
    @section('title', 'Our Services')
    @section('meta_description', 'Services and amenities of the house — dining, wellness, concierge')

    <div class="page-hero">
        <img src="https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=1800&q=80" alt="Dining" class="absolute inset-0 w-full h-full object-cover opacity-30">
        <div class="absolute inset-0 bg-gradient-to-b from-ink-950/70 via-ink-950/40 to-ink-950/85"></div>
        <div class="absolute inset-5 border border-cream-50/12 pointer-events-none"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 md:py-24 text-center">
            <p class="eyebrow-light">✦ &nbsp; The House Provides &nbsp; ✦</p>
            <h1 class="mt-4 font-display text-5xl md:text-6xl text-cream-50">Services <span class="italic text-brass-200">& Amenities</span></h1>
            <div class="gold-rule mt-6"><span class="text-brass-300 text-xs">✦</span></div>
            <p class="mt-5 font-light text-cream-50/70">Every comfort, quietly at hand</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
        @if($services->count())
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($services as $service)
                    <div class="card group">
                        @if($service->image)
                            <div class="overflow-hidden h-56">
                                <img src="{{ image_url($service->image) }}" alt="{{ $service->name }}" class="w-full h-full object-cover group-hover:scale-[1.04] transition duration-700">
                            </div>
                        @else
                            <div class="h-56 bg-ink-950 flex flex-col items-center justify-center gap-3">
                                <span class="flex h-14 w-14 items-center justify-center border border-brass-400/60 rotate-45"><span class="-rotate-45 font-display text-2xl text-brass-300">{{ substr($service->name,0,1) }}</span></span>
                            </div>
                        @endif
                        <div class="p-7">
                            <div class="flex items-start justify-between gap-4">
                                <h2 class="font-display text-[26px] leading-tight text-ink-900">{{ $service->name }}</h2>
                                @if($service->price)
                                    <span class="badge-warning whitespace-nowrap">&#8358;{{ number_format($service->price, 0) }}</span>
                                @endif
                            </div>
                            @if($service->category)
                                <p class="mt-1 text-[11px] uppercase tracking-[0.24em] text-brass-700">{{ $service->category }}</p>
                            @endif
                            <span class="mt-4 block h-px w-10 bg-brass-500/70"></span>
                            <p class="mt-4 font-light text-[15px] leading-relaxed text-ink-900/60">{{ $service->description }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-10">{{ $services->links() }}</div>
        @else
            <p class="text-center py-14 font-display italic text-2xl text-ink-900/50">Services are being prepared.</p>
        @endif
    </div>
</x-layouts.app>
