<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminRedirectMiddleware
{
    /**
     * Handle an incoming request.
     * Redirect admin users to admin dashboard when accessing public pages
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Jika user sedang login sebagai admin dan mengakses halaman public
        if (Auth::check() && !$request->is('admin/*') && !$request->is('api/*')) {
            $user = Auth::user();
            
            // Cek apakah user adalah admin
            if ($user && $user->isAdmin()) {
                // Redirect ke admin dashboard dengan pesan
                return redirect()->route('admin.dashboard')
                    ->with('info', 'Anda sudah login sebagai admin. Akses halaman admin untuk mengelola sistem.');
            }
        }

        return $next($request);
    }
}
