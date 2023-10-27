@extends('bdm.layouts.master')
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
                            <li class="breadcrumb-item"><a href="{{ route('bdm.projects.index') }}">Projects</a></li>
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
                                <a href="{{ route('bdm.projects.create') }}" class="btn px-5 submit-btn"><i class="fa fa-plus"></i> Add a
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
                                    <th> Business Name</th>
                                    <th> Customer Name </th>
                                    <th>Phone Number</th>
                                    <th>Project Type</th>
                                    <th>Project Value</th>
                                    <th>Project Upfront</th>
                                    <th>Currency</th>
                                    <th>Payment Mode</th>
                                    <th>Due Amount</th>
                                    <th>
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>

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
        var table = $('#myTable').DataTable({
            "order": [
                [0, "desc"]
            ],
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
            ajax: "{{ route('bdm.projects.ajax-list') }}",
            columns: [
                {
                    data: 'sale_date',
                    name: 'sale_date'
                },
                {
                    data: 'business_name',
                    name: 'business_name'
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
                    data: 'project_type',
                    name: 'project_type'
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
                    name: 'due_amount',
                    orderable: false,
                    searchable: false
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
    <script>
        $(document).ready(function() {
           //how to place holder in "jquery datatable" search box
            $('#myTable_filter input').attr("placeholder", "Search");
        });


    </script>
@endpush
