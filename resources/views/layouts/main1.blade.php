<!DOCTYPE html>
<html lang="en">
<head>
   <!-- Required meta tags -->
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Leave Application System</title>
 <!-- plugins:css -->
 <link rel="stylesheet" href="vendors/feather/feather.css">
 <link rel="stylesheet" href="vendors/mdi/css/materialdesignicons.min.css">
 <link rel="stylesheet" href="vendors/ti-icons/css/themify-icons.css">
 <link rel="stylesheet" href="vendors/typicons/typicons.css">
 <link rel="stylesheet" href="vendors/simple-line-icons/css/simple-line-icons.css">
 <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
 <!-- endinject -->
 <!-- inject:css -->
 <link rel="stylesheet" href="css/vertical-layout-light/style.css">
   <!-- endinject -->
<!-- jQuery CDN -->
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>

  <!-- Datatables CSS CDN -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap4.min.css">
 <!-- Datatables JS CDN -->
 <script type="text/javascript" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
 <script type="text/javascript" src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js"></script>
  <!-- endinject -->
  <!-- End plugin css for this page -->
  <link rel="icon" href="images/logo.png" type="images/logo.png">
  <!-- Boostrasp -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<!--  icons styling -->
<link href="https://fonts.googleapis.com/css2?family=Material+Icons" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>

 <!-- cards styling and fonts -->
 <link rel="stylesheet" type="text/css" href="https://pixinvent.com/stack-responsive-bootstrap-4-admin-template/app-assets/css/bootstrap-extended.min.css">
<link rel="stylesheet" type="text/css" href="https://pixinvent.com/stack-responsive-bootstrap-4-admin-template/app-assets/fonts/simple-line-icons/style.min.css">
<link rel="stylesheet" type="text/css" href="https://pixinvent.com/stack-responsive-bootstrap-4-admin-template/app-assets/css/colors.min.css">
<link rel="stylesheet" type="text/css" href="https://pixinvent.com/stack-responsive-bootstrap-4-admin-template/app-assets/css/bootstrap.min.css">
</head>

<body >

<div class="loader-wrapper">
    <div class="ring"></div>
    <div class="ring"></div>
    <div class="ring"></div>
    <span class="loading">Loading...</span>

