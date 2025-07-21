<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AuditLog;

class AdminLogoutOnPublic
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Jika user sedang login dan mengakses halaman public (bukan admin)
        if (Auth::check() && !$request->is('admin/*') && !$request->is('api/*')) {
            $user = Auth::user();
            
            // Log automatic logout
            if ($user) {
                try {
                    AuditLog::log('auto_logout_on_public', $user, null, [
                        'reason' => 'User accessed public page while logged in as admin',
                        'url' => $request->fullUrl()
                    ]);
                } catch (\Exception $e) {
                    // Log error but don't break the process
                    \Log::warning('Failed to log auto logout: ' . $e->getMessage());
                }
                
                // Revoke all tokens for the user
                try {
                    $user->tokens()->delete();
                } catch (\Exception $e) {
                    \Log::warning('Failed to delete user tokens during auto logout: ' . $e->getMessage());
                }
            }
            
            // Logout user admin
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            $request->session()->forget('admin_token');
            $request->session()->forget('user_data');
        }

        return $next($request);
    }
}
