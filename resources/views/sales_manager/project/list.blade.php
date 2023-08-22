@extends('sales_manager.layouts.master')
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
                            <li class="breadcrumb-item"><a href="{{ route('projects.index') }}">Projects</a></li>
                            <li class="breadcrumb-item active">List</li>
                        </ul>
                    </div>
                    <div class="col-auto float-end ms-auto">
                        <a href="{{ route('projects.create') }}" class="btn add-btn"><i class="fa fa-plus"></i> Add a
                            Project</a>
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

                        </div>
                    </div>

                    <hr />
                    <div class="table-responsive">
                        <table id="myTable" class="dd table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th> Date</th>
                                    <th> Business Name</th>
                                    <th> Customer Name </th>
                                    <th>Phone Number</th>
                                    <th>Customer Address</th>
                                    <th>Customer Website</th>
                                    <th>Project Type</th>
                                    <th>Project Value</th>
                                    <th>Cureency</th>
                                    <th>Project Upfront</th>
                                    <th>Payment Mode</th>
                                    <th>Due Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($projects as $key => $project)
                                    <tr>
                                        <td>
                                            {{ $project->sale_date->format('d-m-Y') }}
                                        </td>
                                        <td>
                                            {{ $project->business_name }}
                                        </td>
                                        <td>
                                            {{ $project->client_name }}
                                        </td>
                                        <td>
                                            {{ $project->client_phone }}
                                        </td>
                                        <td>
                                            {{ $project->client_address }}
                                        </td>
                                        <td>
                                            {{ $project->website }}
                                        </td>
                                        <td>
                                           @foreach ($project->project_types as $project_type)
                                               <span class="badge bg-info">{{ $project_type->name }}</span>
                                             @endforeach
                                        </td>
                                        <td>
                                            {{ $project->project_value }}
                                        </td>
                                        <td>
                                            {{ $project->currency }}
                                        </td>
                                        <td>
                                            {{ $project->project_upfront }}
                                        </td>
                                        <td>
                                            {{ $project->payment_mode }}
                                        </td>
                                        <td>
                                            {{ $project->project_value - $project->project_upfront }}
                                        </td>
                                        <td>
                                            <a title="Edit Project" data-route=""
                                                href="{{ route('projects.edit', $sales_manager->id) }}"><i
                                                    class="fas fa-edit"></i></a> &nbsp;&nbsp;

                                            <a title="Delete Project"
                                                data-route="{{ route('projects.delete', $sales_manager->id) }}"
                                                href="javascipt:void(0);" id="delete"><i class="fas fa-trash"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
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
            //Default data table
            $('#myTable').DataTable({
                "aaSorting": [],
                "columnDefs": [{
                        "orderable": false,
                        "targets": [3, 4]
                    },
                    {
                        "orderable": true,
                        "targets": [0, 1, 2, ]
                    }
                ]
            });

        });
    </script>
    <script>
        $(document).on('click', '#delete', function(e) {
            swal({
                    title: "Are you sure?",
                    text: "To delete this sales_manager.",
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
                url: '{{ route('projects.change-status') }}',
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
@endpush