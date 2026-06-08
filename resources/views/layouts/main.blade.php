<!DOCTYPE html>
<html>
    <head>
        <!-- Basic Page Info -->
        <meta charset="utf-8">
        <title>Perfomance Management</title>

        <!-- Site favicon -->
        <link rel="icon" href="images/logo.png" sizes="16x16" type="images/logo.png">
        <link href="https://fonts.googleapis.com/css2?family=Material+Icons" rel="stylesheet">
        <!-- Mobile Specific Metas -->
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <!-- Google Font -->
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
        <!-- CSS -->
        <link rel="stylesheet" type="text/css" href="vendors/styles/core.css">
        <link rel="stylesheet" type="text/css" href="vendors/styles/icon-font.min.css">
        <link rel="stylesheet" type="text/css" href="vendors/styles/style.css">
            <!-- datatables -->
        <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap4.min.css">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js"></script>
    </head>
    <style>
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            color: white !important;
            background-color: #1d6988 !important;
            border-color: #1d6988 !important;

    }
        .thead-naive{
            color: white !important;
            background-color: #1d6988 !important;
            border: 5px solid #f4c610 !important;
        }
        .btn-naive{
            color: white !important;
            background-color: #1d6988 !important;
        }


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
        border: 0px solid #1d6988;
        border-radius: 50%;
        position: absolute;
        }

        .ring:nth-child(1){
        border-bottom-width: 8px;
        border-color:#1d6988;
        animation: rotate1 2s linear infinite;
        }
        .ring:nth-child(2){
        border-right-width: 8px;
        border-color: #f4c610;
        animation: rotate2 2s linear infinite;
        }
        .ring:nth-child(3){
        border-top-width: 8px;
        border-color: #e5222b;
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
    </style>

    <body class="d-flex flex-column min-vh-100">
        <div class="loader-wrapper">
            <div class="ring"></div>
            <div class="ring"></div>
            <div class="ring"></div>
            <span class="loading">Loading...</span>
        </div>
        @include('layouts/navbar')
        @include('layouts/right_sidebar')
        @include('layouts/left_sidebar')

        <div class="mobile-menu-overlay"></div>
        <div class="main-container">
            @yield('content')
        </div>

        <script>
            $(window).on("load",function(){
            $(".loader-wrapper").fadeOut("slow");
            });
        </script>
        <script>
            $(function($) {
            let url = window.location.href;
            $('.menu-block .sidebar-menu ul li a').each(function() {
                if (this.href === url) {
                $(this).closest('li').addClass('active');
                }
            });
            });
        </script>
        <script>
            $('.close-icon').on('click',function() {
            $(this).closest('.card').fadeOut();
        })
        </script>
            <!-- js -->
            @include('layouts/scripts')
    </body>
    <footer class="mt-auto">
        @include('layouts/footer')
    </footer>
</html>
