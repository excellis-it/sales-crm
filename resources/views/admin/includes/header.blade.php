<div class="main-wrapper">
    <div class="header">
        <div class="header-left">
            <a href="javascript:void(0);" class="logo">
                <img src="{{ asset('admin_assets/img/logopns.png') }}" width="40" height="40" alt="">
               {{--  <h2>{{ env('APP_NAME') }}</h2> --}}
            </a>
            <a href="javascript:void(0);" class="logo2">
                <img src="{{ asset('admin_assets/img/logopns.png') }}" width="40" height="40" alt="">
              {{--   <h2>{{ env('APP_NAME') }}</h2> --}}
            </a>
        </div>
        <a id="toggle_btn" href="javascript:void(0);">
            <span class="bar-icon">
                <span></span>
                <span></span>
                <span></span>
            </span>
        </a>

        <div class="page-title-box">
            <!--<h3>Welcome to admin panel</h3>-->
        </div>

        <a id="mobile_btn" class="mobile_btn" href="#sidebar"><i class="fa fa-bars"></i></a>
        <ul class="nav user-menu">
            <li class="nav-item dropdown dropdown-large">
              <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="javascript:;" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="position-relative">
                  <span class="notify-badge">8</span>
                  <i class="las la-bell"></i>
                </div>
              </a>
              <div class="dropdown-menu dropdown-menu-end">
                <a href="javascript:void(0);">
                  <div class="msg-header">
                    <p class="msg-header-title">Notifications</p>
                    <p class="msg-header-clear ms-auto">Marks all as read</p>
                  </div>
                </a>
                <div class="header-notifications-list ps">
                  <a class="dropdown-item" href="javascript:;">
                    <div class="d-flex align-items-center">
                      <div class="notify text-primary">
                        <i class="las la-bell"></i>
                      </div>
                      <div class="flex-grow-1">
                        <h6 class="msg-name">New Orders <span class="msg-time float-end">2 min
                            ago</span></h6>
                        <p class="msg-info">You have recived new orders</p>
                      </div>
                    </div>
                  </a>
                  <a class="dropdown-item" href="javascript:;">
                    <div class="d-flex align-items-center">
                      <div class="notify text-danger">
                        <i class="las la-bell"></i>
                      </div>
                      <div class="flex-grow-1">
                        <h6 class="msg-name">New Customers<span class="msg-time float-end">14 Sec
                            ago</span></h6>
                        <p class="msg-info">5 new user registered</p>
                      </div>
                    </div>
                  </a>
                  <a class="dropdown-item" href="javascript:;">
                    <div class="d-flex align-items-center">
                      <div class="notify text-success">
                        <i class="las la-bell"></i>
                      </div>
                      <div class="flex-grow-1">
                        <h6 class="msg-name">24 PDF File<span class="msg-time float-end">19 min
                            ago</span></h6>
                        <p class="msg-info">The pdf files generated</p>
                      </div>
                    </div>
                  </a>

                  <a class="dropdown-item" href="javascript:;">
                    <div class="d-flex align-items-center">
                      <div class="notify text-info">
                        <i class="las la-bell"></i>
                      </div>
                      <div class="flex-grow-1">
                        <h6 class="msg-name">New Product Approved <span class="msg-time float-end">2 hrs ago</span></h6>
                        <p class="msg-info">Your new product has approved</p>
                      </div>
                    </div>
                  </a>
                  <a class="dropdown-item" href="javascript:;">
                    <div class="d-flex align-items-center">
                      <div class="notify text-warning">
                        <i class="las la-bell"></i>
                      </div>
                      <div class="flex-grow-1">
                        <h6 class="msg-name">Time Response <span class="msg-time float-end">28 min
                            ago</span></h6>
                        <p class="msg-info">5.1 min avarage time response</p>
                      </div>
                    </div>
                  </a>
                  <a class="dropdown-item" href="javascript:;">
                    <div class="d-flex align-items-center">
                      <div class="notify text-danger">
                        <i class="las la-bell"></i>
                      </div>
                      <div class="flex-grow-1">
                        <h6 class="msg-name">New Comments <span class="msg-time float-end">4 hrs
                            ago</span></h6>
                        <p class="msg-info">New customer comments recived</p>
                      </div>
                    </div>
                  </a>
                  <a class="dropdown-item" href="javascript:;">
                    <div class="d-flex align-items-center">
                      <div class="notify text-primary">
                        <i class="las la-bell"></i>
                      </div>
                      <div class="flex-grow-1">
                        <h6 class="msg-name">New 24 authors<span class="msg-time float-end">1 day
                            ago</span></h6>
                        <p class="msg-info">24 new authors joined last week</p>
                      </div>
                    </div>
                  </a>
                  <a class="dropdown-item" href="javascript:;">
                    <div class="d-flex align-items-center">
                      <div class="notify text-success">
                        <i class="las la-bell"></i>
                      </div>
                      <div class="flex-grow-1">
                        <h6 class="msg-name">Your item is shipped <span class="msg-time float-end">5 hrs
                            ago</span></h6>
                        <p class="msg-info">Successfully shipped your item</p>
                      </div>
                    </div>
                  </a>
                  <a class="dropdown-item" href="javascript:;">
                    <div class="d-flex align-items-center">
                      <div class="notify text-warning">
                        <i class="las la-bell"></i>
                      </div>
                      <div class="flex-grow-1">
                        <h6 class="msg-name">Defense Alerts <span class="msg-time float-end">2 weeks
                            ago</span></h6>
                        <p class="msg-info">45% less alerts last 4 weeks</p>
                      </div>
                    </div>
                  </a>
                <div class="ps__rail-x" style="left: 0px; bottom: 0px;"><div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div></div><div class="ps__rail-y" style="top: 0px; right: 0px;"><div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 0px;"></div></div></div>
                <a href="javascript:;">
                  <div class="text-center msg-footer">View All Notifications</div>
                </a>
              </div>
            </li>

            <li class="nav-item dropdown has-arrow main-drop">
                <a href="#" class="dropdown-toggle nav-link" data-bs-toggle="dropdown">
                    <span class="user-img">
                        @if (Auth::user()->profile_picture)
                            <img src="{{ Storage::url(Auth::user()->profile_picture) }}" alt="">
                        @else
                            <img src="{{ asset('admin_assets/img/profiles/avatar-21.jpg') }}" alt="">
                        @endif
                        <span class="status online"></span>
                    </span>
                    <!--<span>{{ Auth::user()->name }}</span>-->
                </a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="{{ route('admin.profile') }}">My Profile</a>
                    <a class="dropdown-item" href="{{ route('admin.password') }}">Change Password</a>
                    <a class="dropdown-item" href="{{ route('admin.logout') }}">Logout</a>
                </div>
            </li>
        </ul>

        <div class="dropdown mobile-user-menu">
            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i
                    class="fa fa-ellipsis-v"></i></a>
            <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item" href="{{ route('admin.profile') }}">My Profile</a>
                <a class="dropdown-item" href="{{ route('admin.logout') }}">Logout</a>
            </div>
        </div>
    </div>
