@php
    $currentRoute = request()->route()->getName() ?? '';
    $link = function($match) use ($currentRoute) {
        $active = str_starts_with($currentRoute, $match);
        return $active
            ? 'flex items-center gap-3 px-4 py-3 text-[11px] font-medium uppercase tracking-[0.22em] bg-brass-600/15 text-brass-200 border-l-2 border-brass-400 transition'
            : 'flex items-center gap-3 px-4 py-3 text-[11px] font-medium uppercase tracking-[0.22em] text-cream-50/55 hover:text-cream-50 hover:bg-cream-50/5 border-l-2 border-transparent transition';
    };
@endphp

<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
       class="fixed inset-y-0 left-0 z-40 w-72 bg-ink-950 text-cream-50 transition-transform duration-300 ease-in-out flex flex-col border-r border-brass-600/20">
    <div class="flex items-center gap-3 h-20 px-6 border-b border-cream-50/10 shrink-0">
        <span class="flex h-10 w-10 items-center justify-center border border-brass-400/70 font-display text-xl text-brass-300">
            {{ strtoupper(substr(config('app.name', 'H'), 0, 1)) }}
        </span>
        <span class="leading-tight">
            <span class="block font-display text-xl text-cream-50">{{ config('app.name', 'Hotel') }}</span>
            <span class="block text-[9px] uppercase tracking-[0.32em] text-brass-300/80 mt-0.5">Concierge Desk</span>
        </span>
    </div>

    <nav class="mt-6 px-3 space-y-0.5 flex-1 overflow-y-auto">
        <p class="px-4 pb-2 text-[10px] uppercase tracking-[0.3em] text-cream-50/35">Management</p>
        <a href="{{ route('admin.dashboard') }}" class="{{ $link('admin.dashboard') }}">
            <svg class="w-[18px] h-[18px] opacity-70" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" /></svg>
            Dashboard
        </a>

        <a href="{{ route('admin.bookings.index') }}" class="{{ $link('admin.bookings') }}">
            <svg class="w-[18px] h-[18px] opacity-70" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" /></svg>
            Bookings
        </a>

        <a href="{{ route('admin.rooms.index') }}" class="{{ $link('admin.rooms') }}">
            <svg class="w-[18px] h-[18px] opacity-70" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 21v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21m0 0h4.5V3.545M12.75 21h7.5V10.75M2.25 21h1.5m18 0h-18M2.25 9l4.5-1.636M18.75 3l-1.5.545m0 6.205l3 1m1.5.5l-1.5-.5M6.75 7.364V3h-3v18m3-13.636l10.5-3.819" /></svg>
            Rooms
        </a>

        <a href="{{ route('admin.categories.index') }}" class="{{ $link('admin.categories') }}">
            <svg class="w-[18px] h-[18px] opacity-70" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" /><path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" /></svg>
            Collections
        </a>

        <p class="px-4 pt-6 pb-2 text-[10px] uppercase tracking-[0.3em] text-cream-50/35">House</p>

        <a href="{{ route('admin.services.index') }}" class="{{ $link('admin.services') }}">
            <svg class="w-[18px] h-[18px] opacity-70" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z" /></svg>
            Services
        </a>

        <a href="{{ route('admin.gallery.index') }}" class="{{ $link('admin.gallery') }}">
            <svg class="w-[18px] h-[18px] opacity-70" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" /></svg>
            Gallery
        </a>

        <a href="{{ route('admin.contacts.index') }}" class="{{ $link('admin.contacts') }}">
            <svg class="w-[18px] h-[18px] opacity-70" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" /></svg>
            Messages
            @if(\App\Models\Contact::unread()->count() > 0)
                <span class="ml-auto bg-brass-500 text-white text-[10px] tracking-normal px-2 py-0.5">{{ \App\Models\Contact::unread()->count() }}</span>
            @endif
        </a>

        <a href="{{ route('admin.settings.index') }}" class="{{ $link('admin.settings') }}">
            <svg class="w-[18px] h-[18px] opacity-70" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
            Settings
        </a>
    </nav>

    <div class="p-5 border-t border-cream-50/10">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 flex items-center justify-center border border-brass-400/60 font-display text-lg text-brass-300">
                {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm text-cream-50 truncate">{{ auth()->user()->name ?? 'Admin' }}</p>
                <p class="text-[11px] uppercase tracking-[0.18em] text-cream-50/40 truncate">Hotelier</p>
            </div>
        </div>
    </div>
</aside>

<div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
     @click="sidebarOpen = false"
     class="fixed inset-0 z-30 bg-ink-950/60 lg:hidden">
</div>
