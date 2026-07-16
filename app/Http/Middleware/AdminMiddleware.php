<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || !in_array(auth()->user()->role, ['admin', 'staff'])) {
            return redirect()->route('dashboard')->with('error', 'غير مصرح لك بالدخول');
        }
        return $next($request);
    }
}
