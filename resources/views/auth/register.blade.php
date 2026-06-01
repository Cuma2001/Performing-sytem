<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Register - Performance App</title>

    <!-- Bootstrap -->
    <link rel="stylesheet"
        href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}">

    <!-- Font Awesome -->
    <link rel="stylesheet"
        href="{{ asset('assets/fonts/font-awesome-4.7.0/css/font-awesome.min.css') }}">

    <!-- Animate -->
    <link rel="stylesheet"
        href="{{ asset('assets/vendor/animate/animate.css') }}">

    <!-- Hamburgers -->
    <link rel="stylesheet"
        href="{{ asset('assets/vendor/css-hamburgers/hamburgers.min.css') }}">

    <!-- Select2 -->
    <link rel="stylesheet"
        href="{{ asset('assets/vendor/select2/select2.min.css') }}">

    <!-- Main CSS -->
    <link rel="stylesheet"
        href="{{ asset('assets/css/util.css') }}">

    <link rel="stylesheet"
        href="{{ asset('assets/css/main.css') }}">

    <style>

        body{
            background:#f5f5f5;
        }

        /* =========================
           Loader
        ========================== */

        .loader-wrapper{
            width:100%;
            height:100%;
            position:fixed;
            top:0;
            left:0;
            background:#fff;
            display:flex;
            justify-content:center;
            align-items:center;
            z-index:9999;
        }

        .ring{
            width:200px;
            height:200px;
            border:0px solid transparent;
            border-radius:50%;
            position:absolute;
        }

        .ring:nth-child(1){
            border-bottom-width:8px;
            border-color:#1d6988;
            animation:rotate1 2s linear infinite;
        }

        .ring:nth-child(2){
            border-right-width:8px;
            border-color:#f4c610;
            animation:rotate2 2s linear infinite;
        }

        .ring:nth-child(3){
            border-top-width:8px;
            border-color:#e5222b;
            animation:rotate3 2s linear infinite;
        }

        .loading{
            font-weight:600;
            color:#000;
        }

        @keyframes rotate1{
            0%{
                transform:rotateX(35deg) rotateY(-45deg) rotateZ(0deg);
            }
            100%{
                transform:rotateX(35deg) rotateY(-45deg) rotateZ(360deg);
            }
        }

        @keyframes rotate2{
            0%{
                transform:rotateX(50deg) rotateY(10deg) rotateZ(0deg);
            }
            100%{
                transform:rotateX(50deg) rotateY(10deg) rotateZ(360deg);
            }
        }

        @keyframes rotate3{
            0%{
                transform:rotateX(35deg) rotateY(55deg) rotateZ(0deg);
            }
            100%{
                transform:rotateX(35deg) rotateY(55deg) rotateZ(360deg);
            }
        }

        /* =========================
           Form Inputs
        ========================== */

        .wrap-input100{
            width:100%;
            position:relative;
            margin-bottom:18px;
        }

        .input100,
        .wrap-input100 select{

            width:100%;
            height:50px;
            border:1px solid #ccc;
            border-radius:25px;
            background:#fff;
            outline:none;
            padding-left:60px;
            padding-right:20px;
            font-size:14px;
            color:#333;

        }

        .input100:focus,
        .wrap-input100 select:focus{
            border:1px solid #1d6988;
        }

        .symbol-input100{

            position:absolute;
            left:0;
            top:0;
            width:60px;
            height:50px;

            display:flex;
            justify-content:center;
            align-items:center;

            color:#666;
            font-size:16px;

            pointer-events:none;

        }

        textarea{
            resize:none;
        }

        .register-scroll{
            max-height:700px;
            overflow-y:auto;
            padding-right:10px;
        }

        .register-scroll::-webkit-scrollbar{
            width:6px;
        }

        .register-scroll::-webkit-scrollbar-thumb{
            background:#ccc;
            border-radius:10px;
        }

        .login100-form-btn{
            background:#1d6988;
            border:none;
            width:100%;
            height:50px;
            border-radius:25px;
            color:#fff;
            font-weight:600;
            transition:0.3s;
        }

        .login100-form-btn:hover{
            background:#0f2747;
        }

        .login100-pic img{
            max-width:100%;
        }

    </style>

