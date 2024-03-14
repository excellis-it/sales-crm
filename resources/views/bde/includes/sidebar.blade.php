<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul class="sidebar-vertical">
                <li class="{{ Request::is('bde/dashboard*') ? 'active' : ' ' }}">
                    <a href="{{ route('bde.dashboard') }}"><i class="la la-dashboard"></i> <span>Dashboard</span></a>
                </li>
                <li class="submenu">
                    <a href="#"
                        class="{{ Request::is('bde/profile*') || Request::is('bde/password*') || Request::is('bde/detail*') ? 'active' : ' ' }}"><i
                            class="la la-user-cog"></i> <span>Manage Account </span> <span
                            class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        <li class="{{ Request::is('bde/profile*') ? 'active' : ' ' }}">
                            <a href="{{ route('bde.profile') }}">My Profile</a>
                        </li>
                        <li class="{{ Request::is('bde/password*') ? 'active' : ' ' }}">
                            <a href="{{ route('bde.password') }}">Change Password</a>
                        </li>

                    </ul>
                </li>


                <li class="{{ Request::is('bde/bde-prospects*') ? 'active' : ' ' }}">
                    <a href="{{ route('bde-prospects.index') }}"><i class="la la-users"></i> <span>
                        Prospects</span></a>
                </li>
                <li class="{{ Request::is('bde/transfer-taken*') ? 'active' : ' ' }}">
                    <a href="{{ route('bde.transfer-taken.index') }}"><i class="la la-paper-plane"></i> <span>
                            Prospect Taken</span></a>
                </li>
                <li class="{{ Request::is('bde/bde-projects*') ? 'active' : ' ' }}">
                    <a href="{{ route('bde-projects.index') }}"><i class="la la-book-open"></i> <span>
                             Projects </span></a>
                </li>

            </ul>
        </div>
    </div>
</div>
