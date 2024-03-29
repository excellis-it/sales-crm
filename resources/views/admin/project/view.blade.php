@extends('admin.layouts.master')
@section('title')
    {{ env('APP_NAME') }} | View Project Details
@endsection
@push('styles')
<style>
    .dataTables_filter {
        margin-bottom: 10px !important;
    }
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
                            <li class="breadcrumb-item"><a href="{{ route('sales-projects.index') }}">Project</a></li>
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
                                                <a href="{{ $project->website }}" target="blank">
                                                    {{ $project->website }}</a>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="title">Business Name:-</div>
                                            <div class="text">
                                                {{ $project->business_name }}
                                            </div>
                                        </li>
                                        <li class="">
                                            <div class="title  ">Sale Date:-</div>
                                            <div class="text">
                                                {{ ($project->sale_date) ?  date('d M, Y', strtotime($project->sale_date)) : '' }}
                                            </div>
                                        </li>
                                        <li class="">
                                            <div class="title  ">Sale By:-</div>
                                            <div class="text">
                                                {{ $project->salesManager->name ?? '' }}
                                            </div>
                                        </li>
                                        <li class="">
                                            <div class="title  ">Sales Manager Email:-</div>
                                            <div class="text">
                                                {{ $project->salesManager->email ?? '' }}
                                            </div>
                                        </li>
                                        <li class="">
                                            <div class="title">Assigned To</div>
                                            <div class="text">
                                                {{-- select box  --}}
                                                <select name="assigned_to" id="assigned_to" class="form-control">
                                                    <option value="">Select a account manager</option>
                                                    @foreach ($account_managers as $account_manager)
                                                        <option value="{{ $account_manager->id }}"
                                                            @if ($project->assigned_to == $account_manager->id) selected @endif>
                                                            {{ $account_manager->name }} ({{ $account_manager->email }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </li>
                                    </ul>
                                    @if ($documents->count() > 0)
                                        <h4>Documents Details :</h4>
                                        @foreach ($documents as $key => $document)
                                            <a href="{{ route('sales-projects.document.download', $document->id) }}">
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
                                                {{ $project->project_value ?? '' }}
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
                                                {{ $project->currency }}
                                            </div>
                                        </li>
                                        <li class="">
                                            <div class="title  ">Payment Mode:-</div>
                                            <div class="text">
                                                {{ $project->payment_mode }}
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
                        @if ($project->payment_type == 'Monthly')
                            <div class="row">
                                <div class="col-md-12 d-flex">
                                    <div class="card profile-box flex-fill">
                                        <div class="card-body">
                                            <h3 class="card-title">Monthly Milestone

                                            </h3>
                                            <table id="myTable" class="dd table table-striped  table-hover"
                                                style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th>Milestone value ({{ $project->currency }})</th>
                                                        <th>Status</th>
                                                        <th>Pay Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($project->projectMilestones as $key => $milestone)
                                                        <tr>
                                                            <td>
                                                                {{ $milestone->milestone_value }}
                                                            </td>
                                                            <td>
                                                                @if ($milestone->payment_status)
                                                                    <span
                                                                        class="badge bg-success">{{ $milestone->payment_status }}</span>
                                                                @else
                                                                    <span>N/A</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($milestone->pay_date == null)
                                                                    N/A
                                                                @else
                                                                    {{ date('d-m-Y', strtotime($milestone->pay_date)) }}
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="row">
                                <div class="col-md-12 d-flex">
                                    <div class="card profile-box flex-fill">
                                        <div class="card-body">
                                            <h3 class="card-title">Milestone Details

                                            </h3>
                                            <table id="myTable" class="dd table table-striped  table-hover"
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

                    @endif
                </div>


            </div>

        </div>
    @endsection

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#myTable').DataTable();
            });
            $(document).on('change', '#assigned_to', function() {
                // loader show
                $('#loading').addClass('loading');
                $('#loading-content').addClass('loading-content');

                var assigned_to = $(this).val();
                var project_id = "{{ $project->id }}";
                $.ajax({
                    url: "{{ route('sales-projects.updateAssignedTo') }}",
                    type: "GET",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "assigned_to": assigned_to,
                        "project_id": project_id
                    },
                    success: function(response) {
                        if (response.status) {
                            // loader hide
                            $('#loading').removeClass('loading');
                            $('#loading-content').removeClass('loading-content');
                            toastr.success(response.message);
                        } else {
                            toastr.error(response.message);
                        }
                    }
                });
            });
        </script>
        <script>
            $(document).ready(function() {
               //how to place holder in "jquery datatable" search box
                $('#myTable_filter input').attr("placeholder", "Search");
            });


        </script>
    @endpush
