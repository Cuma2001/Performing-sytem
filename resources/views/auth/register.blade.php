<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Register User</title>

    @include('layouts.head')

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    {{-- YOUR CUSTOM STYLE --}}
    <style>
        body{
            margin:0;
            font-family:'Figtree',sans-serif;
            background:#f3f5f9;
            color:#1f2937;
        }

        .page-wrapper{
            min-height:100vh;
            display:flex;
            align-items:center;
            justify-content:center;
            padding:2rem;
        }

        .login-card{
            width:100%;
            max-width:520px;
            background:#ffffff;
            border-radius:24px;
            box-shadow:0 25px 60px rgba(15, 23, 42, 0.12);
            overflow:hidden;
        }

        .login-card-header{
            padding:2rem;
            background:linear-gradient(135deg,#172554,#1e40af);
            color:#fff;
            text-align:center;
        }

        .login-card-header h1{
            margin:0;
            font-size:1.8rem;
        }

        .login-card-header p{
            margin:.5rem 0 0;
            opacity:.9;
        }

        .login-card-body{
            padding:2rem;
        }

        .form-group{
            margin-bottom:1.2rem;
        }

        .form-label{
            display:block;
            font-weight:600;
            margin-bottom:.5rem;
        }

        .form-input{
            width:100%;
            padding:.9rem 1rem;
            border:1px solid #d1d5db;
            border-radius:14px;
            font-size:1rem;
            background:#f8fafc;
            outline:none;
        }

        .form-input:focus{
            border-color:#2563eb;
            box-shadow:0 0 0 3px rgba(37,99,235,.12);
        }

        .btn-submit{
            width:100%;
            padding:1rem;
            border:none;
            border-radius:14px;
            background:#1e40af;
            color:#fff;
            font-weight:700;
            cursor:pointer;
        }

        .btn-submit:hover{
            background:#1d4ed8;
        }

        .help-links{
            margin-top:1rem;
            text-align:center;
        }

        .help-links a{
            color:#1e40af;
            text-decoration:none;
        }

        .alert{
            padding:1rem;
            border-radius:14px;
            margin-bottom:1rem;
        }

        .alert-danger{
            background:#fee2e2;
            color:#b91c1c;
        }

        .alert-success{
            background:#dcfce7;
            color:#166534;
        }
    </style>
</head>

<body>

<div class="page-wrapper">

    <div class="login-card">

        {{-- HEADER --}}
        <div class="login-card-header">
            <h1>Register User</h1>
            <p>Create a new account</p>
        </div>

        {{-- BODY --}}
        <div class="login-card-body">

            {{-- ERRORS --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul style="margin:0; padding-left:18px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- SUCCESS --}}
            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    {{ $message }}
                </div>
            @endif

            <form action="{{ route('register.store') }}" method="POST">
                @csrf

                {{-- FULL NAME --}}
                <div class="form-group">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-input"
                        value="{{ old('name') }}" required>
                </div>

                {{-- ID NUMBER --}}
                <div class="form-group">
                    <label class="form-label">ID Number</label>
                    <input type="text" name="id_no" class="form-input"
                        maxlength="13" pattern="[0-9]{13}"
                        value="{{ old('id_no') }}" required>
                </div>

                {{-- EMAIL --}}
                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-input"
                        value="{{ old('email') }}" required>
                </div>

                {{-- PHONE --}}
                <div class="form-group">
                    <label class="form-label">Phone Number</label>
                    <input type="text" name="phone" class="form-input"
                        maxlength="10" pattern="[0-9]{10}"
                        value="{{ old('phone') }}" required>
                </div>

                {{-- ROLE --}}
                <div class="form-group">
                    <label class="form-label">Role</label>
                    <select name="role" class="form-input" required>
                        <option value="">Select Role</option>
                        <option value="CEO/Manager">CEO/Manager</option>
                        <option value="Supervisor">Supervisor</option>
                        <option value="HR">HR</option>
                        <option value="Regional Manager">Regional Manager</option>
                    </select>
                </div>

                {{-- STORE --}}
                <div class="form-group">
                    <label class="form-label">Store</label>
                    <select name="store" class="form-input" required>
                        <option value="">Select Store</option>
                        <option value="Hemmingways">Hemmingways</option>
                        <option value="Stone Towers">Stone Towers</option>
                        <option value="Beacon Bay">Beacon Bay</option>
                        <option value="Metlife Kiosk">Metlife Kiosk</option>
                        <option value="Patycn Centre">Patycn Centre</option>
                    </select>
                </div>

                {{-- PASSWORD --}}
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-input" required>
                </div>

                {{-- CONFIRM PASSWORD --}}
                <div class="form-group">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-input" required>
                </div>

                {{-- SUBMIT --}}
                <button type="submit" class="btn-submit">
                    Register
                </button>

                <div class="help-links">
                    <a href="{{ route('login') }}">Already have an account? Login</a>
                </div>

            </form>

        </div>
    </div>

</div>

{{-- OPTIONAL SCRIPT (ID logic removed because gender field does not exist) --}}
<script>
    function isNumber(n) {
        return !isNaN(parseFloat(n)) && isFinite(n);
    }
</script>

</body>
</html>