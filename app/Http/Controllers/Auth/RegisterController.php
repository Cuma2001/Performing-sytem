<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Departments as Department;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class RegisterUserController extends Controller
{
    /**
     * Show the registration form.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $date = Carbon::now();
        return view('auth.RegisterUser')->with('date', $date);
    }

    public function showRegistrationForm()
    {
        // Fetch all departments from the database
        $departments = Department::all();

        // Pass the departments to the view
        return view('auth.register')->with('dep', $departments);
    }
    
    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
       $validator = Validator::make($request->all(), [
            'title' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'id_no' => ['required', 'string', 'max:13','min:13','unique:users'],
            'gender' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users', function ($attribute, $value, $fail) {
                if (!str_ends_with($value, '@ictchoice.com') && !str_ends_with($value, '@ictchoice.co.za')) {
                    $fail('The '.$attribute.' must end with @ictchoice.com or @ictchoice.co.za');
                }
            }],
            'phone' => ['required', 'string','min:10', 'max:10','unique:users'],
            'communication'=> ['required', 'string', 'max:255'],
            'department' => ['required', 'string', 'max:255'],
            'job_title' =>['required', 'string', 'max:255'],
            'role' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }
        if (str_starts_with($request->phone, '0')){
            $phone = "+27".substr($request->phone,1);
        }else{
            return redirect()->back()->withErrors('Ooops! mobile number must start with 0')->withInput();
        }

        $validated = $validator->validated();
        $user = User::create([
            'title' => $request->title,
            'name' =>  ucfirst(strtolower($request->name)),
            'password' => Hash::make(Str::random(12)),
            'surname' =>  ucfirst(strtolower($request->surname)),
            'id_no' => $request->id_no,
            'gender' => $request->gender,
            'email' => $request->email,
            'phone' => $phone,
            'communication'=> $request->communication,
            'department' => $request->department,
            'job_title' => $request->job_title,
            'role' => $request->role,
            'location' => $request->location,
        ]);

        Auth::login($user);

        return redirect()->route('dashboard');
    }

}

