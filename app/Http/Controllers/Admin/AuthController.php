<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\AuditLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLoginForm(): View
    {
        return view('admin.auth.login');
    }

    /**
     * Handle login
     */
    public function login(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            // Check if user is admin
            if (!$user->isAdmin()) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Akses ditolak. Hanya admin yang bisa login.',
                ]);
            }

            $request->session()->regenerate();
            
            // Generate API token for admin panel usage
            $token = $user->createToken('admin-panel')->plainTextToken;
            
            // Log login action
            AuditLog::log('login', $user);
            
            // Store token in session for JavaScript access
            session([
                'admin_token' => $token,
                'user_data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role
                ]
            ]);

            return redirect()->intended(route('admin.dashboard'))
                ->with('success', 'Selamat datang, ' . $user->name);
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    /**
     * Handle logout
     */
    public function logout(Request $request): RedirectResponse
    {
        $user = Auth::user();
        
        // Log logout action
        if ($user) {
            AuditLog::log('logout', $user);
        }
        
        // Revoke all tokens for the user (with safety check for SQLite)
        if ($user) {
            try {
                $user->tokens()->delete();
            } catch (\Exception $e) {
                // Log error but don't break logout process
                \Log::warning('Failed to delete user tokens during logout: ' . $e->getMessage());
            }
        }
        
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        // Remove admin token from session
        $request->session()->forget('admin_token');

        return redirect()->route('admin.login')
            ->with('success', 'Anda telah logout.');
    }

    /**
     * Extend session
     */
    public function extendSession(Request $request)
    {
        if (Auth::check()) {
            $request->session()->regenerate();
            return response()->json(['status' => 'success', 'message' => 'Session extended']);
        }
        
        return response()->json(['status' => 'error', 'message' => 'Not authenticated'], 401);
    }
}
