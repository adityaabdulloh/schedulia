<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RoleMiddleware
{
    public function handle($request, Closure $next, $role)
    {
        Log::info('RoleMiddleware: Checking access.', [
            'user_authenticated' => Auth::check(),
            'user_role' => Auth::check() ? Auth::user()->role : 'not_authenticated',
            'required_role' => $role,
            'request_path' => $request->path(),
        ]);

        if (Auth::check() && Auth::user()->role == $role) {
            return $next($request);
        }

        return redirect('/unauthorized'); // Atau tampilkan pesan "403 Forbidden"
    }
}
