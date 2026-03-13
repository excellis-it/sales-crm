<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul class="sidebar-vertical">
                <li class="{{ Request::is('tender-user/dashboard*') ? 'active' : ' ' }}">
                    <a href="{{ route('tender-user.dashboard') }}"><i class="la la-dashboard"></i>
                        <span>Dashboard</span></a>
                </li>
                <li class="submenu">
                    <a href="#"
                        class="{{ Request::is('tender-user/profile*') || Request::is('tender-user/password*') ? 'active' : ' ' }}"><i
                            class="la la-user-cog"></i> <span>Manage Account </span> <span
                            class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        <li class="{{ Request::is('tender-user/profile*') ? 'active' : ' ' }}">
                            <a href="{{ route('tender-user.profile') }}">My Profile</a>
                        </li>
                        <li class="{{ Request::is('tender-user/password*') ? 'active' : ' ' }}">
                            <a href="{{ route('tender-user.password') }}">Change Password</a>
                        </li>
                    </ul>
                </li>
                <li class="{{ Request::is('tender-user/tender-projects*') ? 'active' : ' ' }}">
                    <a href="{{ route('tender-user.tender-projects.index') }}"><i class="la la-briefcase"></i> <span>
                            Tender Projects</span></a>
                </li>
                <li class="{{ Request::is('tender-user/payments*') ? 'active' : ' ' }}">
                    <a href="{{ route('tender-user.payments.index') }}"><i class="la la-money-bill"></i> <span>
                            Payment History</span></a>
                </li>
                <li class="{{ Request::is('tender-user/tender-statuses*') ? 'active' : ' ' }}">
                    <a href="{{ route('tender-user.tender-statuses.index') }}"><i class="la la-user-tag"></i> <span>
                            Tender Status</span></a>
                </li>
            </ul>
        </div>
    </div>
</div>