</div>



  <div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
    <nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex align-items-top flex-row" style="border: 5px solid #C3bE5C">
      <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
        <div class="me-3">
          <button class="navbar-toggler navbar-toggler align-self-center icon-color" type="button" data-bs-toggle="minimize">
            <span class="icon-menu"></span>
          </button>
        </div>
        <div>
          <a class="navbar-brand" href="{{url('dashboard')}}">
            <img src="images/logo1.png" alt="logo" />
          </a>
          <!-- <a class="navbar-brand brand-logo-mini" href="{{url('dashboard')}}">
            <img src="images/logo1.png" alt="logo" />
          </a> -->
        </div>
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-top">

      <ul class="navbar-nav">
          <li class="nav-item font-weight-semibold d-none d-lg-block ms-0">
            <h1 class="welcome-text">Leave Management System</h1>
          </li>
        </ul>
      <ul class="navbar-nav ms-auto">
          {{-- <li class="nav-item d-none d-lg-block">
          <h1 class="welcome-text">Welcome, <span class="text-gray fw-bold">{{Auth::user()->name}} {{Auth::user()->surname}}</span></h1>
          </li> --}}
        <ul class="navbar-nav ms-auto">
          <li class="nav-item d-none d-lg-block">
            <div id="datepicker-popup" class="input-group date datepicker navbar-date-picker">
              <span class="input-group-addon input-group-prepend border-right">
                <span class="icon-calendar input-group-text calendar-icon"></span>
              </span>
              <input  type="text" id ="calendar"  class="form-control">
            </div>
          </li>

          <li class="nav-item dropdown d-none d-lg-block user-dropdown" >
            <a class="nav-link" id="UserDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
              <img class="img-xs rounded-circle"  src="{{Auth::user()->profile}}" alt="Profile image"> </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown" style="border: 5px solid #18345D">
              <div class="dropdown-header text-center">
                <img class="img-md rounded-circle" src="{{Auth::user()->profile}}" style="width:60%;hieght:80%;" alt="Profile image">
                <p class="mb-1 mt-3 font-weight-semibold">{{Auth::user()->name}} {{Auth::user()->surname}}</p>
                <p class="fw-light text-muted mb-0">{{Auth::user()->email}}</p>
              </div>
              <a class="dropdown-item" href ="{{ url('profile')}}"><i class="dropdown-item-icon mdi mdi-account-outline text-primary me-2"></i> My Profile</a>
             @if ((Auth::user()->hasRole("SuperAdmin")) || (Auth::User()->hasRole('Admin')))
             <a class="dropdown-item" href ="{{ url('feedsView')}}"><i class="dropdown-item-icon mdi mdi-checkbox-marked-circle-outline text-primary me-2"></i>User Activity feeds</a>
             @endif
              <a class="dropdown-item"  href ="{{ url('Logout')}}"><i class="dropdown-item-icon mdi mdi-power text-primary me-2"></i>Sign Out</a>
            </div>
          </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-bs-toggle="offcanvas">
          <span class="mdi mdi-menu"></span>
        </button>
      </div>
    </nav>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:partials/_settings-panel.html -->

      <div id="right-sidebar" class="settings-panel">
        <i class="settings-close ti-close"></i>
        <ul class="nav nav-tabs border-top" id="setting-panel" role="tablist">
          <li class="nav-item">
            <a class="nav-link active" id="todo-tab" data-bs-toggle="tab" href="#todo-section" role="tab" aria-controls="todo-section" aria-expanded="true"></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="chats-tab" data-bs-toggle="tab" href="#chats-section" role="tab" aria-controls="chats-section"></a>
          </li>
        </ul>
        <div class="tab-content" id="setting-content">
          <div class="tab-pane fade show active scroll-wrapper" id="todo-section" role="tabpanel" aria-labelledby="todo-section">
            <div class="add-items d-flex px-3 mb-0">
              <form class="form w-100">
                <div class="form-group d-flex">

                </div>
              </form>
            </div>
            <div class="list-wrapper px-3">
              <ul class="d-flex flex-column-reverse todo-list">
                <li>
                  <div class="form-check">
                    <label class="form-check-label">

                    </label>
                  </div>
                  <i class="remove ti-close"></i>
                </li>
                <li>
                  <div class="form-check">
                    <label class="form-check-label">

                    </label>
                  </div>
                  <i class="remove ti-close"></i>
                </li>
                <li>
                  <div class="form-check">
                    <label class="form-check-label">

                    </label>
                  </div>
                  <i class="remove ti-close"></i>
                </li>
                <li class="completed">
                  <div class="form-check">
                    <label class="form-check-label">

                    </label>
                  </div>
                  <i class="remove ti-close"></i>
                </li>
                <li class="completed">
                  <div class="form-check">
                    <label class="form-check-label">

                    </label>
                  </div>

                </li>
              </ul>
            </div>
            <h4 class="px-3 text-muted mt-5 fw-light mb-0"></h4>
            <div class="events pt-4 px-3">
              <div class="wrapper d-flex mb-2">
                <i class="ti-control-record text-primary me-2"></i>

              </div>
              <p class="mb-0 font-weight-thin text-gray"></p>
              <p class="text-gray mb-0"></p>
            </div>
            <div class="events pt-4 px-3">
              <div class="wrapper d-flex mb-2">
                <i class="ti-control-record text-primary me-2"></i>

              </div>
              <p class="mb-0 font-weight-thin text-gray"></p>
              <p class="text-gray mb-0 "></p>
            </div>
          </div>
          <!-- To do section tab ends -->

        </div>
      </div>
      <!-- partial -->
      <!-- partial:partials/_sidebar.html -->
      <nav class="sidebar sidebar-offcanvas" id="sidebar" style="border-right: 5px solid #C3bE5C;margin-top:1%;">
        <ul class="nav">
          <li class="nav-item">
            <a class="nav-link" href="{{url('dashboard')}}">
              <i class="mdi mdi-grid-large menu-icon"></i>
              <span class="menu-title">Dashboard</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{url('planning')}}">
              <i class="menu-icon fa fa-calendar"></i>
              <span class="menu-title">Planning</span>

            </a>
          </li>
          @if(Auth::User()->hasRole('Admin') || Auth::User()->hasRole('SuperAdmin'))
          <li class="nav-item">
            <a class="nav-link" href="{{url('department')}}">
              <i class="menu-icon fa fa-building"></i>
              <span class="menu-title">Department</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{url('leavePerStaff')}}">
              <i class="menu-icon"><i class="material-icons">airline_seat_individual_suite</i></i>
              <span class="menu-title">Leave Days per staff</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{url('leavetype')}}">
              <i class="menu-icon"><i class="material-icons">bed</i></i>
              <span class="menu-title">Leave Types</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{url('holiday')}}">
              <i class="menu-icon"><i class="material-icons">date_range</i></i>
              <span class="menu-title">Holidays</span>
            </a>
          </li>
        <li class="nav-item nav-category">Staff</li>
        <li class="nav-item">
          <a class="nav-link" data-bs-toggle="collapse" href="#auth" aria-expanded="false" aria-controls="auth">
            <i class="menu-icon mdi mdi-account-circle-outline"></i>
            <span class="menu-title">Staff Memebers</span>
            <i class="menu-arrow"></i>
          </a>
          <div class="collapse" id="auth">
            <ul class="nav flex-column sub-menu">
              <li class="nav-item"> <a class="nav-link" href="{{ url('staff')}}"> <i class = "fa fa-users"></i>  Staff Management </a></li>
            </ul>
          </div>
        </li>
          @endif
          <li class="nav-item nav-category">Leaves</li>
          <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
              <i class="fa fa-file-text-o menu-icon"></i>
              <span class="menu-title">Leave Applications</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-basic">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="{{ url('leaveapply')}}"> <i class="material-icons">border_color</i> Apply</a></li>
                @if (!(Auth::user()->hasRole("user")))
                <li class="nav-item"> <a class="nav-link" href="{{ url('leave-plans')}}"> <i class="material-icons">event_busy</i> Leave plans</a></li>
                @endif
                @if((Auth::User()->hasRole('Admin')))
                <li class="nav-item"> <a class="nav-link" href="{{ url('leave-List')}}"><i class="material-icons">assignment</i>Leaves</a></li>
                @endif
                @if((Auth::User()->hasRole('SuperAdmin')) || Auth::User()->hasRole('Admin'))
                <li class="nav-item"> <a class="nav-link" href="{{ url('alleaves')}}"><i class="material-icons">assignment</i> HOD Leaves</a></li>
                @endif
                {{-- <li class="nav-item"> <a class="nav-link" href="{{ url('approved')}}"><i class="material-icons">event_available</i> Approved Leaves</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{ url('rejected')}}"><i class="material-icons">event_busy</i> Rejected Leaves</a></li> --}}

                @if(Auth::User()->hasRole('department-head'))
                <li class="nav-item"><a class="nav-link" href="{{ url('hodLeaves')}}"><i class="material-icons">assignment</i> Department Leaves</a></li>
                @endif
                <li class="nav-item"> <a class="nav-link" href="{{ url('myleave')}}"><i class="material-icons">event_note</i> My Leaves</a></li>
              </ul>
            </div>
          </li>
        </ul>
      </nav>
      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-12">
              <div class="home-tab">
                <!-- <div class="d-sm-flex align-items-center justify-content-between border-bottom">
                  <div>

                  </div>
                </div> -->
                  <div class="tab-content tab-content-basic">
                    <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview">

                    <!-- </div> -->
                            <div class="row">
                              <div class="col-lg-12 d-flex flex-column">
                                @yield('content')

                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->
        <footer class="footer" style="border-top: 5px solid #C3bE5C">
          <div class="d-sm-flex justify-content-center justify-content-sm-between">
            <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Developed by <a href="https://www.ictchoice.com/" target="_blank"> ICT Choice</a></span>
            <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Copyright © {{now()->year}}. All rights reserved.</span>
          </div>
        </footer>
        <!-- partial -->
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
  <!-- Bootstrap core JavaScript-->
{{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> --}}
<!-- Page level plugin JavaScript-->
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
   <!-- jQuery first, then Popper.js, then Bootstrap JS -->
   <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
   <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  <!-- <script>
    var load = document.getElementById("loader");
    function loadfun()
    {
      load.style.display = 'none';
    }
  </script> -->

<script>
    $(window).on("load",function(){
      $(".loader-wrapper").fadeOut("slow");
    });
</script>

  <script>
     function archiveFunction() {
      event.preventDefault(); // prevent form submit
      var action = event.target.form; // storing the form
      swal({
    title: "Are you sure?",
    text: "Once deleted, you will not be able to recover this record!",
    icon: "warning",
    buttons: true,
    dangerMode: true,
  })
  .then((willDelete) => {
    if (willDelete) {
      action.submit();          // submitting the form when user press yes
    } else {
      swal("Your record is safe!");
    }
  });
  }
  </script>
<script>
 $('.close-icon').on('click',function() {
  $(this).closest('.card').fadeOut();
})
  </script>
  <!-- plugins:js -->
  <script src="vendors/js/vendor.bundle.base.js"></script>
  <!-- endinject -->
  <!-- Plugin js for this page -->
  <script src="vendors/chart.js/Chart.min.js"></script>
  <script src="vendors/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
  <script src="vendors/progressbar.js/progressbar.min.js"></script>

  <!-- End plugin js for this page -->
  <!-- inject:js -->
  <script src="js/off-canvas.js"></script>
  <script src="js/hoverable-collapse.js"></script>
  <script src="js/template.js"></script>
  <script src="js/settings.js"></script>
  <script src="js/todolist.js"></script>
  <!-- endinject -->
  <!-- Custom js for this page-->
  <script src="js/dashboard.js"></script>
  <script src="js/Chart.roundedBarCharts.js"></script>
  <!-- End custom js for this page-->
</body>
</html>

