<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul class="sidebar-vertical">
                <li class="{{ Request::is('sales-excecutive/dashboard*') ? 'active' : ' ' }}">
                    <a href="{{ route('sales-excecutive.dashboard') }}"><i class="la la-dashboard"></i> <span>Dashboard</span></a>
                </li>
                <li class="submenu">
                    <a href="#"
                        class="{{ Request::is('sales-excecutive/profile*') || Request::is('sales-excecutive/password*') || Request::is('sales-excecutive/detail*') ? 'active' : ' ' }}"><i
                            class="la la-user-cog"></i> <span>Manage Account </span> <span
                            class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        <li class="{{ Request::is('sales-excecutive/profile*') ? 'active' : ' ' }}">
                            <a href="{{ route('sales-excecutive.profile') }}">My Profile</a>
                        </li>
                        <li class="{{ Request::is('sales-excecutive/password*') ? 'active' : ' ' }}">
                            <a href="{{ route('sales-excecutive.password') }}">Change Password</a>
                        </li>

                    </ul>
                </li>


                <li class="{{ Request::is('sales-excecutive/prospects*') ? 'active' : ' ' }}">
                    <a href="{{ route('prospects.index') }}"><i class="la la-users"></i> <span>
                        Prospects</span></a>
                </li>



                {{-- <li class="{{ Request::is('sales-excecutive/members*') ? 'active' : ' ' }}">
                    <a href="{{ route('user.index') }}"><i class="la la-users"></i> <span>Members</span></a>
                </li>
                <li class="{{ Request::is('sales-excecutive/group*') ? 'active' : ' ' }}">
                    <a href="{{ route('group.index') }}"><i class="la la-list"></i> <span>Groups</span></a>
                </li> --}}

                {{-- <li class="menu-title">
                    <span>Content Management System</span>
                </li>
                <li class="submenu">
                    <a href="#" class="{{ Request::is('sales-excecutive/cms/sub-sales-excecutive*') ? 'active' : ' ' }}"><i class="la la-address-card"></i> <span>Admin Panel </span> <span
                            class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        <li class="{{ Request::is('sales-excecutive/cms/sub-sales-excecutive*') ? 'active' : ' ' }}">
                            <a href="{{ route('cms.sub-sales-excecutive.get-started') }}">Get Started Page</a>
                        </li>
                    </ul>
                </li>

                <li class="submenu">
                    <a href="#" class="{{ Request::is('sales-excecutive/cms/user*') ? 'active' : ' '}}"><i class="la la-newspaper"></i> <span>Member Panel </span> <span
                            class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        <li class="{{ Request::is('sales-excecutive/cms/user*') ? 'active' : ' ' }}">
                            <a href="{{ route('cms.user.get-started') }}">Get Started Page</a>
                        </li>
                    </ul>
                </li> --}}

            </ul>
        </div>
    </div>
</div>
