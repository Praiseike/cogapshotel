@php
    $waUrl = whatsapp_url(\App\Models\Setting::getValue('hotel_whatsapp', \App\Models\Setting::getValue('hotel_phone')), \App\Models\Setting::getValue('hotel_whatsapp_message'));
    $hotelPhone = \App\Models\Setting::getValue('hotel_phone', '+234 800 555 0134');
    $hotelEmail = \App\Models\Setting::getValue('hotel_email', 'reservations@hotel.com');
    $hotelAddress = \App\Models\Setting::getValue('hotel_address', '12 Independence Avenue, Victoria Island, Lagos');
@endphp
<x-layouts.app>
    @section('title', 'Contact Us')

    <div class="page-hero">
        <img src="https://images.unsplash.com/photo-1564501049412-61c2a3083791?w=1800&q=80" alt="Contact" class="absolute inset-0 w-full h-full object-cover opacity-25">
        <div class="absolute inset-0 bg-gradient-to-b from-ink-950/70 via-ink-950/40 to-ink-950/85"></div>
        <div class="absolute inset-5 border border-cream-50/12 pointer-events-none"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 md:py-24 text-center">
            <p class="eyebrow-light">✦ &nbsp; Write to the House &nbsp; ✦</p>
            <h1 class="mt-4 font-display text-5xl md:text-6xl text-cream-50">Contact <span class="italic text-brass-200">Us</span></h1>
            <div class="gold-rule mt-6"><span class="text-brass-300 text-xs">✦</span></div>
            <p class="mt-5 font-light text-cream-50/70">We reply to every letter, usually within the day — or reach us instantly on WhatsApp</p>
            @if($waUrl)
            <a href="{{ $waUrl }}" target="_blank" rel="noopener" class="mt-6 inline-flex items-center gap-3 bg-[#25D366] px-8 py-3.5 text-[11px] uppercase tracking-[0.24em] text-white hover:bg-[#1ebe59] transition">
                Chat on WhatsApp
            </a>
            @endif
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
        <div class="grid lg:grid-cols-3 gap-8 mb-14">
            <div class="card p-8 text-center">
                <p class="text-[11px] uppercase tracking-[0.28em] text-brass-700">Visit Us</p>
                <p class="mt-3 font-display text-lg text-ink-900 leading-relaxed">{{ $hotelAddress }}</p>
            </div>
            <div class="card p-8 text-center">
                <p class="text-[11px] uppercase tracking-[0.28em] text-brass-700">Call / WhatsApp</p>
                <p class="mt-3 font-display text-lg text-ink-900">{{ $hotelPhone }}</p>
                @if($waUrl)
                <a href="{{ $waUrl }}" target="_blank" rel="noopener" class="mt-3 inline-flex text-[11px] uppercase tracking-[0.24em] text-[#25D366] hover:underline">Open WhatsApp →</a>
                @endif
            </div>
            <div class="card p-8 text-center">
                <p class="text-[11px] uppercase tracking-[0.28em] text-brass-700">Email</p>
                <p class="mt-3 font-display text-lg text-ink-900 break-words">{{ $hotelEmail }}</p>
            </div>
        </div>

        <div class="grid lg:grid-cols-5 gap-8">
            <div class="lg:col-span-3">
            <div class="border border-ink-900/12 bg-white shadow-[0_30px_60px_-30px_rgba(20,17,13,0.4)]">
                <div class="h-1 bg-gradient-to-r from-brass-700 via-brass-400 to-brass-700"></div>
                <form method="POST" action="{{ route('contact.store') }}" class="p-8 md:p-10 space-y-6">
                    @csrf
                    <div class="text-center">
                        <p class="eyebrow">Correspondence</p>
                        <h2 class="mt-1 font-display text-3xl text-ink-900">Send a Message</h2>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label for="name" class="input-label">Name</label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                   class="input-field {{ $errors->has('name') ? 'input-error' : '' }}">
                            @error('name')<p class="mt-1 text-sm text-red-700">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="email" class="input-label">Email</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                   class="input-field {{ $errors->has('email') ? 'input-error' : '' }}">
                            @error('email')<p class="mt-1 text-sm text-red-700">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div>
                        <label for="phone" class="input-label">Phone (optional)</label>
                        <input type="text" id="phone" name="phone" value="{{ old('phone') }}" class="input-field">
                    </div>

                    <div>
                        <label for="subject" class="input-label">Subject</label>
                        <input type="text" id="subject" name="subject" value="{{ old('subject') }}" required
                               class="input-field {{ $errors->has('subject') ? 'input-error' : '' }}">
                        @error('subject')<p class="mt-1 text-sm text-red-700">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="message" class="input-label">Message</label>
                        <textarea id="message" name="message" rows="5" required
                                  class="input-field {{ $errors->has('message') ? 'input-error' : '' }}">{{ old('message') }}</textarea>
                        @error('message')<p class="mt-1 text-sm text-red-700">{{ $message }}</p>@enderror
                    </div>

                    <button type="submit" class="btn-dark w-full">Send Message</button>
                </form>
            </div>
            </div>
            <div class="lg:col-span-2 space-y-6">
                <div class="card overflow-hidden">
                    <div class="h-1 bg-gradient-to-r from-brass-700 via-brass-400 to-brass-700"></div>
                    <div class="p-6">
                        <p class="eyebrow">Find Us</p>
                        <h3 class="mt-1 font-display text-2xl text-ink-900">Location</h3>
                        <p class="mt-3 text-sm font-light text-ink-900/60 leading-relaxed">{{ $hotelAddress }}</p>
                        <div class="mt-6 aspect-[4/3] bg-cream-200 border border-ink-900/10 overflow-hidden">
                            <iframe
                                src="https://maps.google.com/maps?q={{ urlencode($hotelAddress) }}&z=15&output=embed"
                                width="100%" height="100%" style="border:0;" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                        <a href="https://maps.google.com/?q={{ urlencode($hotelAddress) }}" target="_blank" rel="noopener" class="mt-4 inline-flex text-[11px] uppercase tracking-[0.24em] text-brass-700 hover:text-ink-900">Open in Google Maps →</a>
                    </div>
                </div>
                <div class="card p-6 bg-ink-950 text-cream-50">
                    <p class="text-[11px] uppercase tracking-[0.32em] text-brass-300">Hotel Policies</p>
                    <ul class="mt-4 space-y-3 text-sm font-light text-cream-50/70">
                        <li><span class="text-brass-400">—</span> Check-in 2:00 PM · Check-out 12:00 PM</li>
                        <li><span class="text-brass-400">—</span> Free cancellation before check-in date</li>
                        <li><span class="text-brass-400">—</span> Valid ID required at check-in</li>
                        <li><span class="text-brass-400">—</span> <a href="{{ route('policies') }}" class="underline decoration-brass-400 underline-offset-4 hover:text-cream-50">View full policies</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
