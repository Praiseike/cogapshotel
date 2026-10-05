<x-layouts.app>
    @section('title', $title)
    @section('meta_description', $meta ?? '')

    <div class="page-hero">
        <div class="absolute inset-0 bg-gradient-to-b from-ink-900 to-ink-950"></div>
        <div class="absolute inset-5 border border-cream-50/10 pointer-events-none"></div>
        <div class="relative max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-20 text-center">
            <p class="eyebrow-light">✦ &nbsp; The House &nbsp; ✦</p>
            <h1 class="mt-3 font-display text-5xl text-cream-50">{{ $title }}</h1>
            <div class="gold-rule mt-6"><span class="text-brass-300 text-xs">✦</span></div>
        </div>
    </div>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
        <div class="page-content bg-white border border-ink-900/10 p-8 md:p-12 shadow-[0_24px_60px_-30px_rgba(20,17,13,0.3)]">
            {{ $slot }}
        </div>
    </div>
</x-layouts.app>
