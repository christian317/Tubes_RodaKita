<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticateRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $userRole = Auth::user()->id_role;

        if (!in_array($userRole, $roles)) {
            abort(403, 'AKSES DITOLAK!');
        }

        return $next($request);
    }
}