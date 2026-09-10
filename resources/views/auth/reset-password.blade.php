<x-layouts.guest>
    @section('title', 'Reset Password')

    <div class="card p-8">
        <div class="text-center mb-6">
            <h1 class="text-2xl font-display font-bold text-gray-900">Set New Password</h1>
        </div>

        <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email', $email) }}" required
                       class="input-field mt-1 {{ $errors->has('email') ? 'input-error' : '' }}">
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">New Password</label>
                <input type="password" id="password" name="password" required
                       class="input-field mt-1 {{ $errors->has('password') ? 'input-error' : '' }}">
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required
                       class="input-field mt-1">
            </div>

            <button type="submit" class="btn-primary w-full">Reset Password</button>
        </form>
    </div>
</x-layouts.guest>