<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfNotAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * This middleware redirects unauthenticated users to the login page,
     * but stores the intended URL so they are redirected back after logging in.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // If user is not authenticated, redirect to login
        if (!auth()->check()) {
            // Store the intended URL in the session
            session()->put('url.intended', $request->url());

            return redirect()
                ->route('login')
                ->with('info', 'Please login or create an account to use this feature.');
        }

        return $next($request);
    }
}
