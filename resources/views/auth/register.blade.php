<x-layouts.guest>
    @section('title', 'Create Account')

    <div class="text-center">
        <p class="eyebrow">Join the House</p>
        <h1 class="mt-2 font-display text-4xl text-ink-900">Create Account</h1>
        <div class="gold-rule mt-4"><span class="text-brass-500 text-[10px]">✦</span></div>
        <p class="mt-3 text-sm font-light text-ink-900/55">Become a guest of the house</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="mt-8 space-y-5">
        @csrf

        <div>
            <label for="name" class="input-label">Full Name</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus
                   class="input-field {{ $errors->has('name') ? 'input-error' : '' }}">
            @error('name')
                <p class="mt-1 text-sm text-red-700">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="email" class="input-label">Email Address</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required
                   class="input-field {{ $errors->has('email') ? 'input-error' : '' }}">
            @error('email')
                <p class="mt-1 text-sm text-red-700">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="password" class="input-label">Password</label>
                <input type="password" id="password" name="password" required
                       class="input-field {{ $errors->has('password') ? 'input-error' : '' }}">
                @error('password')
                    <p class="mt-1 text-sm text-red-700">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="input-label">Confirm</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required
                       class="input-field">
            </div>
        </div>

        <button type="submit" class="btn-dark w-full">Create Account</button>
    </form>

    <div class="mt-7 pt-6 border-t border-ink-900/10 text-center text-sm font-light text-ink-900/60">
        Already a guest?
        <a href="{{ route('login') }}" class="text-brass-700 font-normal underline underline-offset-4 hover:text-ink-900">Sign in</a>
    </div>
</x-layouts.guest>
