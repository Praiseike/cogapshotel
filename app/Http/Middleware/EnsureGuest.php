<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureGuest
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $home = auth()->user()->role === 'admin'
                ? route('admin.dashboard')
                : route('dashboard.index');

            return redirect()->intended($home);
        }

        return $next($request);
    }
}
