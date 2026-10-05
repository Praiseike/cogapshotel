<x-layouts.app>
    @section('title', $room->name)
    @section('meta_description', Str::limit($room->description, 160))

    <div class="page-hero">
        @if($room->getPrimaryImage())
            <img src="{{ image_url($room->getPrimaryImage()) }}" alt="{{ $room->name }}" class="absolute inset-0 w-full h-full object-cover opacity-35">
        @endif
        <div class="absolute inset-0 bg-gradient-to-b from-ink-950/75 via-ink-950/50 to-ink-950/90"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-20">
            <div class="flex items-center gap-3 text-[11px] uppercase tracking-[0.24em] text-cream-50/55 mb-6">
                <a href="{{ route('home') }}" class="hover:text-brass-300">Home</a>
                <span class="text-brass-500">/</span>
                <a href="{{ route('rooms.index') }}" class="hover:text-brass-300">Rooms</a>
                <span class="text-brass-500">/</span>
                <span class="text-cream-50">{{ $room->name }}</span>
            </div>
            <p class="eyebrow-light">The {{ $room->category->name }} Collection</p>
            <h1 class="mt-3 font-display text-5xl md:text-6xl text-cream-50">{{ $room->name }}</h1>
            <div class="mt-6 flex items-center gap-4 text-cream-50/70 text-[13px] uppercase tracking-[0.2em]">
                <span>{{ $room->capacity }} Guests</span>
                <span class="text-brass-400">✦</span>
                <span>{{ $room->bed_type ?? 'Classic Bed' }}</span>
                <span class="text-brass-400">✦</span>
                <span class="font-display text-xl normal-case tracking-normal text-brass-200">&#8358;{{ number_format($room->price_per_night, 0) }} / night</span>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            <div class="lg:col-span-2">
                @if($room->images && count($room->images) > 0)
                    <div class="grid grid-cols-2 gap-3">
                        @foreach($room->images as $i => $img)
                            <div class="{{ $i === 0 ? 'col-span-2' : '' }} overflow-hidden group">
                                <img src="{{ image_url($img) }}" alt="{{ $room->name }}" class="w-full {{ $i === 0 ? 'h-[440px]' : 'h-56' }} object-cover group-hover:scale-[1.02] transition duration-700">
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="mt-12">
                    <p class="eyebrow">About This Room</p>
                    <h2 class="mt-2 font-display text-4xl text-ink-900">Description</h2>
                    <span class="mt-4 block h-px w-16 bg-brass-500"></span>
                    <p class="mt-6 font-light text-[17px] leading-[1.9] text-ink-900/70">{{ $room->description }}</p>
                </div>

                @if($room->amenities)
                    <div class="mt-12">
                        <p class="eyebrow">Comforts Provided</p>
                        <h2 class="mt-2 font-display text-4xl text-ink-900">Amenities</h2>
                        <span class="mt-4 block h-px w-16 bg-brass-500"></span>
                        <div class="mt-7 grid grid-cols-2 sm:grid-cols-3 gap-px bg-ink-900/10 border border-ink-900/10">
                            @foreach($room->amenities as $amenity)
                                <div class="bg-white px-5 py-4 flex items-center gap-3 text-[15px] font-light text-ink-900/80">
                                    <span class="text-brass-600">—</span>{{ $amenity }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="mt-12 grid grid-cols-3 gap-px bg-ink-900/10 border border-ink-900/10 text-center">
                    <div class="bg-white px-4 py-7">
                        <p class="text-[10px] uppercase tracking-[0.26em] text-ink-900/50">Occupancy</p>
                        <p class="mt-2 font-display text-2xl text-ink-900">{{ $room->capacity }} Guests</p>
                    </div>
                    <div class="bg-white px-4 py-7">
                        <p class="text-[10px] uppercase tracking-[0.26em] text-ink-900/50">Bedding</p>
                        <p class="mt-2 font-display text-2xl text-ink-900">{{ $room->bed_type ?? 'Classic' }}</p>
                    </div>
                    <div class="bg-ink-950 px-4 py-7">
                        <p class="text-[10px] uppercase tracking-[0.26em] text-brass-300">Per Night</p>
                        <p class="mt-2 font-display text-2xl text-cream-50">&#8358;{{ number_format($room->price_per_night, 0) }}</p>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="border border-ink-900/15 bg-white shadow-[0_30px_60px_-30px_rgba(20,17,13,0.4)] sticky top-28" x-data="bookingForm()">
                    <div class="h-1 bg-gradient-to-r from-brass-700 via-brass-400 to-brass-700"></div>
                    <div class="p-8">
                        <p class="eyebrow text-center">Reservations</p>
                        <h3 class="mt-2 font-display text-3xl text-ink-900 text-center">Book This Room</h3>
                        <div class="gold-rule mt-4"><span class="text-brass-500 text-[10px]">✦</span></div>

                        @if(!auth()->check())
                            <p class="mt-5 text-sm font-light text-center text-ink-900/60">
                                No account needed — confirmation goes to your email. Have an account? <a href="{{ route('login') }}" class="text-brass-700 underline underline-offset-4">Sign in</a>.
                            </p>
                        @endif

                        @php($roomUnavailable = !$room->is_available || $room->status !== 'available')
                        @if($roomUnavailable)
                            <div class="mt-5 rounded-sm border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-900">
                                This room is currently unavailable and cannot be reserved.
                            </div>
                        @endif

                        {{-- Live availability status --}}
                        <div x-show="checkIn && checkOut && !roomUnavailable" x-cloak class="mt-5 rounded-sm border px-4 py-3 text-sm" :class="availabilityClass">
                            <span x-text="availabilityText"></span>
                        </div>
                        <div x-show="disabledDates.length" class="mt-3 text-[11px] text-ink-900/50">Unavailable dates are automatically blocked in the calendar.</div>

                        <form method="POST" action="{{ route('booking.store') }}" class="mt-6" @submit="if((!isAvailable && checkIn && checkOut) || roomUnavailable || checking || checkFailed) { $event.preventDefault(); }">
                            @csrf
                            <input type="hidden" name="room_id" value="{{ $room->id }}">

                            <fieldset {{ $roomUnavailable ? 'disabled' : '' }} class="contents">
                            <div class="space-y-5">
                                <div>
                                    <label class="input-label">Check-in</label>
                                    <input type="date" name="check_in" x-model="checkIn" @change="onDatesChange()" @input="onDatesChange()" min="{{ date('Y-m-d') }}" :max="checkOut ? prevDay(checkOut) : ''" required
                                           class="input-field {{ $errors->has('check_in') ? 'input-error' : '' }}" :class="dateError && 'input-error'">
                                    @error('check_in') <p class="mt-1 text-xs text-red-700">{{ $message }}</p> @enderror
                                    <p x-show="dateError" x-text="dateError" class="mt-1 text-xs text-red-700"></p>
                                </div>

                                <div>
                                    <label class="input-label">Check-out</label>
                                    <input type="date" name="check_out" x-model="checkOut" @change="onDatesChange()" @input="onDatesChange()" :min="checkIn ? nextDay(checkIn) : '{{ date('Y-m-d', strtotime('+1 day')) }}'" required
                                           class="input-field {{ $errors->has('check_out') ? 'input-error' : '' }}" :class="dateError && 'input-error'">
                                    @error('check_out') <p class="mt-1 text-xs text-red-700">{{ $message }}</p> @enderror
                                </div>

                                @guest
                                    <div>
                                        <label class="input-label">Full name</label>
                                        <input type="text" name="guest_name" value="{{ old('guest_name') }}" required autocomplete="name"
                                               class="input-field {{ $errors->has('guest_name') ? 'input-error' : '' }}">
                                        @error('guest_name') <p class="mt-1 text-xs text-red-700">{{ $message }}</p> @enderror
                                    </div>

                                    <div>
                                        <label class="input-label">Email (for confirmation + receipt)</label>
                                        <input type="email" name="guest_email" value="{{ old('guest_email') }}" required autocomplete="email"
                                               class="input-field {{ $errors->has('guest_email') ? 'input-error' : '' }}">
                                        @error('guest_email') <p class="mt-1 text-xs text-red-700">{{ $message }}</p> @enderror
                                    </div>

                                    <div>
                                        <label class="input-label">Phone (optional)</label>
                                        <input type="tel" name="guest_phone" value="{{ old('guest_phone') }}" autocomplete="tel"
                                               class="input-field">
                                    </div>
                                @endguest

                                <div>
                                    <label class="input-label">Guests</label>
                                    <select name="guests_count" class="input-field">
                                        @for($i = 1; $i <= $room->capacity; $i++)
                                            <option value="{{ $i }}">{{ $i }} {{ $i === 1 ? 'Guest' : 'Guests' }}</option>
                                        @endfor
                                    </select>
                                </div>

                                <div>
                                    <label class="input-label">How did you hear about us? <span class="font-normal normal-case tracking-normal text-ink-900/40">(optional)</span></label>
                                    <select name="source" class="input-field">
                                        <option value="">Prefer not to say</option>
                                        @foreach(\App\Models\Booking::SOURCES as $key => $label)
                                            <option value="{{ $key }}" {{ old('source') === $key ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="border-t border-ink-900/10 pt-5">
                                    <div class="flex items-center justify-between text-sm font-light text-ink-900/60 mb-2">
                                        <span>&#8358;{{ number_format($room->price_per_night, 0) }} × <span x-text="quote.nights ?? nights"></span> nights</span>
                                        <span x-text="quote ? '&#8358;' + Number(quote.subtotal).toLocaleString() : '&#8358;' + total.toLocaleString()"></span>
                                    </div>
                                    <div class="flex items-center justify-between text-ink-900">
                                        <span class="text-[11px] uppercase tracking-[0.24em]">Total</span>
                                        <span x-text="quote ? '&#8358;' + Number(quote.total).toLocaleString() : '&#8358;' + total.toLocaleString()" class="font-display text-3xl"></span>
                                    </div>
                                    <p x-show="quote && !quote.available" class="mt-2 text-xs text-red-700">Selected dates are not available.</p>
                                </div>

                                <button type="submit" class="btn-dark w-full disabled:cursor-not-allowed disabled:opacity-50" :disabled="!canBook" {{ $roomUnavailable ? 'disabled' : '' }}>
                                    @if($roomUnavailable)
                                        <span>Unavailable</span>
                                    @else
                                        <span x-text="canBook ? 'Reserve Now' : (availabilityText || 'Reserve Now')"></span>
                                    @endif
                                </button>
                                <p class="text-center text-xs font-light text-ink-900/45">Secure payment via Paystack · Instant confirmation</p>

                                @php($waUrlRoom = whatsapp_url(\App\Models\Setting::getValue('hotel_whatsapp', \App\Models\Setting::getValue('hotel_phone')), "Hello, I'm interested in booking {$room->name}." ))
                                @if($waUrlRoom)
                                <a href="{{ $waUrlRoom }}" target="_blank" rel="noopener" class="flex items-center justify-center gap-2 border border-[#25D366] text-[#25D366] px-6 py-3 text-[11px] uppercase tracking-[0.22em] hover:bg-[#25D366] hover:text-white transition">
                                    Enquire on WhatsApp
                                </a>
                                @endif
                            </div>
                            </fieldset>
                        </form>
                        <div class="mt-6 border-t border-ink-900/10 pt-5">
                            <p class="text-[11px] uppercase tracking-[0.22em] text-ink-900/50">Hotel Policies</p>
                            <ul class="mt-2 space-y-1 text-sm font-light text-ink-900/60">
                                <li>Check-in 2PM · Check-out 12PM</li>
                                <li>Free cancellation before check-in</li>
                                <li><a href="{{ route('policies') }}" class="text-brass-700 underline underline-offset-4">View full policies →</a></li>
                            </ul>
                        </div>
                    </div>
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
                roomId: {{ $room->id }},
                roomUnavailable: {{ $roomUnavailable ? 'true' : 'false' }},
                checking: false,
                checkFailed: false,
                disabledDates: [],
                availabilityText: '',
                isAvailable: null,
                dateError: '',
                quote: null,
                availabilityClass: 'border-ink-900/10 bg-cream-100 text-ink-900',
                init() {
                    fetch(`/api/rooms/${this.roomId}/booked-dates`)
                        .then(r => r.json())
                        .then(d => { this.disabledDates = d.disabled_dates || []; })
                        .catch(() => {});
                    // Pre-fill from query string if present
                    const params = new URLSearchParams(window.location.search);
                    if (params.get('check_in')) this.checkIn = params.get('check_in');
                    if (params.get('check_out')) this.checkOut = params.get('check_out');
                    if (this.checkIn && this.checkOut) this.onDatesChange();
                },
                get nights() {
                    if (!this.checkIn || !this.checkOut) return 0;
                    const diff = (new Date(this.checkOut) - new Date(this.checkIn)) / (1000 * 60 * 60 * 24);
                    return diff > 0 ? diff : 0;
                },
                get total() {
                    return this.nights * this.pricePerNight;
                },
                get canBook() {
                    if (this.roomUnavailable) return false;
                    // Fail closed: while verifying, or if verification failed,
                    // don't let the user submit overlapping dates.
                    if (this.checking || this.checkFailed) return false;
                    if (!this.checkIn || !this.checkOut) return true;
                    if (this.dateError) return false;
                    if (this.isAvailable === false) return false;
                    if (this.quote && !this.quote.available) return false;
                    return this.nights > 0;
                },
                nextDay(dateStr) {
                    const d = new Date(dateStr);
                    d.setDate(d.getDate() + 1);
                    return d.toISOString().slice(0,10);
                },
                prevDay(dateStr) {
                    const d = new Date(dateStr);
                    d.setDate(d.getDate() - 1);
                    return d.toISOString().slice(0,10);
                },
                onDatesChange() {
                    this.dateError = '';
                    this.availabilityText = '';
                    this.isAvailable = null;
                    this.quote = null;
                    this.checkFailed = false;
                    this.availabilityClass = 'border-ink-900/10 bg-cream-100 text-ink-900';

                    if (!this.checkIn || !this.checkOut) return;

                    // Client-side disabled dates check
                    const inRange = (dateStr) => this.disabledDates.includes(dateStr);
                    if (inRange(this.checkIn) || inRange(this.checkOut)) {
                        this.dateError = 'One of the selected dates is unavailable. Please choose different dates.';
                        this.availabilityText = 'Unavailable';
                        this.isAvailable = false;
                        this.availabilityClass = 'border-red-200 bg-red-50 text-red-900';
                        return;
                    }

                    // Check overlapping range locally
                    let d = new Date(this.checkIn);
                    const end = new Date(this.checkOut);
                    while (d < end) {
                        const s = d.toISOString().slice(0,10);
                        if (inRange(s) && s !== this.checkOut) {
                            this.dateError = 'Your stay overlaps booked dates. Please adjust.';
                            this.availabilityText = 'Not available for these dates';
                            this.isAvailable = false;
                            this.availabilityClass = 'border-red-200 bg-red-50 text-red-900';
                            return;
                        }
                        d.setDate(d.getDate()+1);
                    }

                    if (new Date(this.checkOut) <= new Date(this.checkIn)) {
                        this.dateError = 'Check-out must be after check-in.';
                        return;
                    }

                    // Fetch live quote + availability
                    this.availabilityText = 'Checking availability…';
                    this.checking = true;
                    fetch(`/api/rooms/${this.roomId}/quote?check_in=${this.checkIn}&check_out=${this.checkOut}`)
                        .then(r => r.json())
                        .then(data => {
                            this.checking = false;
                            this.quote = data;
                            this.isAvailable = data.available;
                            if (data.available) {
                                this.availabilityText = `Available · ${data.nights} night(s) · ₦${Number(data.total).toLocaleString()} total`;
                                this.availabilityClass = 'border-emerald-200 bg-emerald-50 text-emerald-900';
                            } else {
                                this.availabilityText = 'Not available for these dates';
                                this.availabilityClass = 'border-red-200 bg-red-50 text-red-900';
                            }
                        })
                        .catch(() => {
                            this.checking = false;
                            this.checkFailed = true;
                            this.availabilityText = 'Could not verify availability — please check your connection and pick the dates again.';
                            this.availabilityClass = 'border-red-200 bg-red-50 text-red-900';
                        });
                }
            }
        }
    </script>
    @endpush
</x-layouts.app>
