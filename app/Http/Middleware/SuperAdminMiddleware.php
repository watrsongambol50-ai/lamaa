<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Restricts a route to the full 'admin' role only — staff accounts are blocked.
 * Use for sensitive areas staff should never reach: staff management, service/price
 * catalog, coupons, packages, and financial reports.
 */
class SuperAdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'هذا القسم متاح فقط لمدير النظام الرئيسي.');
        }
        return $next($request);
    }
}
