@extends('admin.layouts.master')
@section('title')
    All Account manager Details - {{ env('APP_NAME') }}
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
                    <div class="col-md-8">
                        <h3 class="page-title">Account managers Information</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('account_managers.index') }}">Account managers</a></li>
                            <li class="breadcrumb-item active">List</li>
                        </ul>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex">
                            <select class="form-select w-50 rounded-0" aria-label="Default select example">
                              <option selected>All (29)</option>
                              <option value="1">Active (20)</option>
                              <option value="2">Inactive (9)</option>
                            </select>
                            <a href="{{ route('account_managers.create') }}" class="btn add-btn"> Add New
                                account manager</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                        <div class="row">
                            <div class="col-md-6">
                                <h4 class="mb-0">Account managers Details</h4>
                            </div>

                        </div>
                    </div>

                    <hr />
                    <div class="table-responsive">
                        <table id="myTable" class="dd table table-striped" style="width:100%">
                            <thead>
                                <tr>
                                    <th> Name</th>
                                    <th> Email</th>
                                    <th> Phone</th>
                                    <th>Employee Id</th>
                                    <th>Date Of Joining</th>
                                    <th>No. of Project</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($account_managers as $key => $account_manager)
                                    <tr>
                                        <td>{{ $account_manager->name }}</td>
                                        <td>{{ $account_manager->email }}</td>
                                        <td>{{ $account_manager->phone }}</td>
                                        <td>{{ $account_manager->employee_id }}</td>
                                        <td>{{ $account_manager->date_of_joining }}</td>
                                        <td><a href="{{ route('sales-projects.index', ['account_manager_id'=>$account_manager->id]) }}">{{ $account_manager->accountManagerProjects->count() }}</a></td>
                                        <td>
                                            <!--<div class="button-switch">-->
                                            <!--    <input type="checkbox" id="switch-orange" class="switch toggle-class"-->
                                            <!--        data-id="{{ $account_manager['id'] }}"-->
                                            <!--        {{ $account_manager['status'] ? 'checked' : '' }} />-->
                                            <!--    <label for="switch-orange" class="lbl-off"></label>-->
                                            <!--    <label for="switch-orange" class="lbl-on"></label>-->
                                            <!--</div>-->
                                            <span class="edit_active">
                                                <i class="fas fa-edit"></i> Active
                                            </span>
                                        </td>
                                        <td>
                                            <a title="Edit Account manager" data-route=""
                                                href="{{ route('account_managers.edit', $account_manager->id) }}" class="edit_acma"><i
                                                    class="fas fa-edit"></i></a> &nbsp;&nbsp;

                                            <a title="Delete Account manager"
                                                data-route="{{ route('account_managers.delete', $account_manager->id) }}  class="delete_acma""
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
                        "targets": [5, 6]
                    },
                    {
                        "orderable": true,
                        "targets": [0, 1, 2, 3, 4 ]
                    }
                ]
            });

        });
    </script>
    <script>
        $(document).on('click', '#delete', function(e) {
            swal({
                    title: "Are you sure?",
                    text: "To delete this account_manager.",
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
                url: '{{ route("account_managers.change-status") }}',
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
@endpush
