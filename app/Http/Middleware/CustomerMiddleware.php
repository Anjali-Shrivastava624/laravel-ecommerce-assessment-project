<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CustomerMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check() || !Auth::user()->isCustomer()) {
            return redirect()->route('customer.login')->with('error', 'Access denied. Customer login required.');
        }

        return $next($request);
    }
}
