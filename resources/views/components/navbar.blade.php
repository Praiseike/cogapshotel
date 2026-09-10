<nav class="bg-gray-900 border-b border-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <svg class="w-8 h-8 text-amber-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z" />
                    </svg>
                    <span class="text-xl font-display font-bold text-white tracking-tight">{{ \App\Models\Setting::getValue('hotel_name', config('app.name', 'Hotel')) }}</span>
                </a>
            </div>

            <div class="hidden md:flex md:items-center md:space-x-6">
                <a href="{{ route('home') }}" class="text-sm font-medium text-gray-300 hover:text-white transition{{ request()->routeIs('home') ? ' text-white' : '' }}">Home</a>
                <a href="{{ route('rooms.index') }}" class="text-sm font-medium text-gray-300 hover:text-white transition{{ request()->routeIs('rooms.*') ? ' text-white' : '' }}">Rooms</a>
                <a href="{{ route('services.index') }}" class="text-sm font-medium text-gray-300 hover:text-white transition{{ request()->routeIs('services.*') ? ' text-white' : '' }}">Services</a>
                <a href="{{ route('gallery.index') }}" class="text-sm font-medium text-gray-300 hover:text-white transition{{ request()->routeIs('gallery.*') ? ' text-white' : '' }}">Gallery</a>
                <a href="{{ route('contact.show') }}" class="text-sm font-medium text-gray-300 hover:text-white transition{{ request()->routeIs('contact.show') ? ' text-white' : '' }}">Contact</a>
            </div>

            <div class="hidden md:flex md:items-center md:space-x-3">
                @auth
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="text-sm font-medium text-gray-300 hover:text-white transition">Admin</a>
                    @endif
                    <a href="{{ route('dashboard') }}" class="text-sm font-medium text-gray-300 hover:text-white transition">My Bookings</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm font-medium text-gray-400 hover:text-white transition">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium text-gray-300 hover:text-white transition">Sign In</a>
                    <a href="{{ route('register') }}" class="px-4 py-2 bg-amber-600 text-white text-sm font-semibold rounded-lg hover:bg-amber-700 transition">Register</a>
                @endauth
            </div>

            <div class="md:hidden">
                <button type="button" x-data="{ open: false }" @click="open = !open" class="text-gray-300 hover:text-white p-2">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div x-data="{ open: false }" x-show="open" class="md:hidden border-t border-gray-800">
        <div class="px-4 py-3 space-y-1">
            <a href="{{ route('home') }}" class="block px-3 py-2 text-sm font-medium text-gray-300 hover:text-white hover:bg-gray-800 rounded-md transition">Home</a>
            <a href="{{ route('rooms.index') }}" class="block px-3 py-2 text-sm font-medium text-gray-300 hover:text-white hover:bg-gray-800 rounded-md transition">Rooms</a>
            <a href="{{ route('services.index') }}" class="block px-3 py-2 text-sm font-medium text-gray-300 hover:text-white hover:bg-gray-800 rounded-md transition">Services</a>
            <a href="{{ route('gallery.index') }}" class="block px-3 py-2 text-sm font-medium text-gray-300 hover:text-white hover:bg-gray-800 rounded-md transition">Gallery</a>
            <a href="{{ route('contact.show') }}" class="block px-3 py-2 text-sm font-medium text-gray-300 hover:text-white hover:bg-gray-800 rounded-md transition">Contact</a>
            <div class="divider my-2"></div>
            @auth
                <a href="{{ route('dashboard') }}" class="block px-3 py-2 text-sm font-medium text-gray-300 hover:text-white hover:bg-gray-800 rounded-md transition">My Bookings</a>
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 text-sm font-medium text-gray-300 hover:text-white hover:bg-gray-800 rounded-md transition">Admin</a>
                @endif
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-3 py-2 text-sm font-medium text-gray-400 hover:text-white hover:bg-gray-800 rounded-md transition">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="block px-3 py-2 text-sm font-medium text-gray-300 hover:text-white hover:bg-gray-800 rounded-md transition">Sign In</a>
                <a href="{{ route('register') }}" class="block px-3 py-2 text-sm font-semibold text-white bg-amber-600 hover:bg-amber-700 rounded-md transition">Register</a>
            @endauth
        </div>
    </div>
</nav>