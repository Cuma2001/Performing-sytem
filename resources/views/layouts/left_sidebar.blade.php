<style>
    .menu-block .sidebar-menu ul .active {
      color: white !important;
      background-color: #f4c610 !important;
      border: 5px solid #f4c610 !important;
  }
  .left-side-bar{
      border-right: 5px solid #f4c610 !important;
  }
</style>
<div class="left-side-bar">
    <div class="brand-logo">
        <a class="navbar-brand" href="">
            <img src="{{asset('assets/images/logo.png')}}" alt="logo" />
          </a>
        <div class="close-sidebar" data-toggle="left-sidebar-close">
            <i class="ion-close-round"></i>
        </div>
    </div>
    <div class="menu-block customscroll">
        <div class="sidebar-menu">
            <ul id="accordion-menu">
                <li class="dropdown">
                    <a href="" class="dropdown-toggle no-arrow">
                        <span class="micon dw dw-house-1"></span><span class="mtext">Dashboard</span>
                    </a>

                </li>

                <li>
                    <a href="" class="dropdown-toggle no-arrow">
                        <span class="micon"><i class="dw dw-user"></i></span><span class="mtext">Staff Leave Days</span>
                    </a>
                </li>

                <li>
                    <a href="" class="dropdown-toggle no-arrow">
                        <span class="micon"><i class="material-icons">bed</i></span><span class="mtext">Leave Type</span>
                    </a>
                </li>
                <li>
                    <a href="" class="dropdown-toggle no-arrow">
                        <span class="micon"><i class="material-icons">date_range</i></span><span class="mtext">Holidays</span>
                    </a>
                </li>
                <li class="dropdown">
                    <a ref="javascript:void(0);" class="dropdown-toggle">
                        <span class="micon fa fa-users"></span><span class="mtext">Staff</span>
                    </a>
                    <ul class="submenu">
                        <li><a href="">Manage Staff</a></li>
                    </ul>
                </li>

                <li class="dropdown">
                    <a href="javascript:void(0);" class="dropdown-toggle">
                        <span class="micon dw dw-apartment"></span><span class="mtext"> Leave </span>
                    </a>
                    <ul class="submenu">
                        <li><a href="">Apply Leave</a></li>

                        <li><a  href="">Leaves</a></li>
                        <li><a href=">HOD Leaves</a></li>
                        <li><a href="">Department Leaves</a></li>
                        <li><a href="">My Leaves</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>
