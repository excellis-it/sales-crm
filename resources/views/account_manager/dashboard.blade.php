@extends('account_manager.layouts.master')
@section('title')
    Dashboard - {{ env('APP_NAME') }} admin
@endsection
@push('styles')
@endpush

@section('content')
    <div class="page-wrapper">

        <div class="content container-fluid">

            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">Welcome Account Manager Panel!</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Total Projects Card -->
                <div class="col-md-6 col-sm-6 col-lg-6 col-xl-4">
                    <a href="{{ route('account-manager.projects.index') }}" class="dash-card-link">
                        <div class="stats-card-one dash-card card-info mb-30" style="min-height: 180px;">
                            <div class="d-flex justify-content-between align-items-center mb-10">
                                <div class="d-flex align-items-center">
                                    <div class="dash-icon-box bg-pale-blue mr-2"
                                        style="width: 35px; height: 35px; font-size: 16px;">
                                        <i class="la la-briefcase"></i>
                                    </div>
                                    <p class="dash-title mb-0">Total Projects</p>
                                </div>
                                <span class="badge badge-cyan fs-12">
                                    <span class="fw-600">{{ $count['projects'] }}</span>
                                </span>
                            </div>
                            <h3 class="fs-22 mb-10">{{ $count['projects'] }} Project(s)</h3>

                            <div class="mt-15">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="dash-title">Assigned To You</span>
                                </div>
                                <div class="progress progress-sm" style="height: 6px;">
                                    <div class="progress-bar bg-info" style="width: 100%"></div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Monthly Revenue Goal (Net) -->
                @php
                    $target = isset($count['net_target']['goals_amount']) ? $count['net_target']['goals_amount'] : 0;
                    $achieve = isset($count['net_target']['goals_achieve']) ? $count['net_target']['goals_achieve'] : 0;
                    $percentage = $target > 0 ? round(($achieve / $target) * 100, 0) : 0;
                @endphp
                <div class="col-md-6 col-sm-6 col-lg-6 col-xl-4">
                    <a href="javascript:void(0);" class="dash-card-link">
                        <div class="stats-card-one dash-card card-danger mb-30" style="min-height: 180px;">
                            <div class="d-flex justify-content-between align-items-center mb-10">
                                <div class="d-flex align-items-center">
                                    <div class="dash-icon-box bg-pale-red mr-2"
                                        style="width: 35px; height: 35px; font-size: 16px;">
                                        <i class="la la-money-bill"></i>
                                    </div>
                                    <p class="dash-title mb-0">Monthly Revenue (Net)</p>
                                </div>
                                <span class="badge badge-red fs-12">
                                    <span class="fw-600">{{ $percentage }}%</span>
                                </span>
                            </div>
                            @if ($target > 0)
                                <h3 class="fs-22 mb-10">${{ $achieve }} / ${{ $target }}</h3>
                                <div class="mt-15">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="dash-title">Monthly Goal Achievement</span>
                                    </div>
                                    <div class="progress progress-sm" style="height: 6px;">
                                        <div class="progress-bar bg-danger" style="width: {{ min($percentage, 100) }}%"></div>
                                    </div>
                                </div>
                            @else
                                <h3 class="fs-22 mb-10">No Goal Set</h3>
                                <div class="mt-15">
                                    <div class="progress progress-sm" style="height: 6px;">
                                        <div class="progress-bar bg-danger" style="width: 0%"></div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </a>
                </div>

                <!-- Empty space or another card if needed. The 3-card layout before is now condensed into beautiful 2 or 3 premium cards -->
                
            </div>

            <div class="row">
                {{-- <div class="col-md-12 col-lg-12 col-xl-4 d-flex">
                    <div class="card flex-fill dash-statistics">
                        <div class="card-body">
                            <h5 class="card-title">Statistics</h5>
                            <div class="stats-list">
                                <div class="stats-info">
                                    <p>Today Leave <strong>4 <small>/ 65</small></strong></p>
                                    <div class="progress">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: 31%"
                                            aria-valuenow="31" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <div class="stats-info">
                                    <p>Pending Invoice <strong>15 <small>/ 92</small></strong></p>
                                    <div class="progress">
                                        <div class="progress-bar bg-warning" role="progressbar" style="width: 31%"
                                            aria-valuenow="31" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <div class="stats-info">
                                    <p>Completed Projects <strong>85 <small>/ 112</small></strong></p>
                                    <div class="progress">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: 62%"
                                            aria-valuenow="62" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <div class="stats-info">
                                    <p>Open Tickets <strong>190 <small>/ 212</small></strong></p>
                                    <div class="progress">
                                        <div class="progress-bar bg-danger" role="progressbar" style="width: 62%"
                                            aria-valuenow="62" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <div class="stats-info">
                                    <p>Closed Tickets <strong>22 <small>/ 212</small></strong></p>
                                    <div class="progress">
                                        <div class="progress-bar bg-info" role="progressbar" style="width: 22%"
                                            aria-valuenow="22" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-lg-6 col-xl-4 d-flex">
                    <div class="card flex-fill">
                        <div class="card-body">
                            <h4 class="card-title">Task Statistics</h4>
                            <div class="statistics">
                                <div class="row">
                                    <div class="col-md-6 col-6 text-center">
                                        <div class="stats-box mb-4">
                                            <p>Total Tasks</p>
                                            <h3>385</h3>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-6 text-center">
                                        <div class="stats-box mb-4">
                                            <p>Overdue Tasks</p>
                                            <h3>19</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="progress mb-4">
                                <div class="progress-bar bg-purple" role="progressbar" style="width: 30%" aria-valuenow="30"
                                    aria-valuemin="0" aria-valuemax="100">30%</div>
                                <div class="progress-bar bg-warning" role="progressbar" style="width: 22%"
                                    aria-valuenow="18" aria-valuemin="0" aria-valuemax="100">22%</div>
                                <div class="progress-bar bg-success" role="progressbar" style="width: 24%"
                                    aria-valuenow="12" aria-valuemin="0" aria-valuemax="100">24%</div>
                                <div class="progress-bar bg-danger" role="progressbar" style="width: 26%"
                                    aria-valuenow="14" aria-valuemin="0" aria-valuemax="100">21%</div>
                                <div class="progress-bar bg-info" role="progressbar" style="width: 10%"
                                    aria-valuenow="14" aria-valuemin="0" aria-valuemax="100">10%</div>
                            </div>
                            <div>
                                <p><i class="fa fa-dot-circle-o text-purple me-2"></i>Completed Tasks <span
                                        class="float-end">166</span></p>
                                <p><i class="fa fa-dot-circle-o text-warning me-2"></i>Inprogress Tasks <span
                                        class="float-end">115</span></p>
                                <p><i class="fa fa-dot-circle-o text-success me-2"></i>On Hold Tasks <span
                                        class="float-end">31</span></p>
                                <p><i class="fa fa-dot-circle-o text-danger me-2"></i>Pending Tasks <span
                                        class="float-end">47</span></p>
                                <p class="mb-0"><i class="fa fa-dot-circle-o text-info me-2"></i>Review Tasks <span
                                        class="float-end">5</span></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-lg-6 col-xl-4 d-flex">
                    <div class="card flex-fill">
                        <div class="card-body">
                            <h4 class="card-title">Today Absent <span class="badge bg-inverse-danger ms-2">5</span></h4>
                            <div class="leave-info-box">
                                <div class="media d-flex align-items-center">
                                    <a href="profile.html" class="avatar"><img alt=""
                                            src="assets/img/user.jpg"></a>
                                    <div class="media-body flex-grow-1">
                                        <div class="text-sm my-0">Martin Lewis</div>
                                    </div>
                                </div>
                                <div class="row align-items-center mt-3">
                                    <div class="col-6">
                                        <h6 class="mb-0">4 Sep 2019</h6>
                                        <span class="text-sm text-muted">Leave Date</span>
                                    </div>
                                    <div class="col-6 text-end">
                                        <span class="badge bg-inverse-danger">Pending</span>
                                    </div>
                                </div>
                            </div>
                            <div class="leave-info-box">
                                <div class="media d-flex align-items-center">
                                    <a href="profile.html" class="avatar"><img alt=""
                                            src="assets/img/user.jpg"></a>
                                    <div class="media-body flex-grow-1">
                                        <div class="text-sm my-0">Martin Lewis</div>
                                    </div>
                                </div>
                                <div class="row align-items-center mt-3">
                                    <div class="col-6">
                                        <h6 class="mb-0">4 Sep 2019</h6>
                                        <span class="text-sm text-muted">Leave Date</span>
                                    </div>
                                    <div class="col-6 text-end">
                                        <span class="badge bg-inverse-success">Approved</span>
                                    </div>
                                </div>
                            </div>
                            <div class="load-more text-center">
                                <a class="text-dark" href="javascript:void(0);">Load More</a>
                            </div>
                        </div>
                    </div>
                </div> --}}
            </div>

        </div>

    </div>

    </div>
@endsection

@push('scripts')
@endpush
