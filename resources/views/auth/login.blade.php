<x-layouts.guest>
    @section('title', 'Sign In')

    <div class="text-center">
        <p class="eyebrow">Welcome Back</p>
        <h1 class="mt-2 font-display text-4xl text-ink-900">Sign In</h1>
        <div class="gold-rule mt-4"><span class="text-brass-500 text-[10px]">✦</span></div>
        <p class="mt-3 text-sm font-light text-ink-900/55">Enter your credentials to continue</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-5">
        @csrf

        <div>
            <label for="email" class="input-label">Email Address</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                   class="input-field {{ $errors->has('email') ? 'input-error' : '' }}">
            @error('email')
                <p class="mt-1 text-sm text-red-700">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="input-label">Password</label>
            <input type="password" id="password" name="password" required
                   class="input-field {{ $errors->has('password') ? 'input-error' : '' }}">
            @error('password')
                <p class="mt-1 text-sm text-red-700">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-between text-sm">
            <label class="flex items-center gap-2 font-light text-ink-900/60">
                <input type="checkbox" name="remember" class="border-ink-900/30 text-brass-700 focus:ring-brass-600">
                Remember me
            </label>
            <a href="{{ route('password.request') }}" class="text-brass-700 hover:text-ink-900 underline underline-offset-4 transition">Forgot password?</a>
        </div>

        <button type="submit" class="btn-dark w-full">Sign In</button>
    </form>

    <div class="mt-7 pt-6 border-t border-ink-900/10 text-center text-sm font-light text-ink-900/60">
        New to the house?
        <a href="{{ route('register') }}" class="text-brass-700 font-normal underline underline-offset-4 hover:text-ink-900">Create an account</a>
    </div>
</x-layouts.guest>
