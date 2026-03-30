<div class="row">
    <div class="col-lg-20 col-lg-3 col-md-6">
        <a href="{{ route('sales_managers.index') }}" class="dash-card-link">
            <div class="card dash-card card-primary mb-30" style="min-height: 100px;">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="dash-icon-box bg-pale-orange mr-3">
                            <i class="la la-user-tie"></i>
                        </div>
                        <div>
                            <p class="dash-title mb-0">Sales Manager</p>
                            <h4 class="dash-count mb-0">{{ $count['sales_managers'] ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-lg-20 col-lg-3 col-md-6">
        <a href="{{ route('sales-excecutive.index') }}" class="dash-card-link">
            <div class="card dash-card card-success mb-30" style="min-height: 100px;">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="dash-icon-box bg-pale-green mr-3">
                            <i class="la la-users"></i>
                        </div>
                        <div>
                            <p class="dash-title mb-0">Sales Executive</p>
                            <h4 class="dash-count mb-0">{{ $count['sales_excecutive'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-lg-20 col-lg-3 col-md-6">
        <a href="{{ route('account_managers.index') }}" class="dash-card-link">
            <div class="card dash-card card-info mb-30" style="min-height: 100px;">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="dash-icon-box bg-pale-blue mr-3">
                            <i class="la la-user-tag"></i>
                        </div>
                        <div>
                            <p class="dash-title mb-0">Account Manager</p>
                            <h4 class="dash-count mb-0">{{ $count['account_managers'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-lg-20 col-lg-3 col-md-6">
        <a href="{{ route('business-development-managers.index') }}" class="dash-card-link">
            <div class="card dash-card card-purple mb-30" style="min-height: 100px;">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="dash-icon-box bg-pale-purple mr-3">
                            <i class="la la-briefcase"></i>
                        </div>
                        <div>
                            <p class="dash-title mb-0">BDM</p>
                            <h4 class="dash-count mb-0">{{ $count['bdm'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-lg-20 col-lg-3 col-md-6">
        <a href="{{ route('tender-users.index') }}" class="dash-card-link">
            <div class="card dash-card card-black mb-30" style="min-height: 100px;">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="dash-icon-box bg-pale-black mr-3">
                            <i class="la la-file-invoice-dollar"></i>
                        </div>
                        <div>
                            <p class="dash-title mb-0">Tender Manager</p>
                            <h4 class="dash-count mb-0">{{ $count['tender_managers'] ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>
<div class="row">
    @if ($goal['gross_goals'] > 0)
        <div class="col-lg-20 col-lg-3 col-sm-6">
            <a href="{{ route('sales-projects.index') }}?duration={{ $type }}" class="dash-card-link">
                <div class="stats-card-one dash-card card-primary mb-30" style="min-height: 180px;">
                    <div class="d-flex justify-content-between align-items-center mb-10">
                        <div class="d-flex align-items-center">
                            <div class="dash-icon-box bg-pale-orange mr-2"
                                style="width: 35px; height: 35px; font-size: 16px;">
                                <i class="la la-chart-bar"></i>
                            </div>
                            <p class="dash-title mb-0">Tele Sales Gross Sales</p>
                        </div>
                        @php
                            $target = $goal['gross_goals'] ?? 0;
                            $achieve = $goal['gross_goals_achieve'] ?? 0;
                            $percentage['gross_goals_achieve'] = $target > 0 ? round(($achieve / $target) * 100, 0) : 0;
                        @endphp
                        <span class="badge badge-cyan fs-12">
                            <span class="fw-600">{{ $percentage['gross_goals_achieve'] ?? 0 }}%</span>
                        </span>
                    </div>
                    <h3 class="fs-22 mb-10">${{ $goal['gross_goals_achieve'] ?? 0 }} /
                        ${{ $goal['gross_goals'] ?? 0 }}</h3>
                    ...
                    <div class="mt-15">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="dash-title">Goal Period</span>
                        </div>
                        <div class="progress progress-sm" style="height: 6px;">
                            <div class="progress-bar bg-primary"
                                style="width: {{ min($percentage['gross_goals_achieve'] ?? 0, 100) }}%">
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    @else
        <div class="col-lg-20 col-lg-3 col-sm-6">
            <a href="{{ route('sales-projects.index') }}?duration={{ $type }}" class="dash-card-link">
                <div class="stats-card-one dash-card mb-30" style="min-height: 180px;">
                    <div class="d-flex justify-content-between align-items-center mb-10">
                        <div class="d-flex align-items-center">
                            <div class="dash-icon-box bg-pale-orange mr-2"
                                style="width: 35px; height: 35px; font-size: 16px;">
                                <i class="la la-chart-bar"></i>
                            </div>
                            <p class="dash-title mb-0">Tele Sales Gross Sales</p>
                        </div>
                        <span class="badge badge-cyan fs-12">0%</span>
                    </div>
                    <h3 class="fs-22 mb-10">No Goal Set</h3>
                    <div class="mt-15">
                        <div class="progress progress-sm" style="height: 6px;">
                            <div class="progress-bar bg-primary" style="width: 0%"></div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    @endif

    @if ($goal['net_goals'] > 0)
        <div class="col-lg-20 col-lg-3 col-sm-6">
            <a href="{{ route('sales-projects.index') }}?duration={{ $type }}" class="dash-card-link">
                <div class="stats-card-one dash-card card-danger mb-30" style="min-height: 180px;">
                    <div class="d-flex justify-content-between align-items-center mb-10">
                        <div class="d-flex align-items-center">
                            <div class="dash-icon-box bg-pale-red mr-2"
                                style="width: 35px; height: 35px; font-size: 16px;">
                                <i class="la la-money-bill"></i>
                            </div>
                            <p class="dash-title mb-0">Tele Sales Revenue (Net)</p>
                        </div>
                        @php
                            $target = $goal['net_goals'] ?? 0;
                            $achieve = $goal['net_goals_achieve'] ?? 0;
                            $percentage['net_goals_achieve'] = $target > 0 ? round(($achieve / $target) * 100, 0) : 0;
                        @endphp
                        <span class="badge badge-red fs-12">
                            <span class="fw-600">{{ $percentage['net_goals_achieve'] ?? 0 }}%</span>
                        </span>
                    </div>
                    <h3 class="fs-22 mb-10">${{ $goal['net_goals_achieve'] ?? 0 }} /
                        ${{ $goal['net_goals'] ?? 0 }}</h3>
                    ...
                    <div class="mt-15">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="dash-title">Goal Period</span>
                        </div>
                        <div class="progress progress-sm" style="height: 6px;">
                            <div class="progress-bar bg-danger"
                                style="width: {{ min($percentage['net_goals_achieve'] ?? 0, 100) }}%">
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    @else
        <div class="col-lg-20 col-lg-3 col-sm-6">
            <a href="{{ route('sales-projects.index') }}?duration={{ $type }}" class="dash-card-link">
                <div class="stats-card-one dash-card mb-30" style="min-height: 180px;">
                    <div class="d-flex justify-content-between align-items-center mb-10">
                        <div class="d-flex align-items-center">
                            <div class="dash-icon-box bg-pale-red mr-2"
                                style="width: 35px; height: 35px; font-size: 16px;">
                                <i class="la la-money-bill"></i>
                            </div>
                            <p class="dash-title mb-0">Tele Sales Revenue (Net)</p>
                        </div>
                        <span class="badge badge-red fs-12">0%</span>
                    </div>
                    <h3 class="fs-22 mb-10">No Goal Set</h3>
                    <div class="mt-15">
                        <div class="progress progress-sm" style="height: 6px;">
                            <div class="progress-bar bg-danger" style="width: 0%"></div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    @endif

    <div class="col-lg-20 col-lg-3 col-sm-6">
        <a href="{{ route('sales-projects.index') }}?duration={{ $type }}" class="dash-card-link">
            <div class="stats-card-one dash-card card-info mb-30" style="min-height: 180px;">
                <div class="d-flex justify-content-between align-items-center mb-10">
                    <div class="d-flex align-items-center">
                        <div class="dash-icon-box bg-pale-blue mr-2"
                            style="width: 35px; height: 35px; font-size: 16px;">
                            <i class="la la-user-tag"></i>
                        </div>
                        <p class="dash-title mb-0">AM Revenue</p>
                    </div>
                    ...
                    <span class="badge bg-pale-purple fs-12">
                        <span class="fw-600">{{ $count['account_manager_percentage'] }}%</span>
                    </span>
                </div>
                <h3 class="fs-22 mb-10">${{ $count['account_manager_revenue'] }} /
                    ${{ $count['account_manager_goals'] ?? 0 }}</h3>

                <div class="mt-15">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="dash-title">Goal Period</span>
                    </div>
                    <div class="progress progress-sm" style="height: 6px;">
                        <div class="progress-bar bg-purple"
                            style="width: {{ min($count['account_manager_percentage'], 100) }}%">
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-lg-20 col-lg-3 col-sm-6">
        <a href="{{ route('admin.bdm-projects.index') }}?duration={{ $type }}" class="dash-card-link">
            <div class="stats-card-one dash-card card-black mb-30" style="min-height: 180px;">
                <div class="d-flex justify-content-between align-items-center mb-10">
                    <div class="d-flex align-items-center">
                        <div class="dash-icon-box bg-pale-black mr-2"
                            style="width: 35px; height: 35px; font-size: 16px;">
                            <i class="la la-chart-bar"></i>
                        </div>
                        <p class="dash-title mb-0">BDM Gross Sales</p>
                    </div>
                    <span class="badge bg-pale-black fs-12">
                        <span class="fw-600">{{ $count['bdm_percentage'] }}%</span>
                    </span>
                </div>
                <h3 class="fs-22 mb-10">${{ $count['bdm_gross'] }} / ${{ $count['bdm_gross_goals'] ?? 0 }}</h3>

                <div class="mt-15">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="dash-title">Goal Period</span>
                    </div>
                    <div class="progress progress-sm" style="height: 6px;">
                        <div class="progress-bar bg-black" style="width: {{ min($count['bdm_percentage'], 100) }}%">
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-lg-20 col-lg-3 col-sm-6">
        <a href="{{ route('admin.bdm-projects.index') }}?duration={{ $type }}" class="dash-card-link">
            <div class="stats-card-one dash-card card-success mb-30" style="min-height: 180px;">
                <div class="d-flex justify-content-between align-items-center mb-10">
                    <div class="d-flex align-items-center">
                        <div class="dash-icon-box bg-pale-green mr-2"
                            style="width: 35px; height: 35px; font-size: 16px;">
                            <i class="la la-money-bill"></i>
                        </div>
                        <p class="dash-title mb-0">BDM Revenue (Net)</p>
                    </div>
                    <span class="badge bg-pale-green fs-12">
                        <span class="fw-600">{{ $count['bdm_net_percentage'] }}%</span>
                    </span>
                </div>
                <h3 class="fs-22 mb-10">${{ $count['bdm_net'] }} / ${{ $count['bdm_net_goals'] ?? 0 }}</h3>

                <div class="mt-15">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="dash-title">Goal Period</span>
                    </div>
                    <div class="progress progress-sm" style="height: 6px;">
                        <div class="progress-bar bg-success"
                            style="width: {{ min($count['bdm_net_percentage'], 100) }}%"></div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    {{-- <div class="col-lg-20 col-lg-3 col-sm-6">
        <a href="{{ route('admin.tender-projects.index') }}?duration={{ $type }}" class="dash-card-link">
            <div class="stats-card-one dash-card card-info mb-30" style="min-height: 180px;">
                <div class="d-flex justify-content-between align-items-center mb-10">
                    <div class="d-flex align-items-center">
                        <div class="dash-icon-box bg-pale-blue mr-2"
                            style="width: 35px; height: 35px; font-size: 16px;">
                            <i class="la la-file-invoice-dollar"></i>
                        </div>
                        <p class="dash-title mb-0">Tender Projects</p>
                    </div>
                    <span class="badge badge-cyan fs-12">
                        <span class="fw-600">{{ $count['tender_projects'] ?? 0 }}</span>
                    </span>
                </div>
                <h3 class="fs-22 mb-10">₹{{ number_format($count['tender_projects_value'] ?? 0, 2) }}L</h3>
                <div class="mt-15">
                    <div class="progress progress-sm" style="height: 6px;">
                        <div class="progress-bar bg-info" style="width: 100%"></div>
                    </div>
                </div>
            </div>
        </a>
    </div> --}}
</div>

{{-- BDM Meetings / OnBoard / Tender Quarterly Goal row --}}
<div class="row">
    {{-- BDM Meetings Goal --}}
    <div class="col-lg-4 col-sm-6">
        <a href="{{ route('admin.bdm-projects.index') }}" class="dash-card-link">
            @php
                $bdmMtgGoal = $count['bdm_meetings_goal'] ?? 0;
                $bdmMtgAch = $count['bdm_meetings_achieve'] ?? 0;
                $bdmMtgPct = $count['bdm_meetings_percentage'] ?? 0;
                $bdmMtgColor = $bdmMtgPct >= 80 ? 'success' : ($bdmMtgPct >= 50 ? 'warning' : 'danger');
            @endphp
            <div class="stats-card-one dash-card card-purple mb-30" style="min-height: 180px;">
                <div class="d-flex justify-content-between align-items-center mb-10">
                    <div class="d-flex align-items-center">
                        <div class="dash-icon-box bg-pale-purple mr-2" style="width:35px;height:35px;font-size:16px;">
                            <i class="la la-handshake"></i>
                        </div>
                        <p class="dash-title mb-0">BDM Meetings</p>
                    </div>
                    <span class="badge bg-pale-purple fs-12"><span class="fw-600">{{ $bdmMtgPct }}%</span></span>
                </div>
                @if ($bdmMtgGoal > 0)
                    <h3 class="fs-22 mb-10">{{ $bdmMtgAch }} / {{ $bdmMtgGoal }}</h3>
                @else
                    <h3 class="fs-22 mb-10 text-muted" style="font-size:16px!important;">No Goal Set</h3>
                @endif
                <div class="mt-15">
                    <span class="dash-title">This Month</span>
                    <div class="progress progress-sm mt-1" style="height:6px;">
                        <div class="progress-bar bg-{{ $bdmMtgColor }}" style="width:{{ min($bdmMtgPct, 100) }}%">
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    {{-- BDM OnBoard Goal --}}
    <div class="col-lg-4 col-sm-6">
        <a href="{{ route('admin.bdm-projects.index') }}" class="dash-card-link">
            @php
                $bdmObGoal = $count['bdm_onboard_goal'] ?? 0;
                $bdmObAch = $count['bdm_onboard_achieve'] ?? 0;
                $bdmObPct = $count['bdm_onboard_percentage'] ?? 0;
                $bdmObColor = $bdmObPct >= 80 ? 'success' : ($bdmObPct >= 50 ? 'warning' : 'danger');
            @endphp
            <div class="stats-card-one dash-card card-success mb-30" style="min-height: 180px;">
                <div class="d-flex justify-content-between align-items-center mb-10">
                    <div class="d-flex align-items-center">
                        <div class="dash-icon-box bg-pale-green mr-2" style="width:35px;height:35px;font-size:16px;">
                            <i class="la la-user-check"></i>
                        </div>
                        <p class="dash-title mb-0">BDM On Board</p>
                    </div>
                    <span class="badge bg-pale-green fs-12"><span class="fw-600">{{ $bdmObPct }}%</span></span>
                </div>
                @if ($bdmObGoal > 0)
                    <h3 class="fs-22 mb-10">{{ $bdmObAch }} / {{ $bdmObGoal }}</h3>
                @else
                    <h3 class="fs-22 mb-10 text-muted" style="font-size:16px!important;">No Goal Set</h3>
                @endif
                <div class="mt-15">
                    <span class="dash-title">This Month</span>
                    <div class="progress progress-sm mt-1" style="height:6px;">
                        <div class="progress-bar bg-{{ $bdmObColor }}" style="width:{{ min($bdmObPct, 100) }}%">
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    {{-- Tender Quarterly Goal --}}
    <div class="col-lg-4 col-sm-6">
        <a href="{{ route('admin.tender-projects.index') }}" class="dash-card-link">
            @php
                $tQtrGoal = $count['tender_quarterly_goal'] ?? 0;
                $tQtrAch = $count['tender_quarterly_achieve'] ?? 0;
                $tQtrPct = $count['tender_quarterly_percentage'] ?? 0;
                $tQtrColor = $tQtrPct >= 80 ? 'success' : ($tQtrPct >= 50 ? 'warning' : 'danger');
            @endphp
            <div class="stats-card-one dash-card card-black mb-30" style="min-height: 180px;">
                <div class="d-flex justify-content-between align-items-center mb-10">
                    <div class="d-flex align-items-center">
                        <div class="dash-icon-box bg-pale-black mr-2" style="width:35px;height:35px;font-size:16px;">
                            <i class="la la-file-contract"></i>
                        </div>
                        <p class="dash-title mb-0">Tender Goal</p>
                    </div>
                    <span class="badge bg-pale-black fs-12"><span class="fw-600">{{ $tQtrPct }}%</span></span>
                </div>
                @if ($tQtrGoal > 0)
                    <h3 class="fs-22 mb-10">₹{{ number_format($tQtrAch, 2) }}L / ₹{{ number_format($tQtrGoal, 2) }}L
                    </h3>
                @else
                    <h3 class="fs-22 mb-10 text-muted" style="font-size:16px!important;">No Goal Set</h3>
                @endif
                <div class="mt-15">
                    <span class="dash-title">{{ $count['tender_current_quarter'] ?? '' }}</span>
                    <div class="progress progress-sm mt-1" style="height:6px;">
                        <div class="progress-bar bg-{{ $tQtrColor }}" style="width:{{ min($tQtrPct, 100) }}%">
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>
<div class="row">
    <div class="col-lg-20 col-lg-3 col-md-6">
        <a href="{{ route('admin.prospects.index') }}?duration={{ $type }}" class="dash-card-link">
            <div class="card dash-card mb-30" style="min-height: 100px;">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="dash-icon-box bg-pale-blue mr-3">
                            <i class="la la-book-open"></i>
                        </div>
                        <div>
                            <p class="dash-title mb-0" style="white-space: nowrap;">No of Prospects</p>
                            <h4 class="dash-count mb-0">{{ $count['prospects'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-lg-20 col-lg-3 col-md-6">
        <a href="{{ route('admin.prospects.index') }}?status=Win&duration={{ $type }}"
            class="dash-card-link">
            <div class="card dash-card mb-30" style="min-height: 100px;">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="dash-icon-box bg-pale-green mr-3">
                            <i class="la la-check-circle"></i>
                        </div>
                        <div>
                            <p class="dash-title mb-0" style="white-space: nowrap;">Completed Prospects</p>
                            <h4 class="dash-count mb-0">{{ $count['win'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-lg-20 col-lg-3 col-md-6">
        <a href="{{ route('customers.index') }}?duration={{ $type }}" class="dash-card-link">
            <div class="card dash-card mb-30" style="min-height: 100px;">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="dash-icon-box bg-pale-orange mr-3">
                            <i class="la la-users"></i>
                        </div>
                        <div>
                            <p class="dash-title mb-0" style="white-space: nowrap;">No of Customers</p>
                            <h4 class="dash-count mb-0">{{ $top_customers->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-lg-20 col-lg-3 col-md-6">
        <a href="{{ route('sales-projects.index') }}?duration={{ $type }}" class="dash-card-link">
            <div class="card dash-card mb-30" style="min-height: 100px;">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="dash-icon-box bg-pale-purple mr-3">
                            <i class="la la-rocket"></i>
                        </div>
                        <div>
                            <p class="dash-title mb-0" style="white-space: nowrap;">No of Projects</p>
                            <h4 class="dash-count mb-0">{{ $count['projects'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-lg-20 col-lg-3 col-md-6">
        <a href="{{ route('admin.prospects.index') }}?status=Follow+Up&duration={{ $type }}"
            class="dash-card-link">
            <div class="card dash-card mb-30" style="min-height: 100px;">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="dash-icon-box bg-pale-orange mr-3">
                            <i class="la la-phone-volume"></i>
                        </div>
                        <div>
                            <p class="dash-title mb-0" style="white-space: nowrap;">Follow Up Prospect</p>
                            <h4 class="dash-count mb-0">{{ $count['follow_up'] ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>
