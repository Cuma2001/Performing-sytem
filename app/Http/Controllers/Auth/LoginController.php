<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\OTPVerification;


class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $key = 'login:' . $request->ip();
    
        if (!RateLimiter::remaining($key, 1)) {
            throw ValidationException::withMessages([
                'email' => ['Too many login attempts. Please try again later.'],
            ]);
        }
    
        RateLimiter::hit($key, 60); // Increase the count, lockout for 60 seconds
    
        // Validate and try login
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);
    
        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }
    
        $request->session()->regenerate();
        
        $user = Auth::user();
        $user->generateOneTimePin();
        $user->save();

        Mail::to($user->email)->send(new \App\Mail\OTPVerification($user->one_time_pin));

        return redirect()->route('otp.verify')->with('message', 'An OTP has been sent to your email.');
    }

    public function verify()
    {
        return view('auth.verify');
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    public function verifyOTP(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $user = User::where('one_time_pin', $request->otp)->where('otp_expires_at', '>', now())->first();

        if (!$user) {
            return back()->withErrors(['otp' => 'Invalid or expired OTP.']);
        }

        Auth::login($user);

        $user->one_time_pin = null;
        $user->otp_expires_at = null;
        $user->save();

        return redirect()->intended(route('dashboard'));
    }

    public function resendOTP(Request $request)
    {
        $user = User::where('email', Auth::user()->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'User not found']);
        }

        $user->generateOneTimePin(); // Ensure this method exists in your User model
        $user->save();

        Mail::to($user->email)->send(new \App\Mail\OTPVerification($user->one_time_pin));

        return redirect()->route('otp.verify')->with('message', 'A new OTP has been sent to your email.');
    }
}

 