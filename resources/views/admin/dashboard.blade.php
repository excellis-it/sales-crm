@extends('admin.layouts.master')
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
                        <h3 class="page-title">Welcome Admin!</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                    <a href="{{ route('sales_managers.index') }}" style="color: black">
                        <div class="card dash-widget">
                            <div class="card-body">
                                <div class="dash-widget-info">
                                    <h3>{{ $count['sales_managers'] }}</h3>
                                    <span>Total Sales managers</span>
                                </div>
                                <div class="">
                                    <span class="dash-widget-icon"><i class="fa fa-users"></i></span>
                                    <div class="">+6.32%</div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                    <a href="{{ route('account_managers.index') }}" style="color: black">
                        <div class="card dash-widget">
                            <div class="card-body">
                                <span class="dash-widget-icon"><i class="fas fa-user-tie"></i></span>
                                <div class="dash-widget-info">
                                    <h3>{{ $count['account_managers'] }}</h3>
                                    <span>Total Account managers</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                    <a href="{{ route('sales-excecutive.index') }}" style="color: black">
                        <div class="card dash-widget">
                            <div class="card-body">
                                <span class="dash-widget-icon"><i class="fas fa-user-friends"></i></span>
                                <div class="dash-widget-info">
                                    <h3>{{ $count['sales_excecutive'] }}</h3>
                                    <span>Total Sales Excecutive</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                    <a href="{{ route('sales-projects.index') }}" style="color: black">
                        <div class="card dash-widget">
                            <div class="card-body">
                                <span class="dash-widget-icon"><i class="fas fa-book-open"></i></span>
                                <div class="dash-widget-info">
                                    <h3>{{ $count['projects'] }}</h3>
                                    <span>Total Projects</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 col-lg-12 col-xl-4 d-flex">
                    <div class="card flex-fill dash-statistics">
                        <div class="card-body">
                            <h5 class="card-title">Prospects</h5>
                            <div class="stats-list">
                                <div class="stats-info">
                                    <p>On Board <strong>{{$count['win']}} <small>/ {{$count['prospects']}}</small></strong></p>
                                    <div class="progress">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: 31%"
                                            aria-valuenow="31" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <div class="stats-info">
                                    <p>Follow Up <strong>{{$count['follow_up']}} <small>/ {{$count['prospects']}}</small></strong></p>
                                    <div class="progress">
                                        <div class="progress-bar bg-warning" role="progressbar" style="width: 31%"
                                            aria-valuenow="31" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <div class="stats-info">
                                    <p>Sent Proposal <strong>{{$count['sent_proposal']}} <small>/ {{$count['prospects']}}</small></strong></p>
                                    <div class="progress">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: 62%"
                                            aria-valuenow="62" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <div class="stats-info">
                                    <p>Cancel <strong>{{$count['close']}} <small>/ {{$count['prospects']}}</small></strong></p>
                                    <div class="progress">
                                        <div class="progress-bar bg-danger" role="progressbar" style="width: 62%"
                                            aria-valuenow="62" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="col-md-12 col-lg-6 col-xl-4 d-flex">
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
