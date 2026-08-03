<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Show login form
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        // Rate limiting (by IP)
        $key = 'login:' . $request->ip();

        if (!RateLimiter::remaining($key, 5)) {
            throw ValidationException::withMessages([
                'email' => ['Too many login attempts. Please try again later.'],
            ]);
        }

        RateLimiter::hit($key, 60);

        // Validate input
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        // Attempt login
        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        // Regenerate session (security)
        $request->session()->regenerate();

        $user = Auth::user();

        // Redirect based on role
        return $this->redirectByRole($user);
    }

    /**
     * Role-based redirect
     */
    private function redirectByRole($user)
    {
        $roleName = $user->role ?? optional(
            $user->role_id ? \Illuminate\Support\Facades\DB::table('roles')->find($user->role_id) : null
        )->name;

        $normalizedRole = strtolower(trim((string) $roleName));

        switch ($normalizedRole) {
            case 'ceo/hr':
            case 'ceo':
            case 'hr':
            case 'superadmin':
                return redirect()->route('dashboard.ceo-hr');

            case 'supervisor':
                return redirect()->route('dashboard.supervisor');

            case 'salesperson':
                return redirect()->route('dashboard.salesperson');

            default:
                return redirect()->route('dashboard');
        }
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}