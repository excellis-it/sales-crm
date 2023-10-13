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


                <li class="{{ Request::is('sales-excecutive/projects*') ? 'active' : ' ' }}">
                    <a href="{{ route('sales-excecutive.projects.index') }}"><i class="la la-book-open"></i> <span>
                             Projects </span></a>
                </li>

            </ul>
        </div>
    </div>
</div>
