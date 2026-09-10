<x-layouts.guest>
    @section('title', 'Sign In')

    <div class="card p-8">
        <div class="text-center mb-6">
            <h1 class="text-2xl font-display font-bold text-gray-900">Welcome Back</h1>
            <p class="mt-2 text-sm text-gray-600">Sign in to your account to continue</p>
        </div>

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                       class="input-field mt-1 {{ $errors->has('email') ? 'input-error' : '' }}">
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" id="password" name="password" required
                       class="input-field mt-1 {{ $errors->has('password') ? 'input-error' : '' }}">
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="remember" class="rounded border-gray-300 text-amber-600 focus:ring-amber-500">
                    <span class="text-sm text-gray-600">Remember me</span>
                </label>
                <a href="{{ route('password.request') }}" class="text-sm text-amber-600 hover:text-amber-700">Forgot password?</a>
            </div>

            <button type="submit" class="btn-primary w-full">Sign In</button>
        </form>

        <p class="mt-6 text-center text-sm text-gray-600">
            Don't have an account?
            <a href="{{ route('register') }}" class="text-amber-600 hover:text-amber-700 font-medium">Create one</a>
        </p>
    </div>
</x-layouts.guest>