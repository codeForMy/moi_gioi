<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!isAdmin()) {
            return redirect()->route('login');
        }

        return $next($request);
    }
}
