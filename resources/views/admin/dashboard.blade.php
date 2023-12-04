@extends('admin.layouts.master')
@section('title')
    Dashboard - {{ env('APP_NAME') }} admin
@endsection
@push('styles')
    <style>
        .dataTables_filter {
            margin-bottom: 10px !important;
        }
        #canvas {
    height: 20rem;
}
        .chartjs-custom {
    position: relative;
    overflow: hidden;
    margin-right: auto;
    margin-left: auto
}

.hs-chartjs-tooltip-wrap {
    position: absolute;
    z-index: 3;
    transition: opacity .2s ease-in-out, left .2s ease, top .2s ease
}

.hs-chartjs-tooltip {
    position: relative;
    font-size: .75rem;
    background-color: #132144;
    border-radius: .3125rem;
    padding: .54688rem .875rem;
    transition: opacity .2s ease-in-out, left .2s ease, top .2s ease, top 0s
}

.hs-chartjs-tooltip::before {
    position: absolute;
    left: calc(50% - .5rem);
    bottom: -.4375rem;
    width: 1rem;
    height: .5rem;
    content: "";
    background-image: url("data:image/svg+xml,%3Csvg width='1rem' height='0.5rem' xmlns='http://www.w3.org/2000/svg' x='0px' y='0px' viewBox='0 0 50 22.49'%3E%3Cpath fill='%23132144' d='M0,0h50L31.87,19.65c-3.45,3.73-9.33,3.79-12.85,0.13L0,0z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: center center;
    background-size: 1rem .5rem
}

.hs-chartjs-tooltip-left {
    left: -130%
}

.hs-chartjs-tooltip-left::before {
    top: 50%;
    -webkit-transform: translateY(-50%);
    transform: translateY(-50%);
    right: -.6875rem;
    left: auto;
    -webkit-transform: translateY(-50%) rotate(270deg);
    transform: translateY(-50%) rotate(270deg)
}

.hs-chartjs-tooltip-right {
    left: 30%
}

.hs-chartjs-tooltip-right::before {
    top: 50%;
    -webkit-transform: translateY(-50%);
    transform: translateY(-50%);
    left: -.6875rem;
    right: auto;
    -webkit-transform: translateY(-50%) rotate(90deg);
    transform: translateY(-50%) rotate(90deg)
}

.hs-chartjs-tooltip-header {
    color: rgba(255, 255, 255, .7);
    font-weight: 600;
    white-space: nowrap
}

.hs-chartjs-tooltip-body {
    color: #fff
}

.chartjs-doughnut-custom {
    position: relative
}

.chartjs-doughnut-custom-stat {
    position: absolute;
    top: 8rem;
    left: 50%;
    -webkit-transform: translateX(-50%);
    transform: translateX(-50%)
}

.chartjs-matrix-custom {
    position: relative
}

.hs-chartjs-matrix-legend {
    display: inline-block;
    position: relative;
    height: 2.5rem;
    list-style: none;
    padding-left: 0
}

.hs-chartjs-matrix-legend-item {
    width: .625rem;
    height: .625rem;
    display: inline-block
}

.hs-chartjs-matrix-legend-min {
    position: absolute;
    left: 0;
    bottom: 0
}

.hs-chartjs-matrix-legend-max {
    position: absolute;
    right: 0;
    bottom: 0
}
    </style>
@endpush

@section('content')
    @php
        $totalProspects = $count['prospects'] == 0 ? 1 : $count['prospects'];
        $winProspects = $count['win'] == 0 ? 1 : $count['win'];
        $percentage['win'] = round(($winProspects / $totalProspects) * 100);
        $percentage['follow_up'] = round(($count['follow_up'] / $totalProspects) * 100);
        $percentage['sent_proposal'] = round(($count['sent_proposal'] / $totalProspects) * 100);
        $percentage['close'] = round(($count['close'] / $totalProspects) * 100);
    @endphp
    <section id="loading">
        <div id="loading-content"></div>
    </section>
    <div class="page-wrapper">

        <div class="content container-fluid">

            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">Welcome Admin Panel!</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="card mb-30">
                <div class="card-body" style="position: relative;">
                    <div class="row align-items-center mb-4">
                        <div class="col-lg-6">
                            <h5 class="card-title mb-0">Sales Analytics</h5>
                        </div>
                        <div class="col-lg-6 text-end">
                            <select class="custom-select w-auto" name="">
                                <option value="overall">Overall statistics</option>
                                <option value="today">Todays Statistics</option>
                                <option value="this_month">This Months Statistics</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="card mb-30">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <span>Sales Manager</span>
                                        </div>

                                        <span class="fw-600">{{ $count['users'] ?? 0 }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="card mb-30">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <span>Sales Executive</span>
                                        </div>
                                        <span class="fw-600">{{ $count['sales_excecutive'] }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="card mb-30">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <span>Account Manager</span>
                                        </div>
                                        <span class="fw-600">{{ $count['account_managers'] }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="card mb-30">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <span>BDM</span>
                                        </div>
                                        <span class="fw-600">{{ $count['bdm'] }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        @if ($goal['gross_goals'])
                            <div class="col-lg-3 col-sm-6">
                                <div class="stats-card-one mb-30">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <p class="mb-10 line-height-1">Gross Sales</p>
                                            <h3 class="fs-25">
                                                {{ $goal['gross_goals'] ? '$' . $goal['gross_goals'] : 'N/A' }} /
                                                ${{ $goal['gross_goals_achieve'] ?? 0 }} </h3>
                                        </div>
                                        <?php
                                        $target = $goal['gross_goals'] ?? 0;
                                        $achieve = $goal['gross_goals_achieve'] ?? 0;
                                        // round percentage
                                        $percentage['gross_goals_achieve'] = round(($achieve / $target) * 100, 0);
                                        ?>
                                        <span class="badge badge-cyan fs-12">
                                            <i class="icofont-swoosh-up"></i>
                                            <span class="fw-600 m-l-5">{{ $percentage['gross_goals_achieve'] ?? 0 }}%</span>
                                        </span>
                                    </div>

                                    <div class="mt-15">
                                        <div class="d-flex justify-content-between">
                                            <div class="d-flex align-items-center">
                                                <span class="monthly_goal">Monthly Goal</span>
                                            </div>
                                            <span class="fw-600">{{ $percentage['gross_goals_achieve'] ?? 0 }}%</span>
                                        </div>

                                        <div class="progress progress-sm mt-1">
                                            <div class="progress-bar bg-primary"
                                                style="width: {{ $percentage['gross_goals_achieve'] ?? 0 }}%">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="col-lg-3 col-sm-6">
                                <div class="stats-card-one mb-30">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <p class="mb-10 line-height-1">Gross Sales</p>
                                            <h3 class="fs-25"> No Gross Sales Set</h3>
                                        </div>
                                        <span class="badge badge-cyan fs-12">
                                            <i class="icofont-swoosh-up"></i>
                                            <span class="fw-600 m-l-5">0%</span>
                                        </span>
                                    </div>

                                    <div class="mt-15">
                                        <div class="d-flex justify-content-between">
                                            <div class="d-flex align-items-center">
                                                <span class="monthly_goal">Monthly Goal</span>
                                            </div>
                                            <span class="fw-600 monthly_goal">0%</span>
                                        </div>

                                        <div class="progress progress-sm mt-1">
                                            <div class="progress-bar bg-primary" style="width: 0%">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if ($goal['net_goals'])
                            <div class="col-lg-3 col-sm-6">
                                <div class="stats-card-one mb-30">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <p class="mb-10 line-height-1">Revenue</p>
                                            <h3 class="fs-25"> {{ $goal['net_goals'] ? '$' . $goal['net_goals'] : 'N/A' }}
                                                ${{ $goal['net_goals_achieve'] ?? 0 }} </h3>
                                        </div>
                                        <?php
                                        $target = $goal['net_goals'] ?? 0;
                                        $achieve = $goal['net_goals_achieve'] ?? 0;
                                        // round percentage
                                        $percentage['net_goals_achieve'] = round(($achieve / $target) * 100, 0);
                                        ?>
                                        <span class="badge badge-cyan font-size-12">
                                            <i class="icofont-swoosh-up"></i>
                                            <span class="fw-600 m-l-5">{{ $percentage['net_goals_achieve'] ?? 0 }}%</span>
                                        </span>
                                    </div>

                                    <div class="mt-15">
                                        <div class="d-flex justify-content-between">
                                            <div class="d-flex align-items-center">
                                                <span class="monthly_goal">Monthly Goal</span>
                                            </div>
                                            <span
                                                class="fw-600 monthly_goal">{{ $percentage['net_goals_achieve'] ?? 0 }}%</span>
                                        </div>

                                        <div class="progress progress-sm mt-1">
                                            <div class="progress-bar bg-danger"
                                                style="width: {{ $percentage['net_goals_achieve'] ?? 0 }}%">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="col-lg-3 col-sm-6">
                                <div class="stats-card-one mb-30">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <p class="mb-10 line-height-1">Revenue</p>
                                            <h3 class="fs-25"> No Goals Set </h3>
                                        </div>
                                        <span class="badge badge-cyan font-size-12">
                                            <i class="icofont-swoosh-up"></i>
                                            <span class="fw-600 m-l-5">0%</span>
                                        </span>
                                    </div>

                                    <div class="mt-15">
                                        <div class="d-flex justify-content-between">
                                            <div class="d-flex align-items-center">
                                                <span class="monthly_goal">Monthly Goal</span>
                                            </div>
                                            <span class="fw-600 monthly_goal">0%</span>
                                        </div>

                                        <div class="progress progress-sm mt-1">
                                            <div class="progress-bar bg-danger" style="width:0%">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="col-lg-3 col-sm-6">
                            <div class="stats-card-one mb-30">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="mb-10 line-height-1">Account Manager Revenue</p>
                                        <h3 class="fs-25"> {{ $count['account_manager_revenue'] }}</h3>
                                    </div>

                                    <span class="badge badge-red font-size-12">
                                        <i class="icofont-swoosh-down"></i>
                                        <span class="fw-600 m-l-5"></span>
                                    </span>
                                </div>

                                <div class="mt-15">
                                    <div class="d-flex justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <span class="monthly_goal">Monthly Goal</span>
                                        </div>
                                        <span
                                            class="fw-600 monthly_goal">{{ $count['account_manager_percentage'] }}%</span>
                                    </div>

                                    <div class="progress progress-sm mt-1">
                                        <div class="progress-bar bg-purple"
                                            style="width: {{ $count['account_manager_percentage'] }}%">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <div class="stats-card-one mb-30">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="mb-10 line-height-1">BDM Revenue</p>
                                        <h3 class="fs-25">{{ $count['bdm_revenue'] }}</h3>
                                    </div>

                                    <span class="badge badge-red font-size-12">
                                        <i class="icofont-swoosh-down"></i>
                                        <span class="fw-600 m-l-5">{{ $count['bdm_percentage'] }}%</span>
                                    </span>
                                </div>

                                <div class="mt-15">
                                    <div class="d-flex justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <span class="monthly_goal">Monthly Goal</span>
                                        </div>
                                        <span class="fw-600 monthly_goal">{{ $count['bdm_percentage'] }}%</span>
                                    </div>

                                    <div class="progress progress-sm mt-1">
                                        <div class="progress-bar bg-black"
                                            style="width: {{ $count['bdm_percentage'] }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="card mb-30">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <span>No of Prospects</span>
                                        </div>

                                        <span class="fw-600">{{ $count['prospects'] }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="card mb-30">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <span>Completed Prospects</span>
                                        </div>
                                        <span class="fw-600">{{ $count['win'] }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="card mb-30">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <span>No of Customers</span>
                                        </div>
                                        <span class="fw-600">{{ $count['projects'] }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="card mb-30">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <span>No of Project</span>
                                        </div>
                                        <span class="fw-600">{{ $count['projects'] }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-lg-12">
                    <div class="card mb-30">
                        <div class="card-body" style="position: relative;">
                            <div class="">
                                <h5 class="card-title">Statistics</h5>
                            </div>


                            <div class="resize-triggers">
                                <div class="expand-trigger chartjs-custom">
                                    <canvas id="canvas"></canvas>
                                </div>
                                <div class="contract-trigger"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-4">
                    <div class="card mb-30">
                        <div class="card-body" style="position: relative;">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h5 class="card-title mb-0">Top Customer</h5>
                                <a href="" class="view_all">View All</a>
                            </div>
                            <div class="grid-card-wrap">
                                <div class="cursor-pointer">
                                    <div class="grid-card basic-box-shadow">
                                        <div class="text-center">
                                            <img class="avatar rounded-circle avatar-lg"
                                                src="https://6valley.6amtech.com/storage/app/public/profile/2022-04-20-625fa7d513aa5.png">
                                        </div>
                                        <h5 class="mb-0">fatema</h5>
                                        <div class="orders-count d-flex gap-1">
                                            <div>Value : </div>
                                            <div>$ 137</div>
                                        </div>
                                        <div class="orders-count d-flex gap-1">
                                            <div>No Of Projects : </div>
                                            <div>7</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="cursor-pointer">
                                    <div class="grid-card basic-box-shadow">
                                        <div class="text-center">
                                            <img class="avatar rounded-circle avatar-lg"
                                                src="https://6valley.6amtech.com/storage/app/public/profile/2022-04-20-625fa7d513aa5.png">
                                        </div>
                                        <h5 class="mb-0">fatema</h5>
                                        <div class="orders-count d-flex gap-1">
                                            <div>Value : </div>
                                            <div>$ 137</div>
                                        </div>
                                        <div class="orders-count d-flex gap-1">
                                            <div>No Of Projects : </div>
                                            <div>7</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="cursor-pointer">
                                    <div class="grid-card basic-box-shadow">
                                        <div class="text-center">
                                            <img class="avatar rounded-circle avatar-lg"
                                                src="https://6valley.6amtech.com/storage/app/public/profile/2022-04-20-625fa7d513aa5.png">
                                        </div>
                                        <h5 class="mb-0">fatema</h5>
                                        <div class="orders-count d-flex gap-1">
                                            <div>Value : </div>
                                            <div>$ 137</div>
                                        </div>
                                        <div class="orders-count d-flex gap-1">
                                            <div>No Of Projects : </div>
                                            <div>7</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="cursor-pointer">
                                    <div class="grid-card basic-box-shadow">
                                        <div class="text-center">
                                            <img class="avatar rounded-circle avatar-lg"
                                                src="https://6valley.6amtech.com/storage/app/public/profile/2022-04-20-625fa7d513aa5.png">
                                        </div>
                                        <h5 class="mb-0">fatema</h5>
                                        <div class="orders-count d-flex gap-1">
                                            <div>Value : </div>
                                            <div>$ 137</div>
                                        </div>
                                        <div class="orders-count d-flex gap-1">
                                            <div>No Of Projects : </div>
                                            <div>7</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="cursor-pointer">
                                    <div class="grid-card basic-box-shadow">
                                        <div class="text-center">
                                            <img class="avatar rounded-circle avatar-lg"
                                                src="https://6valley.6amtech.com/storage/app/public/profile/2022-04-20-625fa7d513aa5.png">
                                        </div>
                                        <h5 class="mb-0">fatema</h5>
                                        <div class="orders-count d-flex gap-1">
                                            <div>Value : </div>
                                            <div>$ 137</div>
                                        </div>
                                        <div class="orders-count d-flex gap-1">
                                            <div>No Of Projects : </div>
                                            <div>7</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="cursor-pointer">
                                    <div class="grid-card basic-box-shadow">
                                        <div class="text-center">
                                            <img class="avatar rounded-circle avatar-lg"
                                                src="https://6valley.6amtech.com/storage/app/public/profile/2022-04-20-625fa7d513aa5.png">
                                        </div>
                                        <h5 class="mb-0">fatema</h5>
                                        <div class="orders-count d-flex gap-1">
                                            <div>Value : </div>
                                            <div>$ 137</div>
                                        </div>
                                        <div class="orders-count d-flex gap-1">
                                            <div>No Of Projects : </div>
                                            <div>7</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card mb-30">
                        <div class="card-body" style="position: relative;">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h5 class="card-title mb-0">Latest Projects</h5>
                                <a href="{{ route('sales-projects.index') }}" class="view_all">View All</a>
                            </div>
                            <div class="table-responsive dashboard_mini_table">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Project Name</th>
                                            <th>Project Type</th>
                                            <th>Project Value</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (count($projects) == 0)
                                            <tr>
                                                <td colspan="3" class="text-center">No Project Found</td>
                                            </tr>
                                        @else
                                            @foreach ($projects as $key => $project)
                                                <tr>
                                                    <td>
                                                        {{ $project->business_name }}
                                                    </td>
                                                    <td>
                                                        {{ $project->projectTypes->name ?? '' }}
                                                    </td>
                                                    <td>
                                                        <div class="project_value">
                                                            <h5 class="shop-sell">$ {{ $project->project_value }}</h5>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card mb-30">
                        <div class="card-body" style="position: relative;">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h5 class="card-title mb-0">Top Performers</h5>
                                <a href="" class="view_all">View All</a>
                            </div>

                            <div class="table-responsive dashboard_mini_table">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Project Name</th>
                                            <th>Project Type</th>
                                            <th>Project Value</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Deluxe Online</td>
                                            <td>Deluxe Online</td>
                                            <td>
                                                <div class="project_value">
                                                    <h5 class="shop-sell">$ 4,944.80</h5>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Deluxe Online</td>
                                            <td>Deluxe Online</td>
                                            <td>
                                                <div class="project_value">
                                                    <h5 class="shop-sell">$ 4,944.80</h5>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Deluxe Online</td>
                                            <td>Deluxe Online</td>
                                            <td>
                                                <div class="project_value">
                                                    <h5 class="shop-sell">$ 4,944.80</h5>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Deluxe Online</td>
                                            <td>Deluxe Online</td>
                                            <td>
                                                <div class="project_value">
                                                    <h5 class="shop-sell">$ 4,944.80</h5>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Deluxe Online</td>
                                            <td>Deluxe Online</td>
                                            <td>
                                                <div class="project_value">
                                                    <h5 class="shop-sell">$ 4,944.80</h5>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Deluxe Online</td>
                                            <td>Deluxe Online</td>
                                            <td>
                                                <div class="project_value">
                                                    <h5 class="shop-sell">$ 4,944.80</h5>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Deluxe Online</td>
                                            <td>Deluxe Online</td>
                                            <td>
                                                <div class="project_value">
                                                    <h5 class="shop-sell">$ 4,944.80</h5>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row">

                <div class="col-lg-6">
                    <div class="card mb-30">
                        <div class="card-body" style="position: relative;">
                            <div class="">
                                <h5 class="card-title">Prospects Statistics</h5>
                            </div>

                            <div class="resize-triggers py-5">
                                <div class="expand-trigger">
                                    <canvas id="oilChart"></canvas>

                                </div>
                                <div class="contract-trigger"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card mb-30">
                        <div class="card-body" style="position: relative;">
                            <div class="">
                                <h5 class="card-title">Last 10 Prospects</h5>
                            </div>
                            <div class="row justify-content-end">
                                <div class="col-md-6">
                                    <div class="row g-1 justify-content-end">
                                        <div class="col-md-8 pr-0">
                                            <div class="search-field prod-search">
                                                <input type="text" name="search" id="search"
                                                    placeholder="search..." required class="form-control rounded_search">
                                                <a href="javascript:void(0)" class="prod-search-icon submit_search"><i
                                                        class="fa fa-search"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table id="myTable" class="dd table table-striped table-bordered table-hover"
                                    style="width:100%">
                                    <thead>
                                        <tr>
                                            <th class="sorting" data-tippy-content="Sort by Sale Date"
                                                data-sorting_type="desc" data-column_name="sale_date"
                                                style="cursor: pointer">Date <span id="date_icon"></span></th>
                                            <th class="sorting" data-tippy-content="Sort by Business Name"
                                                data-sorting_type="asc" data-column_name="business_name"
                                                style="cursor: pointer">Business Name <span
                                                    id="business_name_icon"></span></th>
                                            <th class="sorting" data-tippy-content="Sort by Client Name"
                                                data-sorting_type="asc" data-column_name="client_name"
                                                style="cursor: pointer">Client Name <span id="client_name_icon"></span>
                                            </th>
                                            <th class="sorting" data-tippy-content="Sort by Phone"
                                                data-sorting_type="asc" data-column_name="phone" style="cursor: pointer">
                                                Email <span id="email_icon"></span></th>
                                            <th class="sorting" data-tippy-content="Sort by Phone"
                                                data-sorting_type="asc" data-column_name="phone" style="cursor: pointer">
                                                Phone <span id="phone_icon"></span></th>
                                            <th data-tippy-content="Cant't sort by Transfer taken by"
                                                style="cursor: pointer">Transfer Taken By</th>
                                            <th>Status</th>
                                            <th data-tippy-content="Cant't sort by Service offer" style="cursor: pointer">
                                                Service Offered</th>
                                            <th class="sorting" data-tippy-content="Sort by Follow"
                                                data-sorting_type="asc" data-column_name="follow"
                                                style="cursor: pointer">Followup Date <span id="follow_icon"></span></th>
                                            <th class="sorting" data-tippy-content="Sort by Price quoted"
                                                data-sorting_type="asc" data-column_name="price" style="cursor: pointer">
                                                Price Quoted <span id="price_quoted_icon"></span></th>
                                        </tr>
                                    </thead>
                                    <tbody class="prospect-filter">
                                        @include('admin.dashboard_prospect_table')
                                        {{-- @foreach ($prospects as $key => $prospect)
                                <tr>
                                    <td>
                                        {{ date('d M, Y', strtotime($prospect->created_at)) }}
                                    </td>
                                    <td>
                                        {{ $prospect->business_name }}
                                    </td>
                                    <td>
                                        {{ $prospect->client_name }}
                                    </td>
                                    <td>
                                        {{ $prospect->client_email }}
                                    </td>
                                    <td>
                                        {{ $prospect->client_phone }}
                                    </td>
                                    <td>
                                        {{ $prospect->transferTakenBy->name ?? '' }}
                                    </td>
                                    <td>
                                        @if ($prospect->status == 'Win')
                                            <span>On Board</span>
                                        @elseif ($prospect->status == 'Follow Up')
                                            <span>Follow Up</span>
                                        @elseif ($prospect->status == 'Sent Proposal')
                                            <span>Sent Proposal</span>
                                        @elseif ($prospect->status == 'Close')
                                            <span>Cancel</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $prospect->offered_for }}
                                    </td>



                                    <td>
                                        {{ date('d M, Y', strtotime($prospect->followup_date)) }}
                                    </td>
                                    <td>
                                        {{ $prospect->price_quote }}
                                    </td>


                                </tr>
                            @endforeach --}}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.min.js"></script>
    <script>
        var barChartData = {
            labels: [
                "Jan",
                "Febr",
                "Mar",
                "Apr",
                "May",
                "Jun",
                "Jul",
                'Aug',
                'Sept',
                'Oct',
                'Nov',
                'Dec'
            ],
            datasets: [{
                    label: "Gross Sales",
                    backgroundColor: "#fa8d35",
                    borderColor: "#fa8d35",
                    borderWidth: 1,
                    data: [{{ $goal['gross_goals_january'] ?? 0 }}, {{ $goal['gross_goals_february'] ?? 0 }},
                        {{ $goal['gross_goals_march'] ?? 0 }}, {{ $goal['gross_goals_april'] ?? 0 }},
                        {{ $goal['gross_goals_may'] ?? 0 }}, {{ $goal['gross_goals_june'] ?? 0 }},
                        {{ $goal['gross_goals_july'] ?? 0 }}, {{ $goal['gross_goals_august'] ?? 0 }},
                        {{ $goal['gross_goals_september'] ?? 0 }}, {{ $goal['gross_goals_october'] ?? 0 }},
                        {{ $goal['gross_goals_november'] ?? 0 }}, {{ $goal['gross_goals_december'] ?? 0 }}
                    ]
                },
                {
                    label: "Revenue",
                    backgroundColor: "#ad1e23",
                    borderColor: "#ad1e23",
                    borderWidth: 1,
                    data: [{{ $goal['net_goals_january'] ?? 0 }}, {{ $goal['net_goals_february'] ?? 0 }},
                        {{ $goal['net_goals_march'] ?? 0 }}, {{ $goal['net_goals_april'] ?? 0 }},
                        {{ $goal['net_goals_may'] ?? 0 }}, {{ $goal['net_goals_june'] ?? 0 }},
                        {{ $goal['net_goals_july'] ?? 0 }}, {{ $goal['net_goals_august'] ?? 0 }},
                        {{ $goal['net_goals_september'] ?? 0 }}, {{ $goal['net_goals_october'] ?? 0 }},
                        {{ $goal['net_goals_november'] ?? 0 }}, {{ $goal['net_goals_december'] ?? 0 }}
                    ]
                },
                {
                    label: "Prospect",
                    backgroundColor: "#6c757d",
                    borderColor: "#6c757d",
                    borderWidth: 1,
                    data: [{{ $goal['prospect_december'] ?? 0 }}, {{ $goal['prospect_january'] ?? 0 }},
                        {{ $goal['prospect_february'] ?? 0 }}, {{ $goal['prospect_march'] ?? 0 }},
                        {{ $goal['prospect_april'] ?? 0 }}, {{ $goal['prospect_may'] ?? 0 }},
                        {{ $goal['prospect_june'] ?? 0 }}, {{ $goal['prospect_july'] ?? 0 }},
                        {{ $goal['prospect_august'] ?? 0 }}, {{ $goal['prospect_september'] ?? 0 }},
                        {{ $goal['prospect_october'] ?? 0 }}, {{ $goal['prospect_november'] ?? 0 }}
                    ]
                },
            ]
        };

        // var chartOptions = {
        //     responsive: true,
        //     legend: {
        //         position: "top"
        //     },
        //     title: {
        //         display: true,
        //         text: ""
        //     },
        //     scales: {
        //         yAxes: [{
        //             ticks: {
        //                 beginAtZero: true
        //             }
        //         }]
        //     },

        //     // change text style
        //     legend: {
        //         labels: {
        //             // This more specific font property overrides the global property
        //             fontColor: "black",
        //             fontSize: 12,
        //             fontFamily: "Montserrat",
        //             fontStyle: "normal",
        //             padding: 25,
        //         }
        //     },

        //     // change title font style
        //     title: {
        //         fontColor: "black",
        //         fontSize: 18,
        //         fontFamily: "Montserrat",
        //         fontStyle: "normal",
        //         padding: 25,
        //     },

        //     // change scale font style
        //     scales: {
        //         xAxes: [{
        //             ticks: {
        //                 fontColor: "black",
        //                 fontSize: 12,
        //                 fontFamily: "Montserrat",
        //                 fontStyle: "normal",
        //                 padding: 25,
        //             }
        //         }],
        //         yAxes: [{
        //             ticks: {
        //                 fontColor: "black",
        //                 fontSize: 12,
        //                 fontFamily: "Montserrat",
        //                 fontStyle: "normal",
        //                 padding: 25,
        //             }
        //         }]
        //     },

        //     // decrease bar width
        //     scales: {
        //         xAxes: [{
        //             barPercentage: 0.5
        //         }]
        //     },

        //     // dcrease space between bar
        //     scales: {
        //         xAxes: [{
        //             categoryPercentage: 0.4
        //         }]
        //     },


        // }
        var chartOptions = {
            responsive: true,
            bezierCurve: false,
            maintainAspectRatio: false,
            scales: {
                xAxes: [{
                    gridLines: {
                        color: "rgba(180, 208, 224, 0.5)",
                        zeroLineColor: "rgba(180, 208, 224, 0.5)",
                    }
                }],
                yAxes: [{
                    gridLines: {
                        color: "rgba(180, 208, 224, 0.5)",
                        zeroLineColor: "rgba(180, 208, 224, 0.5)",
                        borderDash: [8, 4],
                    }
                }]
            },
            legend: {
                display: true,
                position: "top",
                labels: {
                    usePointStyle: true,
                    boxWidth: 6,
                    fontColor: "#758590",
                    fontSize: 14
                }
            },
            plugins: {
                datalabels: {
                    display: false
                }
            },
        };
        window.onload = function() {
            var ctx = document.getElementById("canvas").getContext("2d");
            window.myBar = new Chart(ctx, {
                type: "bar",
                data: barChartData,
                options: chartOptions
            });


        };
    </script>
    {{-- <script>
        $(document).ready(function() {
            //Default data table
            $('#myTable').DataTable({
                "aaSorting": [],
                "columnDefs": [{
                        "orderable": false,
                        "targets": []
                    },
                    {
                        "orderable": true,
                        "targets": [0, 1, 2, 5, 6, 7, 8, 9]
                    }
                ]
            });

        });
    </script> --}}
    <script>
        $(document).ready(function() {
            //how to place holder in "jquery datatable" search box
            $('#myTable_filter input').attr("placeholder", "Search");
        });
    </script>
    <script>
        var oilCanvas = document.getElementById("oilChart");

        Chart.defaults.global.defaultFontFamily = "Lato";
        Chart.defaults.global.defaultFontSize = 18;

        var oilData = {
            labels: [
                "On Board",
                "Follow Up",
                "Sent Proposal",
                "Close",
            ],
            datasets: [{
                data: [{{ $count['win'] }}, {{ $count['follow_up'] }}, {{ $count['sent_proposal'] }},
                    {{ $count['close'] }}
                ],
                backgroundColor: [
                    "#ad1e23",
                    "#fa8d35",
                    "#297dd7",
                    "#6c757d",
                ]
            }]
        };

        var pieChart = new Chart(oilCanvas, {
            type: 'pie',
            data: oilData
        });
    </script>

    <script>
        $(document).ready(function() {
            function clear_icon() {
                $('#date_icon').html('');
                $('#business_name_icon').html('');
                $('#client_name_icon').html('');
                $('#email_icon').html('');
                $('#phone_icon').html('');
                $('#follow_icon').html('');
                $('#price_quoted_icon').html('');
                // $('#currency_icon').html('');
            }

            function fetch_data(page, sort_type, sort_by, query) {
                $.ajax({
                    url: "{{ route('admin.dashboard.prospect-fetch-data') }}",
                    data: {
                        page: page,
                        sortby: sort_by,
                        sorttype: sort_type,
                        query: query
                    },
                    success: function(data) {
                        $('.prospect-filter').html(data.data);
                    }
                });
            }

            $(document).on('keyup', '#search', function() {
                var query = $('#search').val();
                var column_name = $('#hidden_column_name').val();
                var sort_type = $('#hidden_sort_type').val();
                var page = $('#hidden_page').val();
                fetch_data(page, sort_type, column_name, query);
            });

            $(document).on('click', '.sorting', function() {
                var column_name = $(this).data('column_name');
                var order_type = $(this).data('sorting_type');
                var reverse_order = '';
                if (order_type == 'asc') {
                    $(this).data('sorting_type', 'desc');
                    reverse_order = 'desc';
                    clear_icon();
                    $('#' + column_name + '_icon').html(
                        '<span class="fa fa-sort-down"></span>');
                }
                if (order_type == 'desc') {
                    $(this).data('sorting_type', 'asc');
                    reverse_order = 'asc';
                    clear_icon();
                    $('#' + column_name + '_icon').html(
                        '<span class="fa fa-sort-up"></span>');
                }
                $('#hidden_column_name').val(column_name);
                $('#hidden_sort_type').val(reverse_order);
                var page = $('#hidden_page').val();
                var query = $('#search').val();
                fetch_data(page, reverse_order, column_name, query);
            });

            $(document).on('click', '.pagination a', function(event) {
                event.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                $('#hidden_page').val(page);
                var column_name = $('#hidden_column_name').val();
                var sort_type = $('#hidden_sort_type').val();

                var query = $('#search').val();

                $('li').removeClass('active');
                $(this).parent().addClass('active');
                fetch_data(page, sort_type, column_name, query);
            });

        });
    </script>
@endpush
