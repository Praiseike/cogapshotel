<x-layouts.app>
    @section('title', 'Contact Us')

    <div class="bg-gray-900 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl font-display font-bold text-white">Contact Us</h1>
            <p class="mt-4 text-lg text-gray-300">We would love to hear from you</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="max-w-2xl mx-auto">
            <form method="POST" action="{{ route('contact.store') }}" class="card p-8 space-y-6">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required
                               class="input-field mt-1 {{ $errors->has('name') ? 'input-error' : '' }}">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required
                               class="input-field mt-1 {{ $errors->has('email') ? 'input-error' : '' }}">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700">Phone (optional)</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                           class="input-field mt-1">
                </div>

                <div>
                    <label for="subject" class="block text-sm font-medium text-gray-700">Subject</label>
                    <input type="text" id="subject" name="subject" value="{{ old('subject') }}" required
                           class="input-field mt-1 {{ $errors->has('subject') ? 'input-error' : '' }}">
                    @error('subject')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="message" class="block text-sm font-medium text-gray-700">Message</label>
                    <textarea id="message" name="message" rows="5" required
                              class="input-field mt-1 {{ $errors->has('message') ? 'input-error' : '' }}">{{ old('message') }}</textarea>
                    @error('message')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="btn-primary w-full">Send Message</button>
            </form>
        </div>
    </div>
</x-layouts.app>