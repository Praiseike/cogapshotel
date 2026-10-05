<header class="bg-cream-50/95 backdrop-blur border-b border-ink-900/10 sticky top-0 z-20">
    <div class="h-[2px] bg-gradient-to-r from-brass-700 via-brass-400 to-brass-700"></div>
    <div class="flex items-center justify-between h-[72px] px-4 sm:px-8">
        <div class="flex items-center gap-4">
            <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 text-ink-900/60 hover:text-ink-900">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.2" stroke="currentColor">
                    <path stroke-linecap="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
            </button>

            <div>
                <p class="text-[10px] uppercase tracking-[0.3em] text-brass-700">Concierge Desk</p>
                <h1 class="font-display text-[26px] leading-none text-ink-900 mt-0.5">@yield('header', 'Dashboard')</h1>
            </div>
        </div>

        <div class="flex items-center gap-5">
            <a href="{{ route('home') }}" class="hidden sm:inline-flex text-[11px] uppercase tracking-[0.22em] text-ink-900/55 hover:text-ink-900 transition" target="_blank">
                View House →
            </a>

            <span class="hidden sm:block h-6 w-px bg-ink-900/10"></span>

            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center gap-2.5 group">
                    <div class="w-9 h-9 flex items-center justify-center bg-ink-950 text-brass-200 font-display text-lg">
                        {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
                    </div>
                    <svg class="w-4 h-4 text-ink-900/40" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
                </button>

                <div x-show="open" @click.away="open = false" x-transition
                     class="absolute right-0 mt-3 w-56 bg-white border border-ink-900/10 shadow-xl py-1 z-50">
                    <div class="px-5 py-3 border-b border-ink-900/10">
                        <p class="text-sm text-ink-900">{{ auth()->user()->name ?? 'Admin' }}</p>
                        <p class="text-xs font-light text-ink-900/50">{{ auth()->user()->email ?? '' }}</p>
                    </div>
                    <a href="{{ route('home') }}" class="block px-5 py-2.5 text-sm font-light text-ink-900/70 hover:bg-cream-100">View House</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-5 py-2.5 text-sm text-red-900 hover:bg-cream-100">Sign Out</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
