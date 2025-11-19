<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Admin
{
    public function handle(Request $request, Closure $next)
{
    if (!Auth::check()) return redirect()->route('auth.login');

    if (Auth::user()->role !== 'admin') {
        return redirect()->route('auth.login')->with('error', 'Access denied!');
    }

    return $next($request);
}
}