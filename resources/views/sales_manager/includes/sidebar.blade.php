<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul class="sidebar-vertical">
                <li class="{{ Request::is('sales-manager/dashboard*') ? 'active' : ' ' }}">
                    <a href="{{ route('sales-manager.dashboard') }}"><i class="la la-dashboard"></i> <span>Dashboard</span></a>
                </li>
                <li class="submenu">
                    <a href="#"
                        class="{{ Request::is('sales-manager/profile*') || Request::is('sales-manager/password*') || Request::is('sales-manager/detail*') ? 'active' : ' ' }}"><i
                            class="la la-user-cog"></i> <span>Manage Account </span> <span
                            class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        <li class="{{ Request::is('sales-manager/profile*') ? 'active' : ' ' }}">
                            <a href="{{ route('sales-manager.profile') }}">My Profile</a>
                        </li>
                        <li class="{{ Request::is('sales-manager/password*') ? 'active' : ' ' }}">
                            <a href="{{ route('sales-manager.password') }}">Change Password</a>
                        </li>

                    </ul>
                </li>
               
                {{-- My project --}}
                <li class="{{ Request::is('sales-manager/projects*') ? 'active' : ' ' }}">
                    <a href="{{ route('projects.index') }}"><i class="la la-briefcase"></i> <span>My Projects</span></a>
                </li>

                {{-- My prospect --}}
                <li class="{{ Request::is('sales-manager/prospects*') ? 'active' : ' ' }}">
                    <a href="{{ route('sales-manager.prospects.index') }}"><i class="la la-user"></i> <span>My Prospects</span></a>


                {{-- <li class="{{ Request::is('sales-manager/members*') ? 'active' : ' ' }}">
                    <a href="{{ route('user.index') }}"><i class="la la-users"></i> <span>Members</span></a>
                </li>
                <li class="{{ Request::is('sales-manager/group*') ? 'active' : ' ' }}">
                    <a href="{{ route('group.index') }}"><i class="la la-list"></i> <span>Groups</span></a>
                </li> --}}

                {{-- <li class="menu-title">
                    <span>Content Management System</span>
                </li>
                <li class="submenu">
                    <a href="#" class="{{ Request::is('sales-manager/cms/sub-sales-manager*') ? 'active' : ' ' }}"><i class="la la-address-card"></i> <span>Admin Panel </span> <span
                            class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        <li class="{{ Request::is('sales-manager/cms/sub-sales-manager*') ? 'active' : ' ' }}">
                            <a href="{{ route('cms.sub-sales-manager.get-started') }}">Get Started Page</a>
                        </li>                   
                    </ul>
                </li>

                <li class="submenu">
                    <a href="#" class="{{ Request::is('sales-manager/cms/user*') ? 'active' : ' '}}"><i class="la la-newspaper"></i> <span>Member Panel </span> <span
                            class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        <li class="{{ Request::is('sales-manager/cms/user*') ? 'active' : ' ' }}">
                            <a href="{{ route('cms.user.get-started') }}">Get Started Page</a>
                        </li>                  
                    </ul>
                </li> --}}

            </ul>
        </div>
    </div>
</div>
