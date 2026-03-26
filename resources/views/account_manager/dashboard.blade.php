@extends('account_manager.layouts.master')
@section('title')
    Dashboard - {{ env('APP_NAME') }}
@endsection
@push('styles')
    <style>
        .dash-card-link {
            text-decoration: none;
            color: inherit;
            display: block;
        }
        .dash-card-link:hover {
            text-decoration: none;
            color: inherit;
        }
        .dash-card {
            border: none;
            border-radius: 0.75rem;
            padding: 1.25rem;
            transition: all 0.3s ease;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
            position: relative;
            overflow: hidden;
        }
        .dash-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }
        .dash-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
        }
        .card-info::before { background: #17a2b8; }
        .card-success::before { background: #28a745; }
        .card-danger::before { background: #dc3545; }
        .card-warning::before { background: #ffc107; }
        .card-purple::before { background: #6f42c1; }
        .card-primary::before { background: #007bff; }

        .dash-icon-box {
            width: 45px;
            height: 45px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }
        .bg-pale-blue { background: rgba(23, 162, 184, 0.12); color: #17a2b8; }
        .bg-pale-green { background: rgba(40, 167, 69, 0.12); color: #28a745; }
        .bg-pale-red { background: rgba(220, 53, 69, 0.12); color: #dc3545; }
        .bg-pale-orange { background: rgba(255, 193, 7, 0.12); color: #ffc107; }
        .bg-pale-purple { background: rgba(111, 66, 193, 0.12); color: #6f42c1; }
        .bg-pale-primary { background: rgba(0, 123, 255, 0.12); color: #007bff; }

        .dash-title { font-size: 12px; color: #6c757d; font-weight: 500; }
        .dash-value { font-size: 24px; font-weight: 700; color: #2c3e50; margin: 6px 0; }
        .dash-sub { font-size: 13px; color: #888; }

        .table-card {
            border: none;
            border-radius: 0.75rem;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }
        .table-card .card-header {
            background: #fff;
            border-bottom: 1px solid #f0f0f0;
            padding: 1rem 1.25rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .table-card .card-header h5 {
            margin: 0;
            font-weight: 600;
            font-size: 15px;
            color: #333;
        }
        .table-card .table {
            margin-bottom: 0;
        }
        .table-card .table th {
            background: #f8f9fa;
            border-top: none;
            font-size: 12px;
            font-weight: 600;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .table-card .table td {
            vertical-align: middle;
            font-size: 13px;
            padding: 0.75rem;
        }
        .table-card .table tbody tr {
            transition: background 0.2s;
            cursor: pointer;
        }
        .table-card .table tbody tr:hover {
            background: #f0f7ff;
        }
        .view-all-btn {
            font-size: 12px;
            font-weight: 600;
            color: #007bff;
            text-decoration: none;
        }
        .view-all-btn:hover {
            color: #0056b3;
            text-decoration: underline;
        }
        .badge-paid {
            background: #d4edda;
            color: #155724;
            font-size: 11px;
            padding: 3px 8px;
            border-radius: 4px;
        }
        .badge-pending-custom {
            background: #fff3cd;
            color: #856404;
            font-size: 11px;
            padding: 3px 8px;
            border-radius: 4px;
        }
        .badge-upsale {
            background: #6f42c1;
            color: #fff;
            font-size: 10px;
            padding: 2px 6px;
            border-radius: 4px;
        }
    </style>
@endpush

@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">

            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">Welcome, {{ Auth::user()->name }}!</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Stats Cards Row 1 --}}
            <div class="row">
                {{-- Total Projects --}}
                <div class="col-md-6 col-lg-4 col-xl-4 mb-3">
                    <a href="{{ route('account-manager.projects.index') }}" class="dash-card-link">
                        <div class="dash-card card-info">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="dash-title mb-0">Total Projects</p>
                                    <div class="dash-value">{{ $count['projects'] }}</div>
                                    <span class="dash-sub">Assigned to you</span>
                                </div>
                                <div class="dash-icon-box bg-pale-blue">
                                    <i class="la la-briefcase"></i>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                {{-- Monthly Revenue Goal (Net) --}}
                @php
                    $target = isset($count['net_target']['goals_amount']) ? $count['net_target']['goals_amount'] : 0;
                    $achieve = isset($count['net_target']['goals_achieve']) ? $count['net_target']['goals_achieve'] : 0;
                    $goalPercentage = $target > 0 ? round(($achieve / $target) * 100, 0) : 0;
                @endphp
                <div class="col-md-6 col-lg-4 col-xl-4 mb-3">
                    <a href="{{ route('account-manager.projects.index', ['start_date' => date('Y-m-01'), 'end_date' => date('Y-m-t')]) }}" class="dash-card-link">
                        <div class="dash-card card-danger">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="dash-title mb-0">Monthly Revenue Goal (Net)</p>
                                    @if ($target > 0)
                                        <div class="dash-value">${{ number_format($achieve, 2) }}</div>
                                        <span class="dash-sub">Target: ${{ number_format($target, 2) }}</span>
                                    @else
                                        <div class="dash-value">N/A</div>
                                        <span class="dash-sub">No goal set this month</span>
                                    @endif
                                </div>
                                <div class="dash-icon-box bg-pale-red">
                                    <i class="la la-bullseye"></i>
                                </div>
                            </div>
                            @if ($target > 0)
                                <div class="mt-3">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="dash-title">Achievement</span>
                                        <span class="dash-title fw-bold">{{ $goalPercentage }}%</span>
                                    </div>
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar bg-danger" style="width: {{ min($goalPercentage, 100) }}%"></div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </a>
                </div>

                {{-- Total Payments Received --}}
                <div class="col-md-6 col-lg-4 col-xl-4 mb-3">
                    <a href="{{ route('account-manager.payments.list') }}" class="dash-card-link">
                        <div class="dash-card card-success">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="dash-title mb-0">Total Payments Received</p>
                                    <div class="dash-value">{{ $count['total_payments'] }}</div>
                                    <span class="dash-sub">Worth ${{ number_format($count['total_payment_amount'], 2) }}</span>
                                </div>
                                <div class="dash-icon-box bg-pale-green">
                                    <i class="la la-money-bill"></i>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            {{-- Stats Cards Row 2 --}}
            <div class="row">
                {{-- This Month Collections --}}
                <div class="col-md-6 col-lg-4 col-xl-4 mb-3">
                    <a href="{{ route('account-manager.payments.list') }}" class="dash-card-link">
                        <div class="dash-card card-primary">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="dash-title mb-0">This Month Collections</p>
                                    <div class="dash-value">${{ number_format($count['monthly_payments'], 2) }}</div>
                                    <span class="dash-sub">{{ date('F Y') }}</span>
                                </div>
                                <div class="dash-icon-box bg-pale-primary">
                                    <i class="la la-calendar-check"></i>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                {{-- Pending Milestones --}}
                <div class="col-md-6 col-lg-4 col-xl-4 mb-3">
                    <a href="{{ route('account-manager.payments.list') }}" class="dash-card-link">
                        <div class="dash-card card-warning">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="dash-title mb-0">Pending Milestones</p>
                                    <div class="dash-value">{{ $count['pending_milestones'] }}</div>
                                    <span class="dash-sub">Worth ${{ number_format($count['pending_amount'], 2) }}</span>
                                </div>
                                <div class="dash-icon-box bg-pale-orange">
                                    <i class="la la-clock"></i>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                {{-- Upsales --}}
                <div class="col-md-6 col-lg-4 col-xl-4 mb-3">
                    <a href="{{ route('account-manager.projects.index') }}" class="dash-card-link">
                        <div class="dash-card card-purple">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="dash-title mb-0">Total Upsales</p>
                                    <div class="dash-value">{{ $count['upsales'] }}</div>
                                    <span class="dash-sub">Worth ${{ number_format($count['upsale_value'], 2) }}</span>
                                </div>
                                <div class="dash-icon-box bg-pale-purple">
                                    <i class="la la-chart-line"></i>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            {{-- Tables Section --}}
            <div class="row">
                {{-- Recent Projects --}}
                <div class="col-lg-7 mb-4">
                    <div class="card table-card">
                        <div class="card-header">
                            <h5><i class="la la-briefcase mr-1"></i> Recent Projects</h5>
                            <a href="{{ route('account-manager.projects.index') }}" class="view-all-btn">View All <i class="la la-arrow-right"></i></a>
                        </div>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Sale Date</th>
                                        <th>Business Name</th>
                                        <th>Project Type</th>
                                        <th>Value</th>
                                        <th>Due</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($recentProjects as $project)
                                        @php
                                            $upsaleTotal = $project->upsales->sum('upsale_value');
                                            $upsaleUpfront = $project->upsales->sum('upsale_upfront');
                                            $grandTotal = $project->project_value + $upsaleTotal;
                                            $totalUpfront = $project->project_upfront + $upsaleUpfront;
                                            $paidMs = $project->allProjectMilestones
                                                ->where('payment_status', 'Paid')
                                                ->whereIn('milestone_type', ['milestone', 'upsale_milestone'])
                                                ->sum('milestone_value');
                                            $dueAmount = $grandTotal - ($totalUpfront + $paidMs);
                                        @endphp
                                        <tr onclick="window.location='{{ route('account-manager.projects.edit', $project->id) }}'">
                                            <td>{{ $project->sale_date ? date('d M Y', strtotime($project->sale_date)) : '-' }}</td>
                                            <td>
                                                <strong>{{ Str::limit($project->business_name, 25) }}</strong>
                                                @if($project->upsales->count() > 0)
                                                    <span class="badge-upsale ml-1">+{{ $project->upsales->count() }} Upsale</span>
                                                @endif
                                            </td>
                                            <td>
                                                @foreach ($project->projectTypes->take(2) as $pt)
                                                    {{ Str::limit($pt->type, 15) }}{{ !$loop->last ? ',' : '' }}
                                                @endforeach
                                                @if($project->projectTypes->count() > 2)
                                                    <span class="text-muted">+{{ $project->projectTypes->count() - 2 }}</span>
                                                @endif
                                            </td>
                                            <td>${{ number_format($grandTotal, 2) }}</td>
                                            <td>
                                                <span class="{{ $dueAmount > 0 ? 'text-danger fw-bold' : 'text-success fw-bold' }}">
                                                    ${{ number_format($dueAmount, 2) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4">No projects found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Recent Payments & Pending Milestones --}}
                <div class="col-lg-5 mb-4">
                    {{-- Recent Payments --}}
                    <div class="card table-card mb-4">
                        <div class="card-header">
                            <h5><i class="la la-money-bill mr-1"></i> Recent Payments</h5>
                            <a href="{{ route('account-manager.payments.list') }}" class="view-all-btn">View All <i class="la la-arrow-right"></i></a>
                        </div>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Project</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($recentPayments as $payment)
                                        <tr onclick="window.location='{{ route('account-manager.projects.edit', $payment->project_id) }}'">
                                            <td>{{ $payment->payment_date ? date('d M Y', strtotime($payment->payment_date)) : '-' }}</td>
                                            <td>{{ Str::limit($payment->project->business_name ?? 'N/A', 20) }}</td>
                                            <td><span class="badge-paid">${{ number_format($payment->milestone_value, 2) }}</span></td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted py-3">No payments yet</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Upcoming Pending Milestones --}}
                    <div class="card table-card">
                        <div class="card-header">
                            <h5><i class="la la-clock mr-1"></i> Pending Milestones</h5>
                        </div>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Milestone</th>
                                        <th>Project</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($upcomingMilestones as $milestone)
                                        <tr onclick="window.location='{{ route('account-manager.projects.edit', $milestone->project_id) }}'">
                                            <td>{{ Str::limit($milestone->milestone_name, 18) }}</td>
                                            <td>{{ Str::limit($milestone->project->business_name ?? 'N/A', 18) }}</td>
                                            <td><span class="badge-pending-custom">${{ number_format($milestone->milestone_value, 2) }}</span></td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted py-3">No pending milestones</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
@endpush
