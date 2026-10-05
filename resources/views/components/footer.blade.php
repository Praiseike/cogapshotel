<footer class="bg-ink-950 text-cream-50/70">
    @php($siteName = \App\Models\Setting::getValue('hotel_name', config('app.name', 'Hotel')))
    @php($siteEmail = \App\Models\Setting::getValue('hotel_email', 'reservations@hotel.com'))
    @php($sitePhone = \App\Models\Setting::getValue('hotel_phone', '+1 234 567 890'))
    @php($siteWhatsapp = \App\Models\Setting::getValue('hotel_whatsapp', $sitePhone))
    @php($siteAddress = \App\Models\Setting::getValue('hotel_address', '12 Heritage Avenue, Old Town'))
    @php($siteDescription = \App\Models\Setting::getValue('hotel_description', 'A grand house of quiet luxury — fine rooms, attentive service, and timeless calm in the heart of the city.'))
    @php($waUrl = whatsapp_url($siteWhatsapp, \App\Models\Setting::getValue('hotel_whatsapp_message', 'Hello! I would like to enquire about availability.')))

    <div class="h-[3px] bg-gradient-to-r from-brass-800 via-brass-400 to-brass-800"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="py-16 grid grid-cols-1 md:grid-cols-12 gap-12">
            <div class="md:col-span-5">
                <div class="flex items-center gap-4">
                    <span class="flex h-12 w-12 items-center justify-center border border-brass-400/70 outline outline-1 outline-offset-[5px] outline-brass-400/20 font-display text-2xl text-brass-300">
                        {{ strtoupper(substr($siteName, 0, 1)) }}
                    </span>
                    <span class="leading-tight">
                        <span class="block font-display text-3xl text-cream-50">{{ $siteName }}</span>
                        <span class="block text-[10px] uppercase tracking-[0.38em] text-brass-300 mt-1">Hotel · Suites · Residence</span>
                    </span>
                </div>
                <p class="mt-6 max-w-sm font-light leading-[1.85] text-[15px]">{{ $siteDescription }}</p>
                <div class="mt-7 flex items-center gap-3">
                    <span class="h-px w-12 bg-brass-500/70"></span>
                    <span class="text-[11px] uppercase tracking-[0.3em] text-brass-300">Since · Heritage · Honour</span>
                </div>
            </div>

            <div class="md:col-span-3">
                <h4 class="text-[11px] font-medium uppercase tracking-[0.32em] text-cream-50">Explore</h4>
                <span class="mt-3 block h-px w-10 bg-brass-500/70"></span>
                <ul class="mt-6 space-y-3.5 text-[15px] font-light">
                    <li><a href="{{ route('rooms.index') }}" class="hover:text-brass-300 transition-colors">Rooms &amp; Suites</a></li>
                    <li><a href="{{ route('services.index') }}" class="hover:text-brass-300 transition-colors">Services &amp; Dining</a></li>
                    <li><a href="{{ route('gallery.index') }}" class="hover:text-brass-300 transition-colors">Gallery</a></li>
                    <li><a href="{{ route('about') }}" class="hover:text-brass-300 transition-colors">Our Heritage</a></li>
                    <li><a href="{{ route('contact.show') }}" class="hover:text-brass-300 transition-colors">Contact &amp; Location</a></li>
                </ul>
            </div>

            <div class="md:col-span-4">
                <h4 class="text-[11px] font-medium uppercase tracking-[0.32em] text-cream-50">Reservations</h4>
                <span class="mt-3 block h-px w-10 bg-brass-500/70"></span>
                <ul class="mt-6 space-y-4 text-[15px] font-light">
                    <li class="flex gap-3"><span class="text-brass-400">—</span><span>{{ $siteAddress }}</span></li>
                    <li class="flex gap-3"><span class="text-brass-400">—</span><span>{{ $sitePhone }}</span></li>
                    <li class="flex gap-3"><span class="text-brass-400">—</span><span>{{ $siteEmail }}</span></li>
                    @if($waUrl)
                    <li class="flex gap-3"><span class="text-brass-400">—</span><a href="{{ $waUrl }}" target="_blank" rel="noopener" class="hover:text-brass-300">WhatsApp: {{ $siteWhatsapp }}</a></li>
                    @endif
                </ul>
                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ route('rooms.index') }}" class="inline-flex items-center gap-3 border border-brass-400/60 px-7 py-3 text-[11px] uppercase tracking-[0.26em] text-brass-200 hover:bg-brass-600 hover:border-brass-600 hover:text-white transition-all duration-300">
                        Book Your Stay <span aria-hidden="true">→</span>
                    </a>
                    @if($waUrl)
                    <a href="{{ $waUrl }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 bg-[#25D366] px-6 py-3 text-[11px] uppercase tracking-[0.2em] text-white hover:bg-[#1ebe59] transition">
                        WhatsApp Us
                    </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="border-t border-cream-50/10 py-7 flex flex-col md:flex-row items-center justify-between gap-4">
            <span class="text-[12px] uppercase tracking-[0.2em] text-cream-50/40">&copy; {{ date('Y') }} {{ $siteName }}. All rights reserved.</span>
            <span class="flex gap-8 text-[12px] uppercase tracking-[0.2em]">
                <a href="{{ route('policies') }}" class="text-cream-50/40 hover:text-brass-300 transition-colors">Policies</a>
                <a href="{{ route('privacy') }}" class="text-cream-50/40 hover:text-brass-300 transition-colors">Privacy</a>
                <a href="{{ route('terms') }}" class="text-cream-50/40 hover:text-brass-300 transition-colors">Terms</a>
                <a href="{{ route('sitemap') }}" class="text-cream-50/40 hover:text-brass-300 transition-colors">Sitemap</a>
            </span>
        </div>
    </div>
</footer>
