<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Asset Management</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-pM6VH8bYKgY2L59Q1+ZBzYgdW7ed1C7vD2v+bFJdv0RyxX90bj20EkuFkxLRu9O5Y1u4rQR3w2NQagdbZE+Qig==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        body{margin:0;font-family:'Figtree',sans-serif;background:#f3f5f9;color:#1f2937;}
        .page-wrapper{min-height:100vh;display:flex;align-items:center;justify-content:center;padding:2rem;}
        .login-card{width:100%;max-width:420px;background:#ffffff;border-radius:24px;box-shadow:0 25px 60px rgba(15, 23, 42, 0.12);overflow:hidden;}
        .login-card-header{padding:2rem 2rem 1.5rem;background:linear-gradient(135deg,#172554,#1e40af);color:#ffffff;text-align:center;}
        .login-card-header h1{margin:0;font-size:1.9rem;letter-spacing:.02em;}
        .login-card-header p{margin:.75rem 0 0;font-size:.96rem;opacity:.9;}
        .login-card-body{padding:2rem;}
        .form-group{margin-bottom:1.25rem;}
        .form-label{display:block;font-weight:600;margin-bottom:.55rem;color:#0f172a;}
        .form-input{width:100%;padding:.95rem 1rem;border:1px solid #d1d5db;border-radius:14px;font-size:1rem;background:#f8fafc;color:#0f172a;transition:.2s;border-color:#d1d5db;}
        .form-input:focus{outline:none;border-color:#2563eb;box-shadow:0 0 0 3px rgba(37,99,235,.12);}
        .input-wrapper{position:relative;}
        .field-icon{position:absolute;top:50%;right:1rem;transform:translateY(-50%);color:#64748b;font-size:1rem;}
        .btn-submit{width:100%;padding:1rem;border:none;border-radius:14px;background:#1e40af;color:#ffffff;font-weight:700;cursor:pointer;transition:.2s;display:inline-flex;align-items:center;justify-content:center;gap:.5rem;}
        .btn-submit:hover{background:#1d4ed8;}
        .help-links{display:flex;justify-content:space-between;margin-top:1rem;font-size:.95rem;flex-wrap:wrap;gap:.75rem;}
        .help-links a{color:#1e40af;text-decoration:none;}
        .alert{padding:1rem 1rem;border-radius:14px;margin-bottom:1rem;font-size:.95rem;line-height:1.4;}
        .alert-danger{background:#fee2e2;color:#b91c1c;border:1px solid #fecaca;}
        .alert-success{background:#dcfce7;color:#166534;border:1px solid #86efac;}
    </style>
</head>
<body>
    <div class="page-wrapper">
        <div class="login-card">
            <div class="login-card-header">
                <h1>Performance Management Login</h1>
                <p>Sign in to access your performance dashboard.</p>
            </div>
            <div class="login-card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul style="margin:0;padding-left:1.2rem;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if ($message = Session::get('success'))
                    <div class="alert alert-success">{{ $message }}</div>
                @endif

                <form id="loginForm" method="POST" action="{{ route('loginStore') }}">
                    @csrf
                    <div class="form-group">
                        <label class="form-label" for="email">Email</label>
                        <div class="input-wrapper">
                            <input id="email" class="form-input" type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" required autofocus>
                            <span class="field-icon"><i class="fa fa-envelope"></i></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="password">Password</label>
                        <div class="input-wrapper">
                            <input id="password" class="form-input" type="password" name="password" placeholder="********" required>
                            <span class="field-icon"><i class="fa fa-lock"></i></span>
                        </div>
                    </div>
                    <button type="submit" id="btnSubmit" class="btn-submit">
                        <i class="fa fa-sign-in-alt"></i>
                        Login
                    </button>
                    <div class="help-links">
                        <a href="{{ route('password.request') }}">Forgot Password?</a>
                        <a href="{{ route('register') }}">Register</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('loginForm')?.addEventListener('submit', function () {
            var btn = document.getElementById('btnSubmit');
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Loading...';
            }
        });
    </script>
</body>
</html>
