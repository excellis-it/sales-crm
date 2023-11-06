@extends('account_manager.layouts.master')
@section('title')
    {{ env('APP_NAME') }} | View Project Details
@endsection
@push('styles')
@endpush

@section('content')
    <div class="page-wrapper">

        <div class="content container-fluid">

            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">#{{ $project->client_name }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('account-manager.projects.index') }}">Project</a>
                            </li>
                            <li class="breadcrumb-item active">View Project Details</li>
                        </ul>
                    </div>
                    <div class="col-auto float-end ms-auto">
                        {{-- <a href="#" class="btn add-btn" data-bs-toggle="modal" data-bs-target="#add_group"><i
                    class="fa fa-plus"></i> Add Project Details</a> --}}
                    </div>
                </div>
            </div>

            <div class="tab-content">
                {{-- <h3 class="card"><span class="">Doctor details of Dr. {{ $project['name'] }}.</span></h3> --}}
                <div id="emp_profile" class="pro-overview tab-pane fade show active">
                    <div class="row">
                        <div class="col-md-6 d-flex">
                            <div class="card profile-box flex-fill">
                                <div class="card-body">
                                    <ul class="personal-info">
                                        <li>
                                            <div class="title">Client Name:-</div>
                                            <div class="text">
                                                {{ $project->client_name }}
                                            </div>
                                        </li>

                                        <li>
                                            <div class="title">Client Email:-</div>
                                            <div class="text">
                                                {{ $project->client_email }}
                                            </div>
                                        </li>
                                        <li>
                                            <div class="title">Client Phone:-</div>
                                            <div class="text">
                                                {{ $project->client_phone }}
                                            </div>
                                        </li>
                                        <li>
                                            <div class="title">Client Address:-</div>
                                            <div class="text">
                                                {{ $project->client_address }}
                                            </div>
                                        </li>
                                        <li>
                                            <div class="title">Client Website:-</div>
                                            <div class="text">
                                                <a href="{{ $project->website ?? '' }}" target="blank">
                                                    {{ $project->website ?? '' }}</a>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="title">Business Name:-</div>
                                            <div class="text">
                                                {{ $project->business_name ?? '' }}
                                            </div>
                                        </li>
                                    </ul>
                                    <h4>Documents Details :</h4>
                                    @if ($documents->count() > 0)
                                        @foreach ($documents as $key => $document)
                                            <a
                                                href="{{ route('account-manager.projects.document.download', $document->id) }}">
                                                <i class="fas fa-download"></i></a>&nbsp;&nbsp;
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 d-flex">
                            <div class="card profile-box flex-fill">
                                <div class="card-body">
                                    {{-- <h3 class="card-title"><u>Billing Information</u> </h3> --}}

                                    <ul class="personal-info">
                                        <li>
                                            <div class="title">Project Type:-</div>
                                            <div class="text">
                                                <span class="badge bg-info">{{ $project->projectTypes->name ?? '' }}</span>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="title">Project Value:-</div>
                                            <div class="text">
                                                {{ $project->project_value }}
                                            </div>
                                        </li>
                                        <li>
                                            <div class="title">Project Upfront:-</div>
                                            <div class="text">
                                                {{ $project->project_upfront }}
                                            </div>
                                        </li>
                                        <li class="">
                                            <div class="title  ">Due Amount:-</div>
                                            <div class="text">
                                                {{ (int)$project->project_value - (int)$project->project_upfront }}
                                            </div>
                                        </li>
                                        <li>
                                            <div class="title">Currency:-</div>
                                            <div class="text">
                                                {{ $project->currency ?? '' }}
                                            </div>
                                        </li>
                                        <li class="">
                                            <div class="title  ">Payment Mode:-</div>
                                            <div class="text">
                                                {{ $project->payment_mode ?? '' }}
                                            </div>
                                        </li>

                                        <li class="">
                                            <div class="title  ">Sale Date:-</div>
                                            <div class="text">
                                                {{ ($project->sale_date) ?  date('d M, Y', strtotime($project->sale_date)) : '' }}
                                            </div>
                                        </li>
                                        <li class="">
                                            <div class="title  ">Project Opener:-</div>
                                            <div class="text">
                                                {{ $project->projectOpener->name ?? '' }}
                                            </div>
                                        </li>
                                        <li class="">
                                            <div class="title  ">Project Closer:-</div>
                                            <div class="text">
                                                {{ $project->projectCloser->name ?? ''}}
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if ($project->projectMilestones->count() > 0)
                        <div class="row">
                            <div class="col-md-12 d-flex">
                                <div class="card profile-box flex-fill">
                                    <div class="card-body">
                                        <h3 class="card-title">Milestone Details

                                        </h3>
                                        <table id="myTable" class="dd table table-striped table-bordered"
                                            style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>Milestone Name</th>
                                                    <th>Milestone value ({{ $project->currency }})</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($project->projectMilestones as $key => $milestone)
                                                    <tr>
                                                        <td>
                                                            {{ $milestone->milestone_name }}
                                                        </td>
                                                        <td>
                                                            {{ $milestone->milestone_value }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>


            </div>

        </div>
    @endsection

    @push('scripts')
    @endpush
