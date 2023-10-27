@extends('admin.layouts.master')
@section('title')
    All Project Details - {{ env('APP_NAME') }}
@endsection
@push('styles')
    <style>
        .dataTables_filter {
            margin-bottom: 10px !important;
        }
    </style>
@endpush

@section('content')
    <section id="loading">
        <div id="loading-content"></div>
    </section>
    <div class="page-wrapper">

        <div class="content container-fluid">

            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Projects Information</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('sales-projects.index') }}">Projects</a></li>
                            <li class="breadcrumb-item active">List</li>
                        </ul>
                    </div>

                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                        <div class="row">
                            <div class="col-md-6">
                                <h4 class="mb-0">Projects Details</h4>
                            </div>
                            <div class="col-md-6 text-end">
                                <a href="{{ route('sales-projects.create') }}" class="btn px-5 submit-btn"><i class="fa fa-plus"></i> Add a
                                    Project</a>
                            </div>
                        </div>
                    </div>

                    <hr />
                    <div class="table-responsive">
                        <table id="myTable" class="dd table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th> Date</th>
                                    <th> Sale By</th>
                                    <th> Sales Manager Email</th>
                                    <th> Customer Name </th>
                                    <th> Phone Number</th>
                                    <th> Project Value</th>
                                    <th> Project Upfront</th>
                                    <th> Currency</th>
                                    <th> Payment Mode</th>
                                    <th> Due Amount</th>
                                    <th> Status</th>
                                    <th> Action </th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- @foreach ($projects as $key => $project)
                                    <tr>
                                        <td>
                                            {{ date('d-m-Y', strtotime($project->sale_date)) }}
                                        </td>
                                        <td>
                                            {{ $project->salesManager->name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $project->salesManager->email ?? '' }}
                                        </td>
                                        <td>
                                            {{ $project->client_name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $project->client_phone ?? '' }}
                                        </td>
                                        <td>
                                            {{ $project->project_value ?? '' }}
                                        </td>

                                        <td>
                                            {{ $project->project_upfront ?? '' }}
                                        </td>
                                        <td>
                                            {{ $project->currency ?? '' }}
                                        </td>
                                        <td>
                                            {{ $project->payment_mode ?? '' }}
                                        </td>
                                        <td>
                                            {{ (int)$project->project_value - (int)$project->project_upfront }}
                                        </td>
                                        <td>
                                            @if($project->assigned_to == null)
                                                <span class="badge bg-danger">Not Assigned</span>
                                            @else
                                                <span class="badge bg-success">Assigned</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a title="Edit Project" data-route=""
                                                href="{{ route('sales-projects.edit', $project->id) }}"><i
                                                    class="fas fa-edit"></i></a> &nbsp;&nbsp;

                                            <a title="View Project" data-route=""
                                                href="{{ route('sales-projects.show', $project->id) }}"><i
                                                    class="fas fa-eye"></i></a> &nbsp;&nbsp;


                                            <a title="Delete Project"
                                                data-route="{{ route('sales-projects.delete', $project->id) }}"
                                                href="javascipt:void(0);" id="delete"><i class="fas fa-trash"></i></a>
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
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            var sales_manager_id = '{{$_GET["sales_manager_id"] ?? ""}}'
            var account_manager_id = '{{$_GET["account_manager_id"] ?? ""}}';
            var table = $('#myTable').DataTable({
                // descending order
                "order": [[ 0, "desc" ]],
                "columnDefs": [
                    {
                        "orderable": false,
                        "targets": [10]
                    },
                    {
                        "orderable": true,
                        "targets": [0, 1, 2, 3, 4, 5, 6, 7, 8]
                    }
                ],

                processing: true,
                serverSide: true,
                ajax: "{{ route('sales-projects.ajax-list') }}?sales_manager_id=" + sales_manager_id + "&account_manager_id=" + account_manager_id,
                columns: [{
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'sale_by',
                        name: 'sale_by',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'sales_manager_email',
                        name: 'sales_manager_email',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'client_name',
                        name: 'client_name'
                    },
                    {
                        data: 'client_phone',
                        name: 'client_phone'
                    },
                    {
                        data: 'project_value',
                        name: 'project_value'
                    },
                    {
                        data: 'project_upfront',
                        name: 'project_upfront'
                    },
                    {
                        data: 'currency',
                        name: 'currency'
                    },
                    {
                        data: 'payment_mode',
                        name: 'payment_mode'
                    },
                    {
                        data: 'due_amount',
                        name: 'due_amount'
                    },
                    {
                        data: 'assigned_to',
                        name: 'assigned_to'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    }
                ]
            });

        });
    </script>
    <script>
        $(document).on('click', '#delete', function(e) {
            swal({
                    title: "Are you sure?",
                    text: "To delete this project.",
                    type: "warning",
                    confirmButtonText: "Yes",
                    showCancelButton: true
                })
                .then((result) => {
                    if (result.value) {
                        window.location = $(this).data('route');
                    } else if (result.dismiss === 'cancel') {
                        swal(
                            'Cancelled',
                            'Your stay here :)',
                            'error'
                        )
                    }
                })
        });
    </script>
    {{-- <script>
        $('.toggle-class').change(function() {
            var status = $(this).prop('checked') == true ? 1 : 0;
            var user_id = $(this).data('id');

            $.ajax({
                type: "GET",
                dataType: "json",
                url: '{{ route('sales-projects.change-status') }}',
                data: {
                    'status': status,
                    'user_id': user_id
                },
                success: function(resp) {
                    console.log(resp.success)
                }
            });
        });
    </script> --}}
    <script>
        $(document).ready(function() {
           //how to place holder in "jquery datatable" search box
            $('#myTable_filter input').attr("placeholder", "Search");
        });


    </script>
@endpush
