<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Sign In') - {{ config('app.name', 'Hotel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=jost:300,400,500,600|cormorant-garamond:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-ink-950">
    <div class="min-h-screen grid lg:grid-cols-2">
        {{-- Left — image panel --}}
        <div class="relative hidden lg:block overflow-hidden">
            <img src="https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?w=1400&q=80" alt="Grand hotel lobby" class="absolute inset-0 w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-ink-950 via-ink-950/55 to-ink-950/25"></div>
            <div class="absolute inset-8 border border-cream-50/20 pointer-events-none"></div>
            <div class="relative h-full flex flex-col justify-between p-14">
                <a href="{{ route('home') }}" class="flex items-center gap-4">
                    <span class="flex h-12 w-12 items-center justify-center border border-brass-400/70 outline outline-1 outline-offset-4 outline-brass-400/30 font-display text-xl text-brass-300">
                        {{ strtoupper(substr(config('app.name', 'H'), 0, 1)) }}
                    </span>
                    <span class="leading-tight">
                        <span class="block font-display text-2xl tracking-wide text-cream-50">{{ config('app.name', 'Hotel') }}</span>
                        <span class="block text-[10px] uppercase tracking-[0.34em] text-brass-300 mt-1">Est. of Quiet Luxury</span>
                    </span>
                </a>
                <div>
                    <p class="font-display text-4xl leading-tight text-cream-50 italic">“Arrive as a guest,<br>leave as family.”</p>
                    <div class="mt-6 flex items-center gap-3">
                        <span class="h-px w-12 bg-brass-400"></span>
                        <span class="text-[11px] uppercase tracking-[0.3em] text-cream-50/60">The House Standard</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right — form panel --}}
        <div class="relative bg-cream-100 flex flex-col justify-center px-6 py-12 sm:px-16">
            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-brass-700 via-brass-400 to-brass-700"></div>
            <div class="mx-auto w-full max-w-md">
                <a href="{{ route('home') }}" class="lg:hidden flex items-center justify-center gap-3 mb-8">
                    <span class="flex h-10 w-10 items-center justify-center border border-brass-600/60 font-display text-lg text-brass-700">{{ strtoupper(substr(config('app.name', 'H'), 0, 1)) }}</span>
                    <span class="font-display text-xl text-ink-900">{{ config('app.name', 'Hotel') }}</span>
                </a>

                @if(session('success'))
                    <div class="mb-5 border border-brass-600/30 bg-brass-50 px-4 py-3 text-sm text-ink-900">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="mb-5 border border-red-900/25 bg-red-50 px-4 py-3 text-sm text-red-900">{{ session('error') }}</div>
                @endif

                <div class="bg-white border border-ink-900/10 shadow-[0_30px_70px_-30px_rgba(20,17,13,0.35)]">
                    <div class="h-1 bg-ink-900"></div>
                    <div class="p-8 sm:p-10">
                        {{ $slot }}
                    </div>
                </div>

                <p class="mt-8 text-center text-[11px] uppercase tracking-[0.24em] text-ink-900/45">
                    &copy; {{ date('Y') }} {{ config('app.name', 'Hotel') }} — All Rights Reserved
                </p>
            </div>
        </div>
    </div>
</body>
</html>
