@extends('tender_user.layouts.master')

@section('title')
    Dashboard - {{ env('APP_NAME') }}
@endsection

@push('styles')
<style>
    .dash-card {
        background: #fff;
        border-radius: 15px;
        border: none;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        transition: transform 0.3s ease;
        margin-bottom: 30px;
    }
    .dash-card:hover {
        transform: translateY(-5px);
    }
    .dash-card-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: #fff;
        margin-bottom: 20px;
    }
    .icon-blue { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    .icon-orange { background: linear-gradient(135deg, #ff9b44 0%, #fc6075 100%); }
    .icon-green { background: linear-gradient(135deg, #0ba360 0%, #3cba92 100%); }
    .icon-red { background: linear-gradient(135deg, #f85032 0%, #e73827 100%); }

    .dash-card-title {
        font-size: 14px;
        color: #888;
        font-weight: 600;
        text-transform: uppercase;
        margin-bottom: 5px;
    }
    .dash-card-value {
        font-size: 24px;
        font-weight: 700;
        color: #333;
    }
    .recent-list-item {
        border-left: 3px solid #ff9b44;
        padding: 15px;
        background: #fdfdfd;
        border-radius: 0 8px 8px 0;
        margin-bottom: 10px;
        transition: all 0.2s ease;
    }
    .recent-list-item:hover {
        background: #fff4ec;
    }
    .status-badge {
        font-size: 11px;
        padding: 3px 10px;
        border-radius: 12px;
    }
</style>
@endpush

@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">Welcome, {{ Auth::user()->name }}!</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item active">Dashboard Overview</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Quarterly Goal Card -->
            <div class="row mb-2">
                <div class="col-md-12">
                    <div class="card dash-card" style="border-left: 4px solid #ff9b44;">
                        <div class="card-body py-3">
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="dash-card-icon icon-orange" style="width:50px;height:50px;border-radius:10px;flex-shrink:0;">
                                        <i class="la la-bullseye"></i>
                                    </div>
                                    <div>
                                        <div class="dash-card-title">Quarterly Target — {{ $quarterLabel }}</div>
                                        @if($quarterlyTarget > 0)
                                            <div class="dash-card-value">
                                                ₹{{ number_format($quarterlyAchieve, 2) }}L
                                                <span style="font-size:14px;color:#888;">/ ₹{{ number_format($quarterlyTarget, 2) }}L</span>
                                            </div>
                                        @else
                                            <div class="dash-card-value" style="font-size:16px;color:#aaa;">No Goal Set for {{ $quarterLabel }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div style="min-width:200px;flex:1;max-width:400px;">
                                    @if($quarterlyTarget > 0)
                                        @php
                                            $qColor = $quarterlyPct >= 80 ? '#0ba360' : ($quarterlyPct >= 50 ? '#ff9b44' : '#f85032');
                                        @endphp
                                        <div class="d-flex justify-content-between mb-1">
                                            <small style="color:#888;">Progress</small>
                                            <small style="font-weight:700;color:{{ $qColor }};">{{ $quarterlyPct }}%</small>
                                        </div>
                                        <div class="progress" style="height:8px;border-radius:4px;">
                                            <div class="progress-bar" role="progressbar"
                                                style="width:{{ $quarterlyPct }}%;background:{{ $qColor }};">
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Rows -->
            <div class="row">
                <div class="col-md-6 col-sm-6 col-lg-3">
                    <div class="card dash-card">
                        <div class="card-body">
                            <div class="dash-card-icon icon-blue">
                                <i class="la la-briefcase"></i>
                            </div>
                            <div class="dash-card-title">Total Projects</div>
                            <div class="dash-card-value">{{ $totalProjects }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-lg-3">
                    <div class="card dash-card">
                        <div class="card-body">
                            <div class="dash-card-icon icon-orange">
                                <i class="la la-money-bill"></i>
                            </div>
                            <div class="dash-card-title">Total Value (Lakhs)</div>
                            <div class="dash-card-value">₹{{ number_format($totalValue, 2) }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-lg-3">
                    <div class="card dash-card">
                        <div class="card-body">
                            <div class="dash-card-icon icon-green">
                                <i class="la la-check-circle"></i>
                            </div>
                            <div class="dash-card-title">Paid Milestones</div>
                            <div class="dash-card-value">{{ $paidMilestones->count ?? 0 }}</div>
                            <small class="text-muted">₹{{ number_format($paidMilestones->total_value ?? 0, 2) }} Lakhs</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-lg-3">
                    <div class="card dash-card">
                        <div class="card-body">
                            <div class="dash-card-icon icon-red">
                                <i class="la la-exclamation-circle"></i>
                            </div>
                            <div class="dash-card-title">Due Milestones</div>
                            <div class="dash-card-value">{{ $dueMilestones->count ?? 0 }}</div>
                            <small class="text-muted">₹{{ number_format($dueMilestones->total_value ?? 0, 2) }} Lakhs</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Recent Tenders -->
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                        <div class="card-header bg-white border-0 py-3">
                            <h4 class="card-title mb-0 fw-bold"><i class="la la-history me-2"></i>Recent Projects</h4>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>Project</th>
                                            <th>Status</th>
                                            <th>Value</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(count($recentTenders) > 0)
                                            @foreach($recentTenders as $tender)
                                                <tr>
                                                    <td>
                                                        <span class="fw-bold">{{ $tender->tender_id_ref_no }}</span><br>
                                                        <small class="text-muted">{{ Str::limit($tender->tender_name, 30) }}</small>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-inverse-{{ $tender->tenderStatus->status ? 'success' : 'danger' }} status-badge">
                                                            {{ $tender->tenderStatus->name }}
                                                        </span>
                                                    </td>
                                                    <td>₹{{ $tender->tender_value_lakhs }} L</td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="3" class="text-center py-4 text-muted">No projects found</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer bg-white border-0 text-center py-3">
                            <a href="{{ route('tender-user.tender-projects.index') }}" class="text-orange fw-bold">View All Projects</a>
                        </div>
                    </div>
                </div>

                <!-- Recent Follow-ups Timeline -->
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                        <div class="card-header bg-white border-0 py-3">
                            <h4 class="card-title mb-0 fw-bold"><i class="la la-comments me-2"></i>Recent Activity</h4>
                        </div>
                        <div class="card-body">
                            <div class="recent-followups">
                                @if(count($recentFollowups) > 0)
                                    @foreach($recentFollowups as $followup)
                                        <div class="recent-list-item">
                                            <div class="d-flex justify-content-between">
                                                <span class="fw-bold text-dark">{{ $followup->tenderProject->tender_id_ref_no }}</span>
                                                <small class="text-muted">{{ $followup->created_at->diffForHumans() }}</small>
                                            </div>
                                            <div class="mt-1 text-mutedSmall">
                                                <i class="la la-quote-left me-1"></i> {{ Str::limit($followup->comment, 80) }}
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="text-center py-4 text-muted">No recent activity recorded</div>
                                @endif
                            </div>
                        </div>
                        <div class="card-footer bg-white border-0 text-center py-3">
                            <a href="{{ route('tender-user.payments.index') }}" class="text-orange fw-bold">View Payments History</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
