<x-layouts.app>
    <div class="relative bg-gray-900">
        <div class="absolute inset-0">
            <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?w=1920&q=80" alt="Hotel" class="w-full h-full object-cover opacity-40">
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-32 md:py-48">
            <div class="max-w-2xl">
                <h1 class="text-4xl md:text-6xl font-display font-bold text-white leading-tight">
                    Experience <span class="text-amber-400">Luxury</span> Like Never Before
                </h1>
                <p class="mt-6 text-lg text-gray-300 max-w-lg">
                    Indulge in world-class hospitality, breathtaking views, and unforgettable moments at the finest hotel destination.
                </p>

                <div class="mt-10 flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('rooms.index') }}" class="btn-primary text-lg px-8 py-4">
                        Book Now
                    </a>
                    <a href="{{ route('services.index') }}" class="inline-flex items-center justify-center px-8 py-4 bg-white/10 text-white font-medium rounded-lg border border-white/20 hover:bg-white/20 transition text-lg backdrop-blur-sm">
                        Our Services
                    </a>
                </div>
            </div>
        </div>
    </div>

    <section class="py-16 md:py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="section-title">Why Choose Us</h2>
                <p class="section-subtitle max-w-2xl mx-auto mt-4">We provide an exceptional experience that goes beyond just a place to stay</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-12">
                <div class="card p-8 text-center">
                    <div class="w-14 h-14 bg-amber-100 rounded-xl flex items-center justify-center mx-auto">
                        <svg class="w-7 h-7 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z" />
                        </svg>
                    </div>
                    <h3 class="mt-6 text-xl font-display font-semibold text-gray-900">Luxurious Rooms</h3>
                    <p class="mt-3 text-gray-600">Elegantly designed rooms with premium amenities and stunning views for the perfect stay.</p>
                </div>

                <div class="card p-8 text-center">
                    <div class="w-14 h-14 bg-amber-100 rounded-xl flex items-center justify-center mx-auto">
                        <svg class="w-7 h-7 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.455 2.456L21.75 6l-1.036.259a3.375 3.375 0 00-2.455 2.456z" />
                        </svg>
                    </div>
                    <h3 class="mt-6 text-xl font-display font-semibold text-gray-900">World-Class Services</h3>
                    <p class="mt-3 text-gray-600">From spa treatments to fine dining, we offer premium services tailored to your needs.</p>
                </div>

                <div class="card p-8 text-center">
                    <div class="w-14 h-14 bg-amber-100 rounded-xl flex items-center justify-center mx-auto">
                        <svg class="w-7 h-7 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                        </svg>
                    </div>
                    <h3 class="mt-6 text-xl font-display font-semibold text-gray-900">Secure & Easy Booking</h3>
                    <p class="mt-3 text-gray-600">Book your stay effortlessly with our secure online reservation system powered by Paystack.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-16 md:py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="section-title">A Place Where Memories Are Made</h2>
                    <p class="mt-4 text-gray-600 text-lg">
                        Whether you are here for business or leisure, our hotel provides the perfect blend of comfort, elegance, and modern convenience.
                    </p>
                    <div class="mt-8 space-y-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center">
                                <svg class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                            </div>
                            <span class="text-gray-700">24/7 Concierge Service</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center">
                                <svg class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                            </div>
                            <span class="text-gray-700">Complimentary Wi-Fi</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center">
                                <svg class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                            </div>
                            <span class="text-gray-700">Restaurant & Bar</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center">
                                <svg class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                            </div>
                            <span class="text-gray-700">Spa & Wellness Center</span>
                        </div>
                    </div>
                    <div class="mt-8">
                        <a href="{{ route('rooms.index') }}" class="btn-primary">Explore Rooms</a>
                    </div>
                </div>

                <div class="relative">
                    <img src="https://images.unsplash.com/photo-1582719508461-905c673771fd?w=800&q=80" alt="Hotel Interior" class="rounded-2xl shadow-2xl w-full">
                    <div class="absolute -bottom-6 -left-6 bg-amber-600 text-white rounded-xl p-6 shadow-xl hidden md:block">
                        <p class="text-3xl font-bold">10+</p>
                        <p class="text-sm text-amber-100">Years of Excellence</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-16 md:py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="section-title">Ready to Experience the Difference?</h2>
            <p class="section-subtitle max-w-2xl mx-auto mt-4">Book your stay today and discover why guests choose us time and time again.</p>
            <div class="mt-8">
                <a href="{{ route('rooms.index') }}" class="btn-primary text-lg px-8 py-4">Make a Reservation</a>
            </div>
        </div>
    </section>
</x-layouts.app>