</head>

<body>

<!-- Loader -->
<div class="loader-wrapper">

    <div class="ring"></div>
    <div class="ring"></div>
    <div class="ring"></div>

    <span class="loading">
        Loading...
    </span>

</div>

<div class="limiter">

    <div class="container-login100">

        <div class="wrap-login100">

            <!-- Left Image -->
            <div class="login100-pic js-tilt" data-tilt>

                <img src="{{ asset('assets/images/img-01.png') }}"
                    alt="IMG">

            </div>

            <!-- Register Form -->
            <form class="login100-form validate-form register-scroll"
                method="POST"
                action="{{ route('registerUser') }}">

                @csrf

                <!-- Logo -->
                <div class="login100-pic js-tilt mb-3"
                    data-tilt>

                    <img style="width:250px"
                        src="{{ asset('assets/images/logo.png') }}"
                        alt="IMG">

                </div>

                <span class="login100-form-title">
                    Register User
                </span>

                <!-- Errors -->
                @if ($errors->any())

                    <div class="alert alert-danger">

                        <ul class="mb-0">

                            @foreach ($errors->all() as $error)

                                <li>{{ $error }}</li>

                            @endforeach

                        </ul>

                    </div>

                @endif

                <!-- Success -->
                @if ($message = Session::get('success'))

                    <div class="alert alert-success">

                        {{ $message }}

                    </div>

                @endif

                <!-- Title -->
                <div class="wrap-input100">

                    <select name="title" required>

                        <option value="">
                            Select Title
                        </option>

                        <option value="Mr.">Mr.</option>
                        <option value="Mrs.">Mrs.</option>
                        <option value="Ms.">Ms.</option>
                        <option value="Dr.">Dr.</option>
                        <option value="Prof.">Prof.</option>

                    </select>

                    <span class="symbol-input100">
                        <i class="fa fa-user"></i>
                    </span>

                </div>

                <!-- Name -->
                <div class="wrap-input100">

                    <input class="input100"
                        type="text"
                        name="name"
                        placeholder="Name"
                        value="{{ old('name') }}"
                        required>

                    <span class="symbol-input100">
                        <i class="fa fa-user"></i>
                    </span>

                </div>

                <!-- Surname -->
                <div class="wrap-input100">

                    <input class="input100"
                        type="text"
                        name="surname"
                        placeholder="Surname"
                        value="{{ old('surname') }}"
                        required>

                    <span class="symbol-input100">
                        <i class="fa fa-user"></i>
                    </span>

                </div>

                <!-- ID -->
                <div class="wrap-input100">

                    <input class="input100"
                        type="text"
                        name="id_no"
                        id="id_no"
                        placeholder="ID Number"
                        pattern="[0-9]{13}"
                        required>

                    <span class="symbol-input100">
                        <i class="fa fa-id-card"></i>
                    </span>

                </div>

                <!-- Gender -->
                <div class="wrap-input100">

                    <select name="gender"
                        id="gender"
                        required>

                        <option value="">
                            Select Gender
                        </option>

                        <option value="Male">
                            Male
                        </option>

                        <option value="Female">
                            Female
                        </option>

                    </select>

                    <span class="symbol-input100">
                        <i class="fa fa-users"></i>
                    </span>

                </div>

                <!-- Email -->
                <div class="wrap-input100">

                    <input class="input100"
                        type="email"
                        name="email"
                        placeholder="Email"
                        value="{{ old('email') }}"
                        required>

                    <span class="symbol-input100">
                        <i class="fa fa-envelope"></i>
                    </span>

                </div>

                <!-- Phone -->
                <div class="wrap-input100">

                    <input class="input100"
                        type="text"
                        name="phone"
                        placeholder="Mobile Number"
                        pattern="[0-9]{10}"
                        required>

                    <span class="symbol-input100">
                        <i class="fa fa-phone"></i>
                    </span>

                </div>

                <!-- Communication -->
                <div class="wrap-input100">

                    <select name="communication"
                        required>

                        <option value="">
                            Communication
                        </option>

                        <option value="Email">
                            Email
                        </option>

                        <option value="SMS">
                            SMS
                        </option>

                        <option value="Both">
                            Both
                        </option>

                    </select>

                    <span class="symbol-input100">
                        <i class="fa fa-comments"></i>
                    </span>

                </div>

                <!-- Store -->
                <div class="wrap-input100">

                    <select name="store"
                        required>

                        <option value="">
                            Store Address
                        </option>

                        <option value="Hemmingways">
                            Hemmingways
                        </option>

                        <option value="Vincent">
                            Vincent
                        </option>

                        <option value="Stone Towers">
                            Stone Towers
                        </option>

                    </select>

                    <span class="symbol-input100">
                        <i class="fa fa-location"></i>
                    </span>

                </div>

                <!-- Job Title -->
                <div class="wrap-input100">

                    <input class="input100"
                        type="text"
                        name="job_title"
                        placeholder="Job Title"
                        required>

                    <span class="symbol-input100">
                        <i class="fa fa-briefcase"></i>
                    </span>

                </div>

                <!-- Role -->
                <div class="wrap-input100">

                    <select name="role"
                        required>

                        <option value="">
                            Register As
                        </option>

                        <option value="Admin">
                            Admin
                        </option>

                        <option value="User">
                            User
                        </option>

                        <option value="department-head">
                            Department Head
                        </option>

                    </select>

                    <span class="symbol-input100">
                        <i class="fa fa-user-secret"></i>
                    </span>

                </div>

                <!-- Location -->
                <div class="wrap-input100">

                    <input class="input100"
                        type="text"
                        name="location"
                        placeholder="Location"
                        value="{{ old('location') }}"
                        required>

                    <span class="symbol-input100">
                        <i class="fa fa-map-marker"></i>
                    </span>

                </div>

                <!-- Password -->
                <div class="wrap-input100">

                    <input class="input100"
                        type="password"
                        name="password"
                        placeholder="Password"
                        required>

                    <span class="symbol-input100">
                        <i class="fa fa-lock"></i>
                    </span>

                </div>

                <!-- Confirm Password -->
                <div class="wrap-input100">

                    <input class="input100"
                        type="password"
                        name="password_confirmation"
                        placeholder="Confirm Password"
                        required>

                    <span class="symbol-input100">
                        <i class="fa fa-lock"></i>
                    </span>

                </div>

                <!-- Declaration -->
                <div class="mb-3">

                    <input type="checkbox"
                        id="declaration"
                        required>

                    <label for="declaration"
                        style="font-size:13px">

                        To the best of my knowledge,
                        the details I've given are accurate.

                    </label>

                </div>

                <!-- Button -->
                <div class="container-login100-form-btn">

                    <button type="submit"
                        class="login100-form-btn">

                        Register

                    </button>

                </div>

                <!-- Login -->
                <div class="text-center p-t-12">

                    <span class="txt1">
                        Already have an account?
                    </span>

                    <a class="txt2"
                        href="{{ route('login') }}">

                        Login Here

                    </a>

                </div>

            </form>

        </div>

    </div>

</div>

<!-- Scripts -->

<script src="{{ asset('assets/vendor/jquery/jquery-3.2.1.min.js') }}"></script>

<script src="{{ asset('assets/vendor/bootstrap/js/popper.js') }}"></script>

<script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.min.js') }}"></script>

<script src="{{ asset('assets/vendor/select2/select2.min.js') }}"></script>

<script src="{{ asset('assets/vendor/tilt/tilt.jquery.min.js') }}"></script>

<script src="{{ asset('assets/js/main.js') }}"></script>

<script>

    // Hide Loader
    $(window).on("load", function(){

        $(".loader-wrapper").fadeOut("slow");

    });

    // Tilt
    $('.js-tilt').tilt({
        scale:1.1
    });

    // Auto Gender Detection
    $("#id_no").on("input", function(){

        let idNumber = $(this).val();

        if(idNumber.length >= 10){

            let genderCode =
                parseInt(idNumber.substring(6,10));

            if(genderCode < 5000){

                $("#gender").val("Female");

            }else{

                $("#gender").val("Male");

            }

        }else{

            $("#gender").val("");

        }

    });

</script>

</body>
</html>