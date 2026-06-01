<!DOCTYPE html>
<html lang="en">

<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
    <title>Forgot Password - Performance Managemnt</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" type="image/png" href="{{asset('assets/images/icons/favicon.ico')}}" />

    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendor/bootstrap/css/bootstrap.min.css')}}">

    <link rel="stylesheet" type="text/css" href="{{asset('assets/fonts/font-awesome-4.7.0/css/font-awesome.min.css')}}">

    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendor/animate/animate.css')}}">

    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendor/css-hamburgers/hamburgers.min.css')}}">

    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendor/select2/select2.min.css')}}">

    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/util.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/main.css')}}">

    <style>
       /* loader */


   .loader-wrapper{
    width:100%;
    height: 100%;
    position: absolute;
    top: 0;
    left: 0;
    background-color: #fff;
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 999;
    }

    .ring{
    width: 200px;
    height: 200px;
    border: 0px solid #18345d;
    border-radius: 50%;
    position: absolute;
    }

    .ring:nth-child(1){
    border-bottom-width: 8px;
    border-color: #18345d;
    animation: rotate1 2s linear infinite;
    }
    .ring:nth-child(2){
    border-right-width: 8px;
    border-color: #C3bE5C;
    animation: rotate2 2s linear infinite;
    }
    .ring:nth-child(3){
    border-top-width: 8px;
    border-color: #8689a1;
    animation: rotate3 2s linear infinite;
    }

    .loading{
    color: black;
    }

    @keyframes rotate1 {
    0% {  transform: rotateX(35deg) rotateY(-45deg) rotateZ(0deg);  }

    100% { transform: rotateX(35deg) rotateY(-45deg) rotateZ(360deg); }
    }
    @keyframes rotate2 {
    0% {  transform: rotateX(50deg) rotateY(10deg) rotateZ(0deg);  }

    100% { transform: rotateX(50deg) rotateY(10deg) rotateZ(360deg); }
    }
    @keyframes rotate3 {
    0% {  transform: rotateX(35deg) rotateY(55deg) rotateZ(0deg);  }

    100% { transform: rotateX(35deg) rotateY(55deg) rotateZ(360deg); }
    }


    /* .hide{
        display: none;
    } */
    .fa
        {
            margin-left: -12px;
            margin-right: 8px;
        }
    </style>

    <meta name="robots" content="noindex, follow">
</head>
<body>
<div class="loader-wrapper">
<div class="ring"></div>
<div class="ring"></div>
<div class="ring"></div>
<span class="loading">Loading...</span>
</div>
    <div class="limiter">
        <div class="container-login100">
            <div class="wrap-login100">
                <div class="login100-pic js-tilt" data-tilt>
                    <img src="{{asset('assets/images/img-01.png')}}" alt="IMG">
                </div>
                @if (isset($token))
                <form class="login100-form validate-form" action="{{ route('password.update') }}"  method="post">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                    <div class="login100-pic js-tilt" data-tilt>
                            <img style="width: 300px" src="{{asset('images/logo3.png')}}" alt="IMG">
                        </div>
                        <span class="login100-form-title">
                           Reset Password
                        </span>
                        @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="wrap-input100 validate-input" data-validate="Valid email is required: ex@abc.xyz">
                            <input class="input100" type="text" name="email" placeholder="Email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>
                            <span class="focus-input100"></span>
                            <span class="symbol-input100">
                                <i class="fa fa-envelope" aria-hidden="true"></i>
                            </span>
                        </div>
                        <div class="wrap-input100 validate-input" data-validate="Password is required">
                            <input class="input100" type="password" name="password" placeholder="Password" required autocomplete="new-password">
                            <span class="focus-input100"></span>
                            <span class="symbol-input100">
                                <i class="fa fa-lock" aria-hidden="true"></i>
                            </span>
                        </div>
                        <div class="wrap-input100 validate-input" data-validate="Password is required">
                            <input class="input100" type="password" name="password_confirmation" placeholder="Confirm Password" required autocomplete="new-password">
                            <span class="focus-input100"></span>
                            <span class="symbol-input100">
                                <i class="fa fa-lock" aria-hidden="true"></i>
                            </span>
                        </div>
    
                        <div class="container-login100-form-btn">
                            <button style="background-color: rgb(6, 6, 102)" type="submit" class=" login100-form-btn">
                            Reset Password
                            </button>
                        </div>
                    </form>
                @else
                <form class="login100-form validate-form" action="{{ route('password.email') }}"  method="post">
                    @csrf
                    <div class="login100-pic js-tilt" data-tilt>
                            <img style="width: 300px" src="{{asset('images/logo3.png')}}" alt="IMG">
                        </div>
                        <span class="login100-form-title">
                           Forgot Password
                        </span>
                        @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                       @if ($message = Session::get('success'))
                        <div class="alert alert-success alert-dismissible">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                <p>{{ $message }}</p>
                                </div>
                         @endif
                        <div class="wrap-input100 validate-input" data-validate="Valid email is required: ex@abc.xyz">
                            <input class="input100" type="text" name="email" placeholder="Email">
                            <span class="focus-input100"></span>
                            <span class="symbol-input100">
                                <i class="fa fa-envelope" aria-hidden="true"></i>
                            </span>
                        </div>
    
                        <div class="container-login100-form-btn">
                            <button style="background-color: rgb(6, 6, 102)" type="submit" class=" login100-form-btn">
                            Send Password Reset Link
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <script src="{{asset('assets/vendor/jquery/jquery-3.2.1.min.js')}}"></script>

    <script src="{{asset('assets/vendor/bootstrap/js/popper.js')}}"></script>
    <script src="{{asset('assets/vendor/bootstrap/js/bootstrap.min.js')}}"></script>

    <script src="{{asset('assets/vendor/select2/select2.min.js')}}"></script>

    <script src="{{asset('assets/vendor/tilt/tilt.jquery.min.js')}}"></script>
    <script>
        $(window).on("load",function(){
          $(".loader-wrapper").fadeOut("slow");
        });
    </script>
    <script>
        $('.js-tilt').tilt({
            scale: 1.1
        })

    </script>

    <script src="{{asset('assets/js/main.js')}}"></script>
</body>

</html>
