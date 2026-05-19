<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Claim Management System</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tilt.js/1.2.1/tilt.jquery.min.js"></script>

    <style>
        *{margin:0;padding:0;box-sizing:border-box;font-family:'Segoe UI',Tahoma,Verdana,sans-serif;}
        body{background:#f5f7fa;color:#333;}

        /* Loader */
        .loader-wrapper{
            height:100vh;width:100%;position:fixed;top:0;left:0;
            background:#fff;display:flex;flex-direction:column;
            align-items:center;justify-content:center;z-index:9999;
        }
        .ring{width:120px;height:120px;border-radius:50%;position:absolute;}
        .ring:nth-child(1){border-bottom:5px solid #18345d;animation:rotate1 2s linear infinite;}
        .ring:nth-child(2){border-right:5px solid #C3bE5C;animation:rotate2 2s linear infinite;}
        .ring:nth-child(3){border-top:5px solid #8689a1;animation:rotate3 2s linear infinite;}
        .loading{margin-top:150px;font-weight:bold;}

        @keyframes rotate1{0%{transform:rotateZ(0);}100%{transform:rotateZ(360deg);}}
        @keyframes rotate2{0%{transform:rotateZ(0);}100%{transform:rotateZ(360deg);}}
        @keyframes rotate3{0%{transform:rotateZ(0);}100%{transform:rotateZ(360deg);}}

        /* Layout */
        .auth-container{display:flex;min-height:100vh;width:100%;}
        .auth-container { display: flex; min-height: 100vh; width: 100%; }
        .auth-left {
            flex: 1; background: linear-gradient(135deg, #18345d 0%, #1a427e 100%);
            display: flex; flex-direction: column; justify-content: center; align-items: center;
            padding: 2rem; color: white; position: relative; overflow: hidden;
        }
        .auth-left-content{max-width:450px;z-index:2;}
          .auth-left::before {
            content: ''; position: absolute; width: 200%; height: 200%;
            background: url("data:image/svg+xml,%3Csvg width='40' height='40'..." );
            animation: float 20s infinite linear
        }
        @keyframes float{0%{transform:translate(0,0);}100%{transform:translate(-50%,-50%);}}

        .features{display:flex;justify-content:center;gap:1.2rem;margin-top:2rem;flex-wrap:wrap;}
        .feature{
            background:rgba(255,255,255,0.1);padding:1rem;border-radius:10px;width:140px;
            backdrop-filter:blur(10px);transition:.3s;text-align:center;
        }
        .feature:hover{transform:translateY(-5px);}
        .feature i{font-size:2rem;color:#C3bE5C;margin-bottom:0.5rem;}

        /* Right form */
        .auth-right{flex:1;display:flex;justify-content:center;align-items:center;padding:2rem;}

        .register-form-container{
            background:white;width:100%;max-width:480px;padding:2.2rem;border-radius:18px;
            box-shadow:0 10px 30px rgba(0,0,0,0.08);position:relative;
        }
        .register-form-container::before{
            content:'';height:4px;width:100%;position:absolute;top:0;left:0;
            background:linear-gradient(to right,#18345d,#C3bE5C);
        }

        .form-title{text-align:center;color:#18345d;font-size:1.8rem;font-weight:700;margin-bottom:0.2rem;}
        .form-subtitle{text-align:center;color:#666;margin-bottom:1.5rem;}

        .form-group{margin-bottom:1.2rem;}
        .form-label{font-size:0.9rem;font-weight:600;margin-bottom:0.4rem;display:block;}
        .form-input,.form-select,.form-textarea{
            width:100%;padding:0.75rem;border:1px solid #ddd;border-radius:8px;font-size:1rem;
            transition:.3s;
        }
        .form-input:focus,.form-select:focus,.form-textarea:focus{
            border-color:#18345d;box-shadow:0 0 0 3px rgba(24,52,93,0.1);
        }
        .error-message{color:#e53e3e;font-size:.85rem;margin-top:0.25rem;}

        .btn{
            width:100%;padding:.85rem;border:none;border-radius:8px;background:#18345d;
            color:white;font-weight:600;cursor:pointer;transition:.3s;
        }
        .btn:hover{background:#1a427e;transform:translateY(-2px);}
        .login-link{text-align:center;margin-top:1rem;font-size:.9rem;}
        .login-link a{color:#18345d;font-weight:600;}
        .checkbox-group{
    display:flex;
    flex-direction:column;
    gap:10px;
    margin-top:8px;
}

.checkbox-item{
    display:flex;
    align-items:center;
    gap:10px;
    padding:12px;
    border:1px solid #ddd;
    border-radius:8px;
    cursor:pointer;
    transition:0.3s;
    background:#fff;
}

.checkbox-item:hover{
    border-color:#18345d;
    background:#f7f9fc;
}

.checkbox-item input[type="checkbox"]{
    width:18px;
    height:18px;
    accent-color:#18345d;
}

        @media(max-width:992px){.auth-container{flex-direction:column;}.auth-left{padding:3rem 1rem;}}
 </style>
</head>

<body>

    <!-- Loader -->
    <div class="loader-wrapper">
        <div class="ring"></div>
        <div class="ring"></div>
        <div class="ring"></div>
        <span class="loading">Loading...</span>
    </div>

    <div class="auth-container">
        <!-- LEFT -->
        <div class="auth-left">
            <div class="auth-left-content">
                <img src="{{ asset('logo1.png') }}" class="h-30 w-auto mx-auto">
                <h3 class="text-2xl font-bold mt-4">Claim Management System</h3>

                <div class="features">
                    <div class="feature">
                        <i class="fas fa-file-invoice-dollar"></i>
                        <h3>Easy Claims</h3>
                        <p>Submit claims fast</p>
                    </div>
                    <div class="feature">
                        <i class="fas fa-chart-line"></i>
                        <h3>Track Progress</h3>
                        <p>Monitor in real-time</p>
                    </div>
                    <div class="feature">
                        <i class="fas fa-shield-alt"></i>
                        <h3>Secure</h3>
                        <p>Data protection</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT -->
        <div class="auth-right">
            <div class="register-form-container js-tilt">

                <h2 class="form-title">Create Account</h2>
                <p class="form-subtitle">Start submitting and tracking your claims</p>

                <!-- Error Messages -->
                @if($errors->any())
                <div class="alert alert-error" style="background:#fee;border:1px solid #f5c6cb;padding:1rem;border-radius:8px;margin-bottom:1rem;">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @if(session('status'))
                <div class="alert alert-success" style="background:#e8f5e9;border:1px solid #c3e6cb;padding:1rem;border-radius:8px;margin-bottom:1rem;">
                    {{ session('status') }}
                </div>
                @endif

                <form method="POST" action="{{ route('register') }}" id="registerForm">
                    @csrf

                    <div class="form-group">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" required class="form-input">
                        @error('name') <span class="error-message">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" value="{{ old('email') }}" required class="form-input">
                        @error('email') <span class="error-message">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Phone Number</label>
                        <input type="tel" name="phone" value="{{ old('phone') }}" required class="form-input">
                        @error('phone') <span class="error-message">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Address</label>
                        <textarea name="address" rows="3" class="form-textarea" required>{{ old('address') }}</textarea>
                        @error('address') <span class="error-message">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Account Type</label>
                        <select name="role" class="form-select" required>
                            <option value="" disabled selected>Select account type</option>
                            <option value="user" {{ old('role')=='user'?'selected':'' }}>CEO/Manager</option>
                            <option value="user" {{ old('role')=='user'?'selected':'' }}>Supervisor</option>
                            <option value="admin" {{ old('role')=='admin'?'selected':'' }}>HR Manager</option>
                            <option value="user" {{ old('role')=='user'?'selected':'' }}>Regional Manager</option>
                        </select>
                        @error('role') <span class="error-message">{{ $message }}</span> @enderror
                    </div>

                   <div class="form-group">
    <label class="form-label">SMS MobileStore</label>

    <div class="checkbox-group">

        <label class="checkbox-item">
            <input type="checkbox" name="stores[]" value="Hemmingways"
                {{ is_array(old('stores')) && in_array('Hemmingways', old('stores')) ? 'checked' : '' }}>
            <span>Hemmingways</span>
        </label>

        <label class="checkbox-item">
            <input type="checkbox" name="stores[]" value="Stone Towers"
                {{ is_array(old('stores')) && in_array('Stone Towers', old('stores')) ? 'checked' : '' }}>
            <span>Stone Towers</span>
        </label>

        <label class="checkbox-item">
            <input type="checkbox" name="stores[]" value="Beacon Bay"
                {{ is_array(old('stores')) && in_array('Beacon Bay', old('stores')) ? 'checked' : '' }}>
            <span>Beacon Bay</span>
        </label>

        <label class="checkbox-item">
            <input type="checkbox" name="stores[]" value="Paytcn Vincent"
                {{ is_array(old('stores')) && in_array('Paytcn Vincent', old('stores')) ? 'checked' : '' }}>
            <span>Paytcn Vincent</span>
        </label>

    </div>

    @error('stores')
        <span class="error-message">{{ $message }}</span>
    @enderror
</div>
                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" required class="form-input">
                        @error('password') <span class="error-message">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" required class="form-input">
                    </div>

                    <button type="submit" id="btnSubmit" class="btn">
                        <i class="fas fa-user-plus"></i> Create Account
                    </button>
                </form>

                <div class="login-link">
                    Already have an account?  
                    <a href="{{ route('login') }}">Sign in</a>
                </div>

            </div>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            setTimeout(()=>{$(".loader-wrapper").fadeOut("slow");},1200);

            $("#registerForm").submit(function(){
                $("#btnSubmit").html('<i class="fas fa-spinner fa-spin"></i> Creating...');
                $("#btnSubmit").prop("disabled",true);
            });
        });
    </script>

</body>
</html>
