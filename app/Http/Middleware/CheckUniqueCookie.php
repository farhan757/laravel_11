<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUniqueCookie
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $cookieName = 'req_cookie'; // Change this to your desired cookie name
        $ip = $request->ip();

        if (!$request->cookie($cookieName)) {
            // Set the cookie if it doesn't exist
            return $next($request)->cookie($cookieName, $ip);
        }

        // Check if the IP stored in the cookie matches the current IP
        if ($request->cookie($cookieName) !== $ip) {
            // Handle the case where the IP does not match (e.g., log out the user)
            return redirect('/proseslogout'); // Redirect to the logout route or any other action you want
        }

        return $next($request);
    }
}
