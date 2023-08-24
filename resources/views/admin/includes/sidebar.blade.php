<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul class="sidebar-vertical">
                <li class="{{ Request::is('admin/dashboard*') ? 'active' : ' ' }}">
                    <a href="{{ route('admin.dashboard') }}"><i class="la la-dashboard"></i> <span>Dashboard</span></a>
                </li>
                <li class="submenu">
                    <a href="#"
                        class="{{ Request::is('admin/profile*') || Request::is('admin/password*') || Request::is('admin/detail*') ? 'active' : ' ' }}"><i
                            class="la la-user-cog"></i> <span>Manage Account </span> <span
                            class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        <li class="{{ Request::is('admin/profile*') ? 'active' : ' ' }}">
                            <a href="{{ route('admin.profile') }}">My Profile</a>
                        </li>
                        <li class="{{ Request::is('admin/password*') ? 'active' : ' ' }}">
                            <a href="{{ route('admin.password') }}">Change Password</a>
                        </li>

                    </ul>
                </li>
                <li class="menu-title">
                    <span>User Management</span>
                </li>


                <li class="{{ Request::is('admin/sales_managers*') ? 'active' : ' ' }}">
                    <a href="{{ route('sales_managers.index') }}"><i class="la la-users"></i> <span>
                            Sales managers</span></a>
                </li>

                <li class="{{ Request::is('admin/account_managers*') ? 'active' : ' ' }}">
                    <a href="{{ route('account_managers.index') }}"><i class="la la-user-tie"></i> <span>
                            Account managers</span></a>
                </li>

                <li class="{{ Request::is('admin/sales-excecutive*') ? 'active' : ' ' }}">
                    <a href="{{ route('sales-excecutive.index') }}"><i class="la la-user-friends"></i> <span>
                        Sales Excecutive</span></a>
                </li>

                    <li class="menu-title">
                    <span>Project Management</span>
                </li>


                <li class="{{ Request::is('admin/sales-projects*') ? 'active' : ' ' }}">
                    <a href="{{ route('sales-projects.index') }}"><i class="la la-book-open"></i> <span>
                             Projects </span></a>
                </li>

                <li class="{{ Request::is('admin/prospects*') ? 'active' : ' ' }}">
                    <a href="{{ route('admin.prospects.index') }}"><i class="la la-book-reader"></i> <span>
                             Prospects </span></a>
                </li>



                {{-- <li class="{{ Request::is('admin/members*') ? 'active' : ' ' }}">
                    <a href="{{ route('user.index') }}"><i class="la la-users"></i> <span>Members</span></a>
                </li>
                <li class="{{ Request::is('admin/group*') ? 'active' : ' ' }}">
                    <a href="{{ route('group.index') }}"><i class="la la-list"></i> <span>Groups</span></a>
                </li> --}}

                {{-- <li class="menu-title">
                    <span>Content Management System</span>
                </li>
                <li class="submenu">
                    <a href="#" class="{{ Request::is('admin/cms/sub-admin*') ? 'active' : ' ' }}"><i class="la la-address-card"></i> <span>Admin Panel </span> <span
                            class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        <li class="{{ Request::is('admin/cms/sub-admin*') ? 'active' : ' ' }}">
                            <a href="{{ route('cms.sub-admin.get-started') }}">Get Started Page</a>
                        </li>                   
                    </ul>
                </li>

                <li class="submenu">
                    <a href="#" class="{{ Request::is('admin/cms/user*') ? 'active' : ' '}}"><i class="la la-newspaper"></i> <span>Member Panel </span> <span
                            class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        <li class="{{ Request::is('admin/cms/user*') ? 'active' : ' ' }}">
                            <a href="{{ route('cms.user.get-started') }}">Get Started Page</a>
                        </li>                  
                    </ul>
                </li> --}}

            </ul>
        </div>
    </div>
</div>
