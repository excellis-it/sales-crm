@extends('sales_excecutive.layouts.master')
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
                            <li class="breadcrumb-item"><a href="{{ route('sales-excecutive.projects.index') }}">Projects</a>
                            </li>
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
                                    <th> Phone Number</th>
                                    <th> Project Type</th>
                                    <th> Project Value</th>
                                    <th> Project Upfront</th>
                                    <th> Currency</th>
                                    <th> Payment Mode</th>
                                    <th> Due Amount</th>
                                    <th>
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- @foreach ($projects as $key => $project)
                                    <tr>
                                        <td>
                                            {{ date('d-m-Y', strtotime($project->sale_date)) }}
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
                                            <span>{{ $project->projectTypes->type ?? '' }}</span>
                                        </td>
                                        <td>
                                            {{ $project->project_value }}
                                        </td>

                                        <td>
                                            {{ $project->project_upfront }}
                                        </td>
                                        <td>
                                            {{ $project->currency }}
                                        </td>
                                        <td>
                                            {{ $project->payment_mode }}
                                        </td>
                                        <td>
                                            {{ (int)$project->project_value - (int)$project->project_upfront }}
                                        </td>

                                        <td>
                                            <a title="View Project" data-route=""
                                                href="{{ route('sales-excecutive.projects.show', $project->id) }}"><i
                                                    class="fas fa-eye"></i></a>
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
            var table = $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                ajax: "{{ route('prospect.ajax-list') }}",
                columns: [{
                        data: 'user_id',
                        name: 'user_id'
                    },
                    {
                        data: 'date',
                        name: 'date'
                    },
                    {
                        data: 'client_name',
                        name: 'client_name'
                    },
                    {
                        data: 'business_name',
                        name: 'business_name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'phone',
                        name: 'phone'
                    },
                    {
                        data: 'transfer_by',
                        name: 'transfer_by'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'service_offered',
                        name: 'service_offered'
                    },
                    {
                        data: 'followup_date',
                        name: 'followup_date'
                    },
                    {
                        data: 'price_quoted',
                        name: 'price_quoted'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });

        });
    </script>
    {{-- <script>
        $(document).ready(function() {
            //Default data table
            $('#myTable').DataTable({
                "aaSorting": [],
                "columnDefs": [{
                        "orderable": false,
                        "targets": [10]
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
@endpush
