<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="index, follow">

    {{-- SEO --}}
    @hasSection('title')
        <x-seo :title="trim($__env->yieldContent('title'))" :description="trim($__env->yieldContent('meta_description'))" />
    @else
        <x-seo />
    @endif

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=jost:300,400,500,600|cormorant-garamond:400,500,600,700|playfair-display:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="font-sans antialiased bg-cream-100 text-ink-900">
    <div class="min-h-screen flex flex-col">
        <x-navbar />

        <main class="flex-1">
            @if(session('success'))
                <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 6000)" x-show="show"
                     x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                     class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
                    <div class="border border-brass-600/30 bg-brass-50 px-5 py-4 flex items-start gap-3">
                        <span class="mt-0.5 inline-block h-2 w-2 rotate-45 bg-brass-600 shrink-0"></span>
                        <p class="text-sm tracking-wide text-ink-900">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 6000)" x-show="show"
                     x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                     class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
                    <div class="border border-red-900/25 bg-red-50 px-5 py-4">
                        <p class="text-sm tracking-wide text-red-900">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            {{ $slot }}
        </main>

        <x-footer />
        <x-whatsapp-button />
        <x-cookie-consent />
    </div>

    @stack('scripts')
</body>
</html>
