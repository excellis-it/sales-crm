@extends('admin.layouts.master')
@section('title')
    All BDM Details - {{ env('APP_NAME') }}
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
                        <h3 class="page-title">BDM Information</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('business-development-managers.index') }}">BDM</a></li>
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
                                <h4 class="mb-0">BDM Details</h4>
                            </div>
                            <div class="col-md-6 text-end">
                                <a href="{{ route('business-development-managers.create') }}" class="btn px-5 submit-btn"><i class="fa fa-plus"></i> Add a
                                    BDM</a>
                            </div>
                        </div>
                    </div>

                    <hr />
                    <div class="table-responsive">
                        <table id="myTable" class="dd table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th> Name</th>
                                    <th> Email</th>
                                    <th> Phone</th>
                                    <th>Employee Id</th>
                                    <th>Date Of Joining</th>
                                    <th>No. of sales</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($business_development_managers as $key => $business_development_manager)
                                    <tr>
                                        <td>{{ $business_development_manager->name }}</td>
                                        <td>{{ $business_development_manager->email }}</td>
                                        <td>{{ $business_development_manager->phone }}</td>
                                        <td>{{ $business_development_manager->employee_id }}</td>
                                        <td>{{ $business_development_manager->date_of_joining }}</td>
                                        <td><a href="{{ route('sales-projects.index', ['sales_manager_id'=>$business_development_manager->id]) }}">{{ $business_development_manager->projects->count() }}</a></td>
                                        <td>
                                            <div class="button-switch">
                                                <input type="checkbox" id="switch-orange" class="switch toggle-class"
                                                    data-id="{{ $business_development_manager['id'] }}"
                                                    {{ $business_development_manager['status'] ? 'checked' : '' }} />
                                                <label for="switch-orange" class="lbl-off"></label>
                                                <label for="switch-orange" class="lbl-on"></label>
                                            </div>
                                        </td>
                                        <td>
                                            <a title="Edit BDM" data-route=""
                                                href="{{ route('business-development-managers.edit', $business_development_manager->id) }}"><i
                                                    class="fas fa-edit"></i></a> &nbsp;&nbsp;

                                            <a title="Delete BDM"
                                                data-route="{{ route('business-development-managers.delete', $business_development_manager->id) }}"
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
                        "targets": [5, 6, 7]
                    },
                    {
                        "orderable": true,
                        "targets": [0, 1, 2, 3,4]
                    }
                ]
            });

        });
    </script>
    <script>
        $(document).on('click', '#delete', function(e) {
            swal({
                    title: "Are you sure?",
                    text: "To delete this business_development_manager.",
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
    <script>
        $('.toggle-class').change(function() {
            var status = $(this).prop('checked') == true ? 1 : 0;
            var user_id = $(this).data('id');

            $.ajax({
                type: "GET",
                dataType: "json",
                url: '{{ route("business-development-managers.change-status") }}',
                data: {
                    'status': status,
                    'user_id': user_id
                },
                success: function(resp) {
                    console.log(resp.success)
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
