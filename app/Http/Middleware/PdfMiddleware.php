<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PdfMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && (auth()->user()->isKasir() || auth()->user()->isAdmin() || auth()->user()->isManajer())) {
            return $next($request);
        }

        return redirect('/error-unauthorized');
    }
}
