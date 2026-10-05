<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisteredUserController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'guest',
        ]);

        // Retro-link: guest-checkout bookings made with this email now
        // belong to the new account, so history is preserved.
        \App\Models\Booking::whereNull('user_id')
            ->where('guest_email', strtolower($user->email))
            ->update(['user_id' => $user->id]);

        $linked = \App\Models\Booking::where('user_id', $user->id)->count();

        Auth::login($user);

        \App\Support\ActivityLogger::log(
            'auth.register',
            $user,
            ['linked_bookings' => $linked],
            $user->name.' created an account'.($linked ? " ({$linked} past booking(s) linked)" : '')
        );

        return redirect()->route('dashboard.index');
    }
}
