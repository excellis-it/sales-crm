<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul class="sidebar-vertical">
                <li class="{{ Request::is('sales_manager/dashboard*') ? 'active' : ' ' }}">
                    <a href="{{ route('sales_manager.dashboard') }}"><i class="la la-dashboard"></i> <span>Dashboard</span></a>
                </li>
                <li class="submenu">
                    <a href="#"
                        class="{{ Request::is('sales_manager/profile*') || Request::is('sales_manager/password*') || Request::is('sales_manager/detail*') ? 'active' : ' ' }}"><i
                            class="la la-user-cog"></i> <span>Manage Account </span> <span
                            class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        <li class="{{ Request::is('sales_manager/profile*') ? 'active' : ' ' }}">
                            <a href="{{ route('sales_manager.profile') }}">My Profile</a>
                        </li>
                        <li class="{{ Request::is('sales_manager/password*') ? 'active' : ' ' }}">
                            <a href="{{ route('sales_manager.password') }}">Change Password</a>
                        </li>

                    </ul>
                </li>
                <li class="menu-title">
                    <span>User Management</span>
                </li>


                <li class="{{ Request::is('sales_manager/sales_managers*') ? 'active' : ' ' }}">
                    <a href="{{ route('sales_managers.index') }}"><i class="la la-users"></i> <span>
                            Sales managers</span></a>
                </li>



                {{-- <li class="{{ Request::is('sales_manager/members*') ? 'active' : ' ' }}">
                    <a href="{{ route('user.index') }}"><i class="la la-users"></i> <span>Members</span></a>
                </li>
                <li class="{{ Request::is('sales_manager/group*') ? 'active' : ' ' }}">
                    <a href="{{ route('group.index') }}"><i class="la la-list"></i> <span>Groups</span></a>
                </li> --}}

                {{-- <li class="menu-title">
                    <span>Content Management System</span>
                </li>
                <li class="submenu">
                    <a href="#" class="{{ Request::is('sales_manager/cms/sub-sales_manager*') ? 'active' : ' ' }}"><i class="la la-address-card"></i> <span>Admin Panel </span> <span
                            class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        <li class="{{ Request::is('sales_manager/cms/sub-sales_manager*') ? 'active' : ' ' }}">
                            <a href="{{ route('cms.sub-sales_manager.get-started') }}">Get Started Page</a>
                        </li>                   
                    </ul>
                </li>

                <li class="submenu">
                    <a href="#" class="{{ Request::is('sales_manager/cms/user*') ? 'active' : ' '}}"><i class="la la-newspaper"></i> <span>Member Panel </span> <span
                            class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        <li class="{{ Request::is('sales_manager/cms/user*') ? 'active' : ' ' }}">
                            <a href="{{ route('cms.user.get-started') }}">Get Started Page</a>
                        </li>                  
                    </ul>
                </li> --}}

            </ul>
        </div>
    </div>
</div>
