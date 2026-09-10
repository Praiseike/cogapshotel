<x-layouts.app>
    @section('title', $room->name)
    @section('meta_description', Str::limit($room->description, 160))

    <div class="bg-gray-900 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-2 text-sm text-gray-400 mb-4">
                <a href="{{ route('home') }}" class="hover:text-white">Home</a>
                <span>/</span>
                <a href="{{ route('rooms.index') }}" class="hover:text-white">Rooms</a>
                <span>/</span>
                <span class="text-white">{{ $room->name }}</span>
            </div>
            <h1 class="text-4xl font-display font-bold text-white">{{ $room->name }}</h1>
            <p class="mt-2 text-lg text-gray-300">{{ $room->category->name }}</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-8">
                @if($room->images && count($room->images) > 0)
                    <div class="grid grid-cols-2 gap-4">
                        @foreach($room->images as $i => $img)
                            <div class="{{ $i === 0 ? 'col-span-2' : '' }}">
                                <img src="{{ image_url($img) }}" alt="{{ $room->name }}" class="w-full {{ $i === 0 ? 'h-96' : 'h-48' }} object-cover rounded-xl">
                            </div>
                        @endforeach
                    </div>
                @endif

                <div>
                    <h2 class="text-2xl font-display font-bold text-gray-900 mb-4">About This Room</h2>
                    <p class="text-gray-600 leading-relaxed">{{ $room->description }}</p>
                </div>

                @if($room->amenities)
                    <div>
                        <h2 class="text-2xl font-display font-bold text-gray-900 mb-4">Amenities</h2>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                            @foreach($room->amenities as $amenity)
                                <div class="flex items-center gap-2 text-gray-700">
                                    <svg class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                    {{ $amenity }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="grid grid-cols-3 gap-4">
                    <div class="card p-4 text-center">
                        <p class="text-sm text-gray-500">Capacity</p>
                        <p class="text-lg font-bold text-gray-900">{{ $room->capacity }} Guests</p>
                    </div>
                    <div class="card p-4 text-center">
                        <p class="text-sm text-gray-500">Bed Type</p>
                        <p class="text-lg font-bold text-gray-900">{{ $room->bed_type ?? 'Standard' }}</p>
                    </div>
                    <div class="card p-4 text-center">
                        <p class="text-sm text-gray-500">Price</p>
                        <p class="text-lg font-bold text-amber-600">&#8358;{{ number_format($room->price_per_night, 0) }}</p>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="card p-6 sticky top-24" x-data="bookingForm()">
                    <h3 class="text-xl font-display font-bold text-gray-900 mb-6">Book This Room</h3>

                    @if(!auth()->check())
                        <p class="text-sm text-gray-600 mb-4">
                            <a href="{{ route('login') }}" class="text-amber-600 hover:text-amber-700 font-medium">Sign in</a> to make a reservation.
                        </p>
                    @endif

                    <form method="POST" action="{{ route('booking.store') }}">
                        @csrf
                        <input type="hidden" name="room_id" value="{{ $room->id }}">

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Check-in</label>
                                <input type="date" name="check_in" x-model="checkIn" min="{{ date('Y-m-d') }}" required
                                       class="input-field {{ $errors->has('check_in') ? 'input-error' : '' }}">
                                @error('check_in') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Check-out</label>
                                <input type="date" name="check_out" x-model="checkOut" :min="checkIn || '{{ date('Y-m-d', strtotime('+1 day')) }}'" required
                                       class="input-field {{ $errors->has('check_out') ? 'input-error' : '' }}">
                                @error('check_out') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Guests</label>
                                <select name="guests_count" class="input-field">
                                    @for($i = 1; $i <= $room->capacity; $i++)
                                        <option value="{{ $i }}">{{ $i }} {{ $i === 1 ? 'Guest' : 'Guests' }}</option>
                                    @endfor
                                </select>
                            </div>

                            <div class="border-t pt-4 mt-4">
                                <div class="flex items-center justify-between text-sm text-gray-600 mb-2">
                                    <span>&#8358;{{ number_format($room->price_per_night, 0) }} x <span x-text="nights || 0"></span> nights</span>
                                    <span x-text="'&#8358;' + total.toLocaleString()"></span>
                                </div>
                                <div class="flex items-center justify-between font-bold text-gray-900 text-lg">
                                    <span>Total</span>
                                    <span x-text="'&#8358;' + total.toLocaleString()" class="text-amber-600"></span>
                                </div>
                            </div>

                            <button type="submit" class="btn-primary w-full" {{ !auth()->check() ? 'disabled' : '' }}>
                                {{ auth()->check() ? 'Reserve Now' : 'Sign in to Book' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function bookingForm() {
            return {
                checkIn: '',
                checkOut: '',
                pricePerNight: {{ $room->price_per_night }},
                get nights() {
                    if (!this.checkIn || !this.checkOut) return 0;
                    const diff = (new Date(this.checkOut) - new Date(this.checkIn)) / (1000 * 60 * 60 * 24);
                    return diff > 0 ? diff : 0;
                },
                get total() {
                    return this.nights * this.pricePerNight;
                }
            }
        }
    </script>
    @endpush
</x-layouts.app>