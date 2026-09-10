<x-layouts.guest>
    @section('title', 'Forgot Password')

    <div class="card p-8">
        <div class="text-center mb-6">
            <h1 class="text-2xl font-display font-bold text-gray-900">Reset Password</h1>
            <p class="mt-2 text-sm text-gray-600">Enter your email to receive a reset link</p>
        </div>

        @if (session('status'))
            <div class="mb-4 rounded-lg bg-emerald-50 p-4 text-emerald-700 text-sm">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                       class="input-field mt-1 {{ $errors->has('email') ? 'input-error' : '' }}">
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="btn-primary w-full">Send Reset Link</button>
        </form>

        <p class="mt-6 text-center text-sm text-gray-600">
            <a href="{{ route('login') }}" class="text-amber-600 hover:text-amber-700 font-medium">Back to Sign In</a>
        </p>
    </div>
</x-layouts.guest>