<x-layouts.app>
    @section('title', 'Our Services')
    @section('meta_description', 'Explore the world-class services and amenities offered at our hotel')

    <div class="bg-gray-900 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl font-display font-bold text-white">Our Services</h1>
            <p class="mt-4 text-lg text-gray-300">World-class amenities at your fingertips</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        @if($services->count())
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($services as $service)
                    <div class="card p-6 hover:shadow-lg transition">
                        @if($service->image)
                            <img src="{{ image_url($service->image) }}" alt="{{ $service->name }}" class="w-full h-40 object-cover rounded-lg">
                        @else
                            <div class="w-full h-40 bg-gradient-to-br from-amber-100 to-amber-50 rounded-lg flex items-center justify-center">
                                <svg class="w-14 h-14 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z" /></svg>
                            </div>
                        @endif
                        <div class="flex items-center justify-between mt-4">
                            <h2 class="text-xl font-display font-semibold text-gray-900">{{ $service->name }}</h2>
                            @if($service->price)
                                <span class="badge-success">&#8358;{{ number_format($service->price, 0) }}</span>
                            @endif
                        </div>
                        @if($service->category)
                            <p class="text-sm text-amber-600 mt-1">{{ $service->category }}</p>
                        @endif
                        <p class="mt-3 text-gray-600">{{ $service->description }}</p>
                    </div>
                @endforeach
            </div>
            <div class="mt-8">{{ $services->links() }}</div>
        @else
            <div class="text-center py-12 text-gray-500"><p>Services coming soon.</p></div>
        @endif
    </div>
</x-layouts.app>