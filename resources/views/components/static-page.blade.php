<x-layouts.app>
    @section('title', $title)
    @section('meta_description', $meta ?? '')

    <div class="bg-gray-900 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-display font-bold text-white">{{ $title }}</h1>
        </div>
    </div>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="page-content">
            {{ $slot }}
        </div>
    </div>
</x-layouts.app>