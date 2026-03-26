@extends('account_manager.layouts.master')
@section('title')
    {{ env('APP_NAME') }} | View Project Details
@endsection
@push('styles')
<style>
    .upsale-card { border-left: 4px solid #6f42c1; }
    .badge-upsale { background-color: #6f42c1; color: #fff; }
</style>
@endpush

@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">

            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">#{{ $project->client_name }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('account-manager.projects.index') }}">Project</a></li>
                            <li class="breadcrumb-item active">View Project Details</li>
                        </ul>
                    </div>
                </div>
            </div>

            @if(session('message'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('message') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @php
                $upsales = $project->upsales()->with('milestones', 'upfrontMilestone')->get();
                $totalUpsaleValue   = $upsales->sum('upsale_value');
                $totalUpsaleUpfront = $upsales->sum('upsale_upfront');
                $grandTotalValue    = $project->project_value + $totalUpsaleValue;
                $totalUpfront       = $project->project_upfront + $totalUpsaleUpfront;

                $paidMilestones = $project->allProjectMilestones
                    ->where('payment_status', 'Paid')
                    ->filter(function($m) {
                        return !in_array($m->milestone_type, ['upfront', 'upsale_upfront'])
                            && !in_array($m->milestone_name, ['Upfront', 'Upsale Upfront']);
                    })
                    ->sum('milestone_value');

                $totalPaid = $totalUpfront + $paidMilestones;
                $dueAmount = $grandTotalValue - $totalPaid;
            @endphp

            <div class="tab-content">
                <div id="emp_profile" class="pro-overview tab-pane fade show active">
                    <div class="row">
                        <div class="col-md-6 d-flex">
                            <div class="card profile-box flex-fill">
                                <div class="card-body">
                                    <ul class="personal-info">
                                        <li>
                                            <div class="title">Client Name</div>
                                            <div class="text">{{ $project->client_name }}</div>
                                        </li>
                                        <li>
                                            <div class="title">Client Email</div>
                                            <div class="text">{{ $project->client_email }}</div>
                                        </li>
                                        <li>
                                            <div class="title">Client Phone</div>
                                            <div class="text">{{ $project->client_phone }}</div>
                                        </li>
                                        <li>
                                            <div class="title">Client Address</div>
                                            <div class="text">{{ $project->client_address }}</div>
                                        </li>
                                        <li>
                                            <div class="title">Client Website</div>
                                            <div class="text">
                                                <a href="{{ $project->website ?? '' }}" target="blank">{{ $project->website ?? '' }}</a>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="title">Business Name</div>
                                            <div class="text">{{ $project->business_name ?? '' }}</div>
                                        </li>
                                    </ul>
                                    <h4>Documents:</h4>
                                    @if ($documents->count() > 0)
                                        @foreach ($documents as $document)
                                            <a href="{{ route('account-manager.projects.document.download', $document->id) }}">
                                                <i class="fas fa-download"></i>
                                            </a>&nbsp;&nbsp;
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 d-flex">
                            <div class="card profile-box flex-fill">
                                <div class="card-body">
                                    <ul class="personal-info">
                                        <li>
                                            <div class="title">Project Type</div>
                                            <div class="text">
                                                @foreach($project->projectTypes as $type)
                                                    <span class="badge bg-info">{{ $type->name }}</span>
                                                @endforeach
                                            </div>
                                        </li>
                                        <li>
                                            <div class="title">Base Project Value</div>
                                            <div class="text">{{ number_format($project->project_value, 2) }} {{ $project->currency }}</div>
                                        </li>
                                        @if($totalUpsaleValue > 0)
                                        <li>
                                            <div class="title">Total Upsale Value</div>
                                            <div class="text fw-bold" style="color:#6f42c1;">+ {{ number_format($totalUpsaleValue, 2) }}</div>
                                        </li>
                                        <li>
                                            <div class="title fw-bold">Grand Total</div>
                                            <div class="text fw-bold text-success">{{ number_format($grandTotalValue, 2) }} {{ $project->currency }}</div>
                                        </li>
                                        @endif
                                        <li>
                                            <div class="title">Base Upfront</div>
                                            <div class="text">{{ number_format($project->project_upfront, 2) }}</div>
                                        </li>
                                        @if($totalUpsaleUpfront > 0)
                                        <li>
                                            <div class="title">Upsale Upfront</div>
                                            <div class="text" style="color:#6f42c1;">+ {{ number_format($totalUpsaleUpfront, 2) }}</div>
                                        </li>
                                        @endif
                                        <li>
                                            <div class="title">Total Received</div>
                                            <div class="text text-success">{{ number_format($totalPaid, 2) }}</div>
                                        </li>
                                        <li>
                                            <div class="title fw-bold">Due Amount</div>
                                            <div class="text fw-bold {{ $dueAmount > 0 ? 'text-danger' : 'text-success' }}">
                                                {{ number_format($dueAmount, 2) }}
                                            </div>
                                        </li>
                                        <li>
                                            <div class="title">Currency</div>
                                            <div class="text">{{ $project->currency }}</div>
                                        </li>
                                        <li>
                                            <div class="title">Payment Mode</div>
                                            <div class="text">{{ $project->payment_mode }}</div>
                                        </li>
                                        <li>
                                            <div class="title">Sale Date</div>
                                            <div class="text">{{ $project->sale_date ? date('d M, Y', strtotime($project->sale_date)) : '' }}</div>
                                        </li>
                                        <li>
                                            <div class="title">Project Opener</div>
                                            <div class="text">{{ $project->projectOpener->name ?? '' }}</div>
                                        </li>
                                        <li>
                                            <div class="title">Project Closer</div>
                                            <div class="text">{{ $project->projectCloser->name ?? '' }}</div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if ($project->projectMilestones->count() > 0)
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card profile-box">
                                <div class="card-body">
                                    <h3 class="card-title">Milestone Details</h3>
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>Milestone Name</th>
                                                <th>Value ({{ $project->currency }})</th>
                                                <th>Status</th>
                                                <th>Payment Date</th>
                                                <th>Mode</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($project->projectMilestones as $milestone)
                                            <tr>
                                                <td>{{ $milestone->milestone_name }}</td>
                                                <td>{{ number_format($milestone->milestone_value, 2) }}</td>
                                                <td>
                                                    <span class="badge {{ $milestone->payment_status == 'Paid' ? 'bg-success' : 'bg-warning text-dark' }}">
                                                        {{ $milestone->payment_status }}
                                                    </span>
                                                </td>
                                                <td>{{ $milestone->payment_date ? date('d M Y', strtotime($milestone->payment_date)) : '-' }}</td>
                                                <td>{{ $milestone->payment_mode ?? '-' }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- Upsales Section --}}
                    @if($upsales->count() > 0)
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header" style="background:#f3eeff;border-left:4px solid #6f42c1;">
                                    <h5 class="mb-0" style="color:#6f42c1;">
                                        <i class="la la-money-bill me-2"></i>Upsales ({{ $upsales->count() }})
                                    </h5>
                                </div>
                                <div class="card-body p-0">
                                    @foreach($upsales as $idx => $upsale)
                                    <div class="border-bottom p-3 upsale-card">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <div>
                                                <span class="badge badge-upsale">#{{ $idx + 1 }}</span>
                                                <span class="text-muted small ms-1">{{ $upsale->upsale_date ? date('d M Y', strtotime($upsale->upsale_date)) : '' }}</span>
                                            </div>
                                        </div>
                                        <div class="row g-2 mb-2 small">
                                            <div class="col-md-3">
                                                <span class="text-muted">Type:</span>
                                                <strong>{{ $upsale->project_type_label }}</strong>
                                            </div>
                                            <div class="col-md-2">
                                                <span class="text-muted">Value:</span>
                                                <strong class="text-primary">{{ number_format($upsale->upsale_value, 2) }} {{ $upsale->upsale_currency }}</strong>
                                            </div>
                                            <div class="col-md-2">
                                                <span class="text-muted">Upfront:</span>
                                                <strong class="text-success">{{ number_format($upsale->upsale_upfront, 2) }}</strong>
                                            </div>
                                            <div class="col-md-2">
                                                <span class="text-muted">Method:</span>
                                                <span>{{ $upsale->upsale_payment_method }}</span>
                                            </div>
                                            <div class="col-md-3">
                                                @php
                                                    $upsalePaid = $upsale->upfrontMilestone ? $upsale->upfrontMilestone->milestone_value : 0;
                                                    $upsalePaid += $upsale->milestones->where('payment_status','Paid')->sum('milestone_value');
                                                    $upsaleDue = $upsale->upsale_value - $upsalePaid;
                                                @endphp
                                                <span class="text-muted">Due:</span>
                                                <strong class="{{ $upsaleDue > 0 ? 'text-danger' : 'text-success' }}">
                                                    {{ number_format($upsaleDue, 2) }}
                                                </strong>
                                            </div>
                                        </div>
                                        @if($upsale->milestones->count() > 0)
                                        <table class="table table-sm table-bordered mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Name</th><th>Value</th><th>Status</th><th>Date</th><th>Mode</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($upsale->milestones as $ms)
                                                <tr>
                                                    <td>{{ $ms->milestone_name }}</td>
                                                    <td>{{ number_format($ms->milestone_value, 2) }}</td>
                                                    <td><span class="badge {{ $ms->payment_status == 'Paid' ? 'bg-success' : 'bg-warning text-dark' }}">{{ $ms->payment_status }}</span></td>
                                                    <td>{{ $ms->payment_date ? date('d M Y', strtotime($ms->payment_date)) : '-' }}</td>
                                                    <td>{{ $ms->payment_mode ?? '-' }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        @endif
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
@endpush
