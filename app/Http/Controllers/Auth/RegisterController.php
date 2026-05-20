<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class RegisterController extends Controller
{
    /**
     * Show registration form
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Handle registration request
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'name' => ['required', 'string', 'max:255'],

            'id_no' => ['required', 'digits:13', 'unique:users,id_no'],

            'email' => ['required', 'email', 'max:255', 'unique:users,email'],

            'phone' => ['required', 'digits:10', 'unique:users,phone'],

            'role' => [
                'required',
                Rule::in([
                    'CEO/Manager',
                    'Supervisor',
                    'HR',
                    'Regional Manager'
                ])
            ],

            'store' => [
                'required',
                Rule::in([
                    'Hemmingways',
                    'Stone Towers',
                    'Beacon Bay',
                    'Metlife Kiosk',
                    'Patycn Centre'
                ])
            ],

            'password' => ['required', 'min:6', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Convert phone to +27 format
        if (str_starts_with($request->phone, '0')) {
            $phone = "+27" . substr($request->phone, 1);
        } else {
            return redirect()->back()
                ->withErrors(['phone' => 'Mobile number must start with 0'])
                ->withInput();
        }

        // Create user
        $user = User::create([
            'name' => ucwords(strtolower($request->name)),
            'id_no' => $request->id_no,
            'email' => strtolower($request->email),
            'phone' => $phone,
            'role' => $request->role,
            'store' => $request->store,
            'password' => Hash::make($request->password),
        ]);

        // Auto login
        Auth::login($user);

        return redirect()->route('dashboard')
            ->with('success', 'User registered successfully.');
    }
}