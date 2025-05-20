<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, $role)
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        $user = Auth::user();

        if ($role == 'admin' && !$user->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        if ($role == 'seller' && !($user->isSeller() || $user->isAdmin())) {
            abort(403, 'Unauthorized action.');
        }

        if ($role == 'buyer' && !($user->isBuyer() || $user->isAdmin())) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
