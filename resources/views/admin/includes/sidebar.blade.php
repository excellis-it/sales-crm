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
                <li class="{{ Request::is('admin/ip*') ? 'active' : ' ' }}">
                    <a href="{{ route('ips.index') }}"><i class="la la-globe"></i> <span>
                            Manage IP</span></a>
                </li>
                <li class="menu-title">
                    <span>User Management</span>
                </li>
                <li class="{{ Request::is('admin/customers*') ? 'active' : ' ' }}">
                    <a href="{{ route('customers.index') }}"><i class="la la-users"></i> <span>
                            Customers</span></a>
                </li>

                <li class="{{ Request::is('admin/goals*') ? 'active' : ' ' }}">
                    <a href="{{ route('goals.index') }}"><i class="la la-bullseye"></i> <span>
                            Users Goals </span></a>
                </li>
                <li class="submenu">
                    <a href="#"
                        class="{{ Request::is('admin/tender-users*') || Request::is('admin/tender-projects*') || Request::is('admin/tender-statuses*') ? 'active' : ' ' }}"><i
                            class="la la-user-tag"></i> <span> Tender Management </span> <span
                            class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        <li class="{{ Request::is('admin/tender-users*') ? 'active' : ' ' }}">
                            <a href="{{ route('tender-users.index') }}">Tender Manager</a>
                        </li>
                        <li class="{{ Request::is('admin/tender-projects*') ? 'active' : ' ' }}">
                            <a href="{{ route('admin.tender-projects.index') }}">Tender Project</a>
                        </li>
                        <li class="{{ Request::is('admin/tender-statuses*') ? 'active' : ' ' }}">
                            <a href="{{ route('admin.tender-statuses.index') }}">Tender Status</a>
                        </li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="#"
                        class="{{ Request::is('admin/sales_managers*') || Request::is('admin/account_managers*') || Request::is('admin/sales-excecutive*') ? 'active' : ' ' }}"><i
                            class="la la-users"></i> <span> Telecaller </span> <span class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        <li class="{{ Request::is('admin/sales_managers*') ? 'active' : ' ' }}">
                            <a href="{{ route('sales_managers.index') }}">Sales managers</a>
                        </li>
                        <li class="{{ Request::is('admin/account_managers*') ? 'active' : ' ' }}">
                            <a href="{{ route('account_managers.index') }}">Account managers</a>
                        </li>
                        <li class="{{ Request::is('admin/sales-excecutive*') ? 'active' : ' ' }}">
                            <a href="{{ route('sales-excecutive.index') }}">Sales Excecutive</a>
                        </li>
                    </ul>
                </li>

                <li class="submenu">
                    <a href="#"
                        class="{{ Request::is('admin/business-development-managers*') || Request::is('admin/business-development-excecutive*') ? 'active' : ' ' }}"><i
                            class="la la-briefcase"></i> <span> Business Development </span> <span
                            class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        <li class="{{ Request::is('admin/business-development-managers*') ? 'active' : ' ' }}">
                            <a href="{{ route('business-development-managers.index') }}">BDM</a>
                        </li>
                        <li class="{{ Request::is('admin/business-development-excecutive*') ? 'active' : ' ' }}">
                            <a href="{{ route('business-development-excecutive.index') }}">BDE</a>
                        </li>
                    </ul>
                </li>
                <li class="menu-title">
                    <span>Project Management</span>
                </li>

                {{-- <li class="{{ Request::is('admin/followups*') ? 'active' : ' ' }}">
                    <a href="{{ route('admin.followups.index') }}"><i class="la la-arrow-up"></i> <span>Follow-Up</span></a>
                </li> --}}

                <li class="submenu">
                    <a href="#"
                        class="{{ Request::is('admin/sales-projects*') || Request::is('admin/prospects*') ? 'active' : ' ' }}"><i
                            class="la la-book-open"></i> <span> Telecaller Pipeline </span> <span
                            class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        <li class="{{ Request::is('admin/sales-projects*') ? 'active' : ' ' }}">
                            <a href="{{ route('sales-projects.index') }}">Projects</a>
                        </li>
                        <li class="{{ Request::is('admin/prospects*') ? 'active' : ' ' }}">
                            <a href="{{ route('admin.prospects.index') }}">Prospects</a>
                        </li>
                    </ul>
                </li>

                <li class="submenu">
                    <a href="#"
                        class="{{ Request::is('admin/bdm-projects*') || Request::is('admin/bdm-prospects*') ? 'active' : ' ' }}"><i
                            class="la la-rocket"></i> <span> BDM Pipeline </span> <span
                            class="menu-arrow"></span></a>
                    <ul style="display: none;">
                        <li class="{{ Request::is('admin/bdm-projects*') ? 'active' : ' ' }}">
                            <a href="{{ route('admin.bdm-projects.index') }}">BDM Projects</a>
                        </li>
                        <li class="{{ Request::is('admin/bdm-prospects*') ? 'active' : ' ' }}">
                            <a href="{{ route('admin.bdm-prospects.index') }}">BDM Prospects</a>
                        </li>
                    </ul>
                </li>

                <li class="menu-title">
                    <span>Payment Management</span>
                </li>

                <li class="{{ Request::is('admin/payments*') ? 'active' : ' ' }}">
                    <a href="{{ route('admin.payments.list') }}"><i class="fa fa-money-bill"></i>
                        <span>Payments</span></a>
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
