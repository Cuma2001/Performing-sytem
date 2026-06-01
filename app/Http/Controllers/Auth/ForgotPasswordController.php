<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\ForgotPasswordMail;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.forgotPassword');
    }

    public function sendResetLinkEmail(Request $request)
    {
        // Validate email
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Generate token
        $token = Str::random(64);
        $email = $request->email;
        $userId = User::where('email', $email)->value('id');

        // Save token to DB
        DB::table('password_resets')->updateOrInsert(
            ['email' => $email],
            ['token' => $token, 'created_at' => Carbon::now()]
        );

        // Send reset email
        $link = route('password.reset.form', ['id' => $userId, 'token' => $token]);
        Mail::to($email)->send(new ForgotPasswordMail($link));

        return back()->with('success', 'A password reset link has been sent to your email.');
    }

    public function showResetForm($id, $token)
    {
        $resetRecord = DB::table('password_resets')->where('token', $token)->first();

        if (!$resetRecord) {
            return redirect()->route('password.request')->withErrors('Invalid or expired token.');
        }

        $createdAt = Carbon::parse($resetRecord->created_at);
        if (Carbon::now()->diffInMinutes($createdAt) > 10) {
            return redirect()->route('password.request')->withErrors('Token expired. Please request a new one.');
        }

        return view('auth.forgotPassword', [
            'email' => $resetRecord->email,
            'token' => $token
        ]);
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', 'exists:users,email'],
            'password' => [
                'required', 'confirmed', 'min:8',
                'regex:/[a-z]/', 'regex:/[A-Z]/', 
                'regex:/[0-9]/', 'regex:/[@$!%*#?&]/'
            ],
            'token' => ['required'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = User::where('email', $request->email)->first();
        $user->password = bcrypt($request->password);
        $user->save();

        DB::table('password_resets')->where('email', $request->email)->delete();

        return redirect()->route('login')->with('success', 'Your password has been reset. You may now log in.');
    }
}
