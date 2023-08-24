<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul class="sidebar-vertical">
              
                <li class="submenu">
                    <a href="#"
                        class="{{ Request::is('account-manager/profile*') || Request::is('account-manager/password*') || Request::is('account-manager/detail*') ? 'active' : ' ' }}"><i
                            class="la la-user-cog"></i> <span>Manage Account </span> <span
                            class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        <li class="{{ Request::is('account-manager/profile*') ? 'active' : ' ' }}">
                            <a href="{{ route('account-manager.profile') }}">My Profile</a>
                        </li>
                        <li class="{{ Request::is('account-manager/password*') ? 'active' : ' ' }}">
                            <a href="{{ route('account-manager.password') }}">Change Password</a>
                        </li>

                    </ul>
                </li>
              
                <li class="{{ Request::is('account-manager/projects*') ? 'active' : ' ' }}">
                    <a href="{{ route('account-manager.projects.index') }}"><i class="la la-credit-card"></i> <span>Projects</span></a>
                </li>



                {{-- <li class="{{ Request::is('account-manager/members*') ? 'active' : ' ' }}">
                    <a href="{{ route('user.index') }}"><i class="la la-users"></i> <span>Members</span></a>
                </li>
                <li class="{{ Request::is('account-manager/group*') ? 'active' : ' ' }}">
                    <a href="{{ route('group.index') }}"><i class="la la-list"></i> <span>Groups</span></a>
                </li> --}}

                {{-- <li class="menu-title">
                    <span>Content Management System</span>
                </li>
                <li class="submenu">
                    <a href="#" class="{{ Request::is('account-manager/cms/sub-account-manager*') ? 'active' : ' ' }}"><i class="la la-address-card"></i> <span>Admin Panel </span> <span
                            class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        <li class="{{ Request::is('account-manager/cms/sub-account-manager*') ? 'active' : ' ' }}">
                            <a href="{{ route('cms.sub-account-manager.get-started') }}">Get Started Page</a>
                        </li>                   
                    </ul>
                </li>

                <li class="submenu">
                    <a href="#" class="{{ Request::is('account-manager/cms/user*') ? 'active' : ' '}}"><i class="la la-newspaper"></i> <span>Member Panel </span> <span
                            class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        <li class="{{ Request::is('account-manager/cms/user*') ? 'active' : ' ' }}">
                            <a href="{{ route('cms.user.get-started') }}">Get Started Page</a>
                        </li>                  
                    </ul>
                </li> --}}

            </ul>
        </div>
    </div>
</div>
