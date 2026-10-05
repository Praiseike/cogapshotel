@php($hotelName = \App\Models\Setting::getValue('hotel_name', config('app.name', 'Hotel')))
@php($hotelPhone = \App\Models\Setting::getValue('hotel_phone', '+1 234 567 890'))
@php($hotelEmail = \App\Models\Setting::getValue('hotel_email', 'reservations@hotel.com'))
<header class="bg-ink-950 text-cream-50 sticky top-0 z-50">
    {{-- gold hairline --}}
    <div class="h-[3px] bg-gradient-to-r from-brass-800 via-brass-400 to-brass-800"></div>

    {{-- utility strip --}}
    <div class="hidden md:block border-b border-cream-50/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-9 flex items-center justify-between text-[11px] uppercase tracking-[0.22em] text-cream-50/55">
            <p class="flex items-center gap-6">
                <span class="flex items-center gap-2">
                    <svg class="w-3.5 h-3.5 text-brass-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" /></svg>
                    {{ $hotelPhone }}
                </span>
                <span class="hidden lg:flex items-center gap-2">
                    <svg class="w-3.5 h-3.5 text-brass-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" /></svg>
                    {{ $hotelEmail }}
                </span>
            </p>
            <p class="flex items-center gap-5">
                @auth
                    <a href="{{ route('dashboard.index') }}" class="hover:text-brass-300 transition">My Reservations</a>
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="hover:text-brass-300 transition">Concierge Desk</a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="hover:text-brass-300 transition">Sign In</a>
                    <a href="{{ route('register') }}" class="hover:text-brass-300 transition">Register</a>
                @endauth
                <span class="text-brass-400/70">✦ &nbsp;Est. Heritage House</span>
            </p>
        </div>
    </div>

    {{-- main bar --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" x-data="{ open: false }">
        <div class="flex items-center justify-between h-20">
            <a href="{{ route('home') }}" class="flex items-center gap-4 group">
                <img src="{{ asset('images/logo.png') }}" alt="{{ $hotelName }} logo"
                     class="h-11 w-auto max-w-[160px] object-contain shrink-0" />
                <span class="leading-tight">
                    <span class="block font-display text-[26px] tracking-wide text-cream-50">{{ $hotelName }}</span>
                    <span class="block text-[10px] uppercase tracking-[0.38em] text-brass-300/90">Hotel · Suites · Residence</span>
                </span>
            </a>

            <nav class="hidden lg:flex items-center gap-9">
                <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'nav-link-active' : '' }}">Home</a>
                <a href="{{ route('rooms.index') }}" class="nav-link {{ request()->routeIs('rooms.*') ? 'nav-link-active' : '' }}">Rooms</a>
                <a href="{{ route('services.index') }}" class="nav-link {{ request()->routeIs('services.*') ? 'nav-link-active' : '' }}">Services</a>
                <a href="{{ route('gallery.index') }}" class="nav-link {{ request()->routeIs('gallery.*') ? 'nav-link-active' : '' }}">Gallery</a>
                <a href="{{ route('about') }}" class="nav-link {{ request()->routeIs('about') ? 'nav-link-active' : '' }}">Heritage</a>
                <a href="{{ route('contact.show') }}" class="nav-link {{ request()->routeIs('contact.show') ? 'nav-link-active' : '' }}">Contact</a>
            </nav>

            <div class="hidden lg:flex items-center gap-3">
                @auth
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-[11px] uppercase tracking-[0.22em] text-cream-50/60 hover:text-cream-50 transition px-2">Sign Out</button>
                    </form>
                    <span class="h-5 w-px bg-cream-50/15"></span>
                @endauth
                <a href="{{ route('rooms.index') }}" class="inline-flex items-center gap-3 border border-brass-400/70 px-6 py-3 text-[11px] font-medium uppercase tracking-[0.24em] text-brass-200 hover:bg-brass-600 hover:border-brass-600 hover:text-white transition-all duration-300">
                    Reserve
                    <span aria-hidden="true">→</span>
                </a>
            </div>

            <button @click="open = !open" class="lg:hidden p-2 text-cream-50/80 hover:text-cream-50" aria-label="Menu">
                <svg x-show="!open" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke-width="1.2" stroke="currentColor"><path stroke-linecap="round" d="M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h16.5" /></svg>
                <svg x-show="open" x-cloak class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke-width="1.2" stroke="currentColor"><path stroke-linecap="round" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>

        {{-- mobile --}}
        <div x-show="open" x-cloak x-transition class="lg:hidden border-t border-cream-50/10 pb-6">
            <div class="pt-4 flex flex-col gap-1 text-center">
                @foreach([['home','Home',route('home')],['rooms','Rooms',route('rooms.index')],['services','Services',route('services.index')],['gallery','Gallery',route('gallery.index')],['about','Heritage',route('about')],['contact','Contact',route('contact.show')]] as [$key,$label,$url])
                    <a href="{{ $url }}" class="py-3 text-[12px] uppercase tracking-[0.28em] text-cream-50/75 hover:text-brass-300 transition">{{ $label }}</a>
                @endforeach
                <div class="gold-rule my-4 opacity-70"><span class="text-brass-400 text-xs">✦</span></div>
                @auth
                    <a href="{{ route('dashboard.index') }}" class="py-2 text-[12px] uppercase tracking-[0.24em] text-cream-50/70">My Reservations</a>
                @else
                    <div class="flex justify-center gap-6 py-2 text-[12px] uppercase tracking-[0.24em]">
                        <a href="{{ route('login') }}" class="text-cream-50/70">Sign In</a>
                        <a href="{{ route('register') }}" class="text-brass-300">Register</a>
                    </div>
                @endauth
                <a href="{{ route('rooms.index') }}" class="mt-3 mx-auto inline-flex border border-brass-400/70 px-10 py-3.5 text-[11px] uppercase tracking-[0.26em] text-brass-200">Reserve Your Stay</a>
            </div>
        </div>
    </div>
</header>
