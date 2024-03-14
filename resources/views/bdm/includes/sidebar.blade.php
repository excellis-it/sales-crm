<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul class="sidebar-vertical">
                <li class="{{ Request::is('bdm/dashboard*') ? 'active' : ' ' }}">
                    <a href="{{ route('bdm.dashboard') }}"><i class="la la-dashboard"></i>
                        <span>Dashboard</span></a>
                </li>
                <li class="submenu">
                    <a href="#"
                        class="{{ Request::is('bdm/profile*') || Request::is('bdm/password*') || Request::is('bdm/detail*') ? 'active' : ' ' }}"><i
                            class="la la-user-cog"></i> <span>Manage Account </span> <span
                            class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        <li class="{{ Request::is('bdm/profile*') ? 'active' : ' ' }}">
                            <a href="{{ route('bdm.profile') }}">My Profile</a>
                        </li>
                        <li class="{{ Request::is('bdm/password*') ? 'active' : ' ' }}">
                            <a href="{{ route('bdm.password') }}">Change Password</a>
                        </li>

                    </ul>
                </li>
                {{-- My sales excecutive --}}
                <li class="{{ Request::is('bdm/bde*') ? 'active' : ' ' }}">
                    <a href="{{ route('bde.index') }}"><i class="la la-user-tie"></i> <span>
                            BDE</span></a>
                </li>
                {{-- My project --}}
                <li class="{{ Request::is('bdm/projects*') ? 'active' : ' ' }}">
                    <a href="{{ route('bdm.projects.index') }}"><i class="la la-briefcase"></i> <span>My
                            Projects</span></a>
                </li>

                {{-- My prospect --}}
                <li class="{{ Request::is('bdm/prospects*') ? 'active' : ' ' }}">
                    <a href="{{ route('bdm.prospects.index') }}"><i class="la la-book-reader"></i> <span>My
                            Prospects</span></a>
                </li>
                <li class="{{ Request::is('bdm/transfer-taken*') ? 'active' : ' ' }}">
                    <a href="{{ route('bdm.transfer-taken.index') }}"><i class="la la-paper-plane"></i> <span>
                            Prospect Taken</span></a>
                </li>


                {{-- <li class="{{ Request::is('bdm/members*') ? 'active' : ' ' }}">
                    <a href="{{ route('user.index') }}"><i class="la la-users"></i> <span>Members</span></a>
                </li>
                <li class="{{ Request::is('bdm/group*') ? 'active' : ' ' }}">
                    <a href="{{ route('group.index') }}"><i class="la la-list"></i> <span>Groups</span></a>
                </li> --}}

                {{-- <li class="menu-title">
                    <span>Content Management System</span>
                </li>
                <li class="submenu">
                    <a href="#" class="{{ Request::is('bdm/cms/sub-bdm*') ? 'active' : ' ' }}"><i class="la la-address-card"></i> <span>Admin Panel </span> <span
                            class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        <li class="{{ Request::is('bdm/cms/sub-bdm*') ? 'active' : ' ' }}">
                            <a href="{{ route('cms.sub-bdm.get-started') }}">Get Started Page</a>
                        </li>
                    </ul>
                </li>

                <li class="submenu">
                    <a href="#" class="{{ Request::is('bdm/cms/user*') ? 'active' : ' '}}"><i class="la la-newspaper"></i> <span>Member Panel </span> <span
                            class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        <li class="{{ Request::is('bdm/cms/user*') ? 'active' : ' ' }}">
                            <a href="{{ route('cms.user.get-started') }}">Get Started Page</a>
                        </li>
                    </ul>
                </li> --}}

            </ul>
        </div>
    </div>
</div>
