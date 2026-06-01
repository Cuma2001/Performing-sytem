<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @include('layouts.head')

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>

    <style>

        body {
            background-color: #f5f7fb;
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
            background-color:#fff;
            display:flex;
            justify-content:center;
            align-items:center;
            z-index:9999;
        }

        .ring{
            width:200px;
            height:200px;
            border:0px solid #18345d;
            border-radius:50%;
            position:absolute;
        }

        .ring:nth-child(1){
            border-bottom-width:8px;
            border-color:#18345d;
            animation:rotate1 2s linear infinite;
        }

        .ring:nth-child(2){
            border-right-width:8px;
            border-color:#C3BE5C;
            animation:rotate2 2s linear infinite;
        }

        .ring:nth-child(3){
            border-top-width:8px;
            border-color:#8689a1;
            animation:rotate3 2s linear infinite;
        }

        .loading{
            color:black;
            font-size:18px;
            font-weight:600;
            margin-top:230px;
        }

        @keyframes rotate1 {
            0% {
                transform: rotateX(35deg) rotateY(-45deg) rotateZ(0deg);
            }
            100% {
                transform: rotateX(35deg) rotateY(-45deg) rotateZ(360deg);
            }
        }

        @keyframes rotate2 {
            0% {
                transform: rotateX(50deg) rotateY(10deg) rotateZ(0deg);
            }
            100% {
                transform: rotateX(50deg) rotateY(10deg) rotateZ(360deg);
            }
        }

        @keyframes rotate3 {
            0% {
                transform: rotateX(35deg) rotateY(55deg) rotateZ(0deg);
            }
            100% {
                transform: rotateX(35deg) rotateY(55deg) rotateZ(360deg);
            }
        }

        /* =========================
           Page Design
        ========================== */

        .card0 {
            border-radius: 20px;
            overflow: hidden;
            border: none;
            background: #fff;
        }

        .card1 {
            background: linear-gradient(135deg, #18345d, #242459);
            height: 100%;
            color: white;
        }

        .logo {
            width: 180px;
            margin: 30px;
        }

        .image {
            border-radius: 15px;
        }

        .card2 {
            border-radius: 20px;
        }

        .form-control {
            height: 50px;
            border-radius: 10px;
            margin-bottom: 15px;
        }

        .form-control:focus {
            box-shadow: none;
            border: 1px solid #18345d;
        }

        .btn-success {
            background: #18345d;
            border: none;
            padding: 12px 35px;
            border-radius: 10px;
        }

        .btn-success:hover {
            background: #0f2747;
        }

        .btn-dark {
            padding: 12px 35px;
            border-radius: 10px;
        }

        .bg-blue {
            color: #fff;
        }

        .social-contact span {
            cursor: pointer;
            transition: 0.3s;
        }

        .social-contact span:hover {
            color: #C3BE5C;
        }

        .form-check-label {
            font-size: 13px;
            line-height: 1.5;
        }

        .fa {
            margin-left: -12px;
            margin-right: 8px;
        }

        @media(max-width:768px){

            .card1{
                display:none;
            }

            .card2{
                padding:20px !important;
            }
        }

    </style>
</head>

<body>

<!-- =========================
     Loader
========================= -->
<div class="loader-wrapper" id="loader">
    <div class="ring"></div>
    <div class="ring"></div>
    <div class="ring"></div>
    <span class="loading">Loading...</span>
</div>

<!-- =========================
     Main Container
========================= -->
<div class="container-fluid px-1 px-md-5 px-lg-1 px-xl-5 py-5 mx-auto">

    <div class="card card0 shadow-lg">

        <div class="row d-flex">

            <!-- Left Side -->
            <div class="col-lg-5 p-0">
                <div class="card1 pb-5">

                    <div class="row ml-3">
                        <img src="{{ url('/images/logo.png') }}" class="logo">
                    </div>

                    <div class="row px-3 justify-content-center mt-4 mb-5 border-line">
                        <img src="{{ url('/images/register.jpg') }}" class="image" style="width: 70%;">
                    </div>

                </div>
            </div>

            <!-- Right Side -->
            <div class="col-lg-7">

                <div class="card2 card border-0 px-4 py-5">

                    <div class="row mb-4 px-3">
                        <h2 class="mb-0 font-weight-bold">
                            Register User
                        </h2>
                    </div>

                    <!-- Error Messages -->
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show">
                            <button type="button" class="close" data-dismiss="alert">
                                &times;
                            </button>

                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Success Message -->
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            <button type="button" class="close" data-dismiss="alert">
                                &times;
                            </button>

                            {{ $message }}
                        </div>
                    @endif

                    <small class="mb-4 d-block">
                        Please fill this form to create an account.
                    </small>

                    <!-- Form -->
                    <form action="{{ route('registerUser') }}" method="POST">

                        @csrf

                        <div class="row">

                            <!-- Title -->
                            <div class="col-md-3">
                                <label>Title</label>

                                <select class="form-control" name="title" required>
                                    <option value="">None</option>

                                    @foreach(['Mr.', 'Mrs.', 'Ms.', 'Dr.', 'Prof.'] as $t)
                                        <option value="{{ $t }}"
                                            {{ old('title') == $t ? 'selected' : '' }}>
                                            {{ $t }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Name -->
                            <div class="col-md-4">
                                <label>Name</label>

                                <input type="text"
                                    name="name"
                                    placeholder="John"
                                    class="form-control"
                                    value="{{ old('name') }}"
                                    required>
                            </div>

                            <!-- Surname -->
                            <div class="col-md-5">
                                <label>Surname</label>

                                <input type="text"
                                    name="surname"
                                    placeholder="Doe"
                                    class="form-control"
                                    value="{{ old('surname') }}"
                                    required>
                            </div>

                            <!-- ID Number -->
                            <div class="col-md-4">
                                <label>ID Number</label>

                                <input type="text"
                                    name="id_no"
                                    placeholder="9803300876089"
                                    class="form-control"
                                    pattern="[0-9]{13}"
                                    value="{{ old('id_no') }}"
                                    required>
                            </div>

                            <!-- Gender -->
                            <div class="col-md-3">
                                <label>Gender</label>

                                <select name="gender"
                                    class="form-control"
                                    required
                                    id="gender">

                                    <option value="">None</option>

                                    <option value="Male"
                                        {{ old('gender') == 'Male' ? 'selected' : '' }}>
                                        Male
                                    </option>

                                    <option value="Female"
                                        {{ old('gender') == 'Female' ? 'selected' : '' }}>
                                        Female
                                    </option>

                                </select>
                            </div>

                            <!-- Email -->
                            <div class="col-md-5">
                                <label>Email</label>

                                <input type="email"
                                    name="email"
                                    placeholder="john@example.com"
                                    class="form-control"
                                    value="{{ old('email') }}"
                                    required>
                            </div>

                            <!-- Phone -->
                            <div class="col-md-4">
                                <label>Mobile Number</label>

                                <input type="text"
                                    name="phone"
                                    placeholder="0123456789"
                                    pattern="[0-9]{10}"
                                    class="form-control"
                                    value="{{ old('phone') }}"
                                    required>
                            </div>

                            <!-- Communication -->
                            <div class="col-md-4">
                                <label>Communication</label>

                                <select name="communication"
                                    class="form-control"
                                    required>

                                    <option value="">None</option>

                                    <option value="Email">Email</option>
                                    <option value="SMS">SMS</option>
                                    <option value="Both">Both</option>

                                </select>
                            </div>

                            <!-- Department -->
                            <div class="col-md-4">
                                <label>Department</label>

                                <input type="text"
                                    name="department"
                                    class="form-control"
                                    placeholder="Department"
                                    required>
                            </div>

                            <!-- Job Title -->
                            <div class="col-md-4">
                                <label>Job Title</label>

                                <input type="text"
                                    name="job_title"
                                    class="form-control"
                                    placeholder="IT Manager"
                                    required>
                            </div>

                            <!-- Role -->
                            <div class="col-md-4">
                                <label>Register As</label>

                                <select name="role"
                                    class="form-control"
                                    required>

                                    <option value="">None</option>
                                    <option value="Admin">Admin</option>
                                    <option value="User">User</option>
                                    <option value="department-head">
                                        Department-head
                                    </option>

                                </select>
                            </div>

                            <!-- Location -->
                            <div class="col-md-12">
                                <label>Location</label>

                                <input type="text"
                                    name="location"
                                    placeholder="East London, Eastern Cape"
                                    class="form-control"
                                    value="{{ old('location') }}"
                                    required>
                            </div>

                            <!-- Declaration -->
                            <div class="col-md-12 mt-3">

                                <div class="form-check">

                                    <input type="checkbox"
                                        class="form-check-input"
                                        id="declaration"
                                        required>

                                    <label class="form-check-label"
                                        for="declaration">

                                        To the best of my knowledge,
                                        the details I've given are accurate
                                        and comprehensive.

                                    </label>

                                </div>
                            </div>

                            <!-- Buttons -->
                            <div class="col-md-12 mt-4 text-center">

                                <button type="submit"
                                    class="btn btn-success">

                                    Register
                                </button>

                                <a href="{{ route('login') }}"
                                    class="btn btn-dark ml-2">

                                    Back to login
                                </a>

                            </div>

                        </div>

                    </form>

                </div>

                <!-- Footer -->
                <div class="bg-blue py-4"
                    style="background-color:#242459;">

                    <div class="row px-3">

                        <small class="ml-4 mb-2">
                            &copy; {{ now()->year }}
                            All rights reserved.
                        </small>

                        <div class="social-contact ml-auto">

                            <span class="fa fa-facebook mr-4"></span>
                            <span class="fa fa-google-plus mr-4"></span>
                            <span class="fa fa-linkedin mr-4"></span>
                            <span class="fa fa-twitter mr-4"></span>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<!-- =========================
     Scripts
========================= -->

<script>

    // Hide Loader
    $(window).on("load", function () {
        $("#loader").fadeOut("slow");
    });

    // Auto Gender Detection
    $(document).ready(function () {

        $("input[name='id_no']").on('input', function () {

            var idNumber = $(this).val();

            if (idNumber.length >= 10) {

                var genderCode =
                    parseInt(idNumber.substring(6, 10));

                if (!isNaN(genderCode)) {

                    if (genderCode < 5000) {
                        $("#gender").val("Female");
                    } else {
                        $("#gender").val("Male");
                    }
                }

            } else {

                $("#gender").val("");

            }

        });

    });

</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.min.js"></script>

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

</body>
</html>