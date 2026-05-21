<!DOCTYPE html>
<html lang="en">

<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
    <title>Login - Performance Management System</title>
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
    <script nonce="43193252-33a1-4642-92f5-ebaf6f459a5a">
        (function (w, d) {
            ! function (a, e, t, r) {assets/
                a.zarazData = a.zarazData || {}, a.zarazData.executed = [], a.zaraz = {
                    deferred: []
                }, a.zaraz.q = [], a.zaraz._f = function (e) {
                    return function () {
                        var t = Array.prototype.slice.call(arguments);
                        a.zaraz.q.push({
                            m: e,
                            a: t
                        })
                    }
                };
                for (const e of ["track", "set", "ecommerce", "debug"]) a.zaraz[e] = a.zaraz._f(e);
                a.addEventListener("DOMContentLoaded", (() => {
                    var t = e.getElementsByTagName(r)[0],
                        z = e.createElement(r),
                        n = e.getElementsByTagName("title")[0];
                    for (a.zarazData.c = e.cookie, n && (a.zarazData.t = e.getElementsByTagName(
                            "title")[0].text), a.zarazData.w = a.screen.width, a.zarazData.h = a.screen
                        .height, a.zarazData.j = a.innerHeight, a.zarazData.e = a.innerWidth, a
                        .zarazData.l = a.location.href, a.zarazData.r = e.referrer, a.zarazData.k = a
                        .screen.colorDepth, a.zarazData.n = e.characterSet, a.zarazData.o = (new Date)
                        .getTimezoneOffset(), a.zarazData.q = []; a.zaraz.q.length;) {
                        const e = a.zaraz.q.shift();
                        a.zarazData.q.push(e)
                    }
                    z.defer = !0, z.referrerPolicy = "origin", z.src =
                        "../../../cdn-cgi/zaraz/sd0d9.js?z=" + btoa(encodeURIComponent(JSON.stringify(a
                            .zarazData))), t.parentNode.insertBefore(z, t)
                }))
            }(w, d, 0, "script");
        })(window, document);

    </script>
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
                <form class="login100-form validate-form" id ="loginForm" action="{{ route('loginStore') }}"  method="post">
                @csrf <!-- {{ csrf_field() }} -->
                <div class="login100-pic js-tilt" data-tilt>
                        <img style="width: 300px" src="{{asset('images/logo3.png')}}" alt="IMG">
                    </div>
                    <span class="login100-form-title">
                       Asset  Management System
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
                    <div class="wrap-input100 validate-input" data-validate="Password is required">
                        <input class="input100" type="password" name="password" placeholder="Password">
                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class="fa fa-lock" aria-hidden="true"></i>
                        </span>
                    </div>

                    <div class="container-login100-form-btn">
                        <div class="overlay"> <div class="spinner"></div></div>

                        <button style="background-color: rgb(6, 6, 102)" data-style="expand-right" type="submit" id = "btnSubmit" class=" login100-form-btn buttonload" onclick = "return Login()" >

                        Login
                        </button>

                    </div>
                   <div class="text-center p-t-12">
                        <a class="txt2" href="">
                            Forgot Password?
                        </a>
                        <span class="txt1">
                            |
                        </span>
                        <a class="txt2" href="">
                            Register?
                        </a>
                    </div>
                    <div class="text-center p-t-136">

                    </div>
                </form>
            </div>
        </div>
    </div>
<script >


    function Login() {
            $("#btnSubmit").html('<i class="fa fa-spinner fa-spin"></i>Loading...');
           $("#loginForm").submit();
        }

</script>

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

    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-23581568-13"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'UA-23581568-13');

    </script>

    <script src="{{asset('assets/js/main.js')}}"></script>
    <script defer
        src="https://static.cloudflareinsights.com/beacon.min.js/v652eace1692a40cfa3763df669d7439c1639079717194"
        integrity="sha512-Gi7xpJR8tSkrpF7aordPZQlW2DLtzUlZcumS8dMQjwDHEnw9I7ZLyiOj/6tZStRBGtGgN6ceN6cMH8z7etPGlw=="
        data-cf-beacon='{"rayId":"6fa469ebef373f2e","token":"cd0b4b3a733644fc843ef0b185f98241","version":"2021.12.0","si":100}'
        crossorigin="anonymous"></script>
</body>

</html>
