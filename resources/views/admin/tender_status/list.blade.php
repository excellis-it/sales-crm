@extends('admin.layouts.master')
@section('title')
    Tender Status Management - {{ env('APP_NAME') }}
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
                        <h3 class="page-title">Tender Status</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.tender-statuses.index') }}">Tender Status</a>
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
                                <h4 class="mb-0">Tender Status Details</h4>
                            </div>
                            <div class="col-md-6 text-end">
                                <a href="javascript:void(0);" class="btn px-5 submit-btn" data-bs-toggle="offcanvas"
                                data-bs-target="#offcanvasRight" aria-controls="offcanvasRight"><i
                                        class="fa fa-plus"></i> Add Status</a>
                            </div>
                        </div>
                    </div>

                    <hr />
                    <div class="row justify-content-end">
                        <div class="col-md-6">
                            <div class="row g-1 justify-content-end">
                                <div class="col-md-8 pr-0">
                                    <div class="search-field">
                                        <input type="text" name="search" id="search" placeholder="search..." required
                                            class="form-control rounded_search">
                                        <button class="submit_search" id="search-button"> <span class=""><i
                                                    class="fa fa-search"></i></span></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight"
                        aria-labelledby="offcanvasRightLabel">
                        <div class="offcanvas-header">
                            <button type="button" class="text-reset cls_btn_left" data-bs-dismiss="offcanvas"
                                aria-label="Close">
                                <i class="fa fa-chevron-right" aria-hidden="true"></i>
                            </button>
                            <h4 id="offcanvasEditLabel">Add Tender Status</h4>
                        </div>
                        <div class="offcanvas-body">
                            <form action="{{ route('admin.tender-statuses.store') }}" method="post"
                                id="tender-statuses-form-create">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label for="inputEnterYourName" class="col-form-label"> Name <span
                                                style="color: red;">*</span></label>
                                        <input type="text" name="name"  class="form-control"
                                            value="{{ old('name') }}" placeholder="Enter Status Name">
                                        <span class="text-danger name_error"></span>

                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label for="status" class="col-form-label"> Status
                                            <span style="color: red;">*</span></label>
                                        <select name="status"  class="form-control">
                                            <option value="">Select a Status</option>
                                            <option value="1">Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
                                        <span class="text-danger status_error"></span>
                                    </div>
                                </div>
                                <div class="d-flex alin-items-center w-100 text-end">
                                    <button class="print_btn cancel_btn me-3" type="reset"><i
                                            class="far fa-times-circle"></i>
                                        Cancel</button>
                                    <button class="print_btn" type="submit"><i class="far fa-check-circle"></i>
                                        Create</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasEdit"
                        aria-labelledby="offcanvasRightLabel">
                        <div class="offcanvas-header">
                            <button type="button" class="text-reset cls_btn_left" data-bs-dismiss="offcanvas"
                                aria-label="Close">
                                <i class="fa fa-chevron-right" aria-hidden="true"></i>
                            </button>
                            <h4 id="offcanvasEditLabel">Edit Tender Status</h4>
                        </div>
                        <div class="offcanvas-body">
                            <form action="" method="POST" id="tender-statuses-edit-form">
                                @method('PUT')
                                @csrf
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label for="edit_name" class="col-form-label"> Name <span
                                                style="color: red;">*</span></label>
                                        <input type="text" name="name"  class="form-control" id="edit_name"
                                            value="{{ old('name') }}" placeholder="Enter Status Name">
                                        <span class="text-danger name_msg_error"></span>

                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label for="edit_status" class="col-form-label"> Status
                                            <span style="color: red;">*</span></label>
                                        <select name="status"  class="form-control" id="edit_status">
                                            <option value="">Select a Status</option>
                                            <option value="1">Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
                                        <span class="text-danger status_msg_error"></span>
                                    </div>
                                </div>
                                <div class="d-flex alin-items-center w-100 text-end">
                                    <button class="print_btn cancel_btn me-3" type="reset"><i
                                            class="far fa-times-circle"></i>
                                        Cancel</button>
                                    <button class="print_btn" type="submit"><i class="far fa-check-circle"></i>
                                        Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    </div>
                    <div class="table-responsive" id="tender_statuses_data">
                        @include('admin.tender_status.table')

                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#search').on('keyup', function() {
                var text = $('#search').val();
                url = "{{ route('admin.tender-statuses.search') }}"
                $('#loading').addClass('loading');
                $('#loading-content').addClass('loading-content');
                $.ajax({
                    url: url,
                    type: 'GET',
                    data: {
                        text: text,
                    },
                    success: function(response) {
                        $('#tender_statuses_data').html(response.view);
                        $('#loading').removeClass('loading');
                        $('#loading-content').removeClass('loading-content');
                    }
                });
            });
        });
    </script>
    <script>
        $(document).on('click', '.delete-status', function(e) {
            swal({
                    title: "Are you sure?",
                    text: "To delete this tender status.",
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
        $(document).on('change', '.toggle-class', function() {
            var status = $(this).prop('checked') == true ? 1 : 0;
            var id = $(this).data('id');

            $.ajax({
                type: "GET",
                dataType: "json",
                url: '{{ route('admin.tender-statuses.change-status') }}',
                data: {
                    'status': status,
                    'id': id
                },
                success: function(resp) {
                    toastr.success(resp.success);
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#tender-statuses-form-create').submit(function(e) {
                e.preventDefault();

                var formData = $(this).serialize();
                $('#loading').addClass('loading');
                $('#loading-content').addClass('loading-content');
                $.ajax({
                    url: $(this).attr('action'),
                    type: $(this).attr('method'),
                    data: formData,
                    success: function(response) {
                        $('#loading').removeClass('loading');
                        $('#loading-content').removeClass('loading-content');
                        window.location.reload();
                        toastr.success('Tender Status Added Successfully');
                    },
                    error: function(xhr) {
                        $('.text-danger').html('');
                        var errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            $('.' + key + '_error').html(value[0]);
                        });
                        $('#loading').removeClass('loading');
                        $('#loading-content').removeClass('loading-content');
                    }
                });
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $(document).on('click', '.edit-route', function() {
                var route = $(this).data('route');
                $('#loading').addClass('loading');
                $('#loading-content').addClass('loading-content');
                $.ajax({
                    url: route,
                    type: 'GET',
                    success: function(response) {
                        var status = response.tender_status;

                        $('#edit_name').val(status.name);
                        $('#edit_status').val(status.status);
                        var updateRoute =
                            "{{ route('admin.tender-statuses.update', ['tender_status' => ':id']) }}";
                        updateRoute = updateRoute.replace(':id', status.id);
                        $('#tender-statuses-edit-form').attr('action', updateRoute);
                        $('#loading').removeClass('loading');
                        $('#loading-content').removeClass('loading-content');
                        $('#offcanvasEdit').offcanvas('show');
                    },
                    error: function(xhr) {
                        $('#loading').removeClass('loading');
                        $('#loading-content').removeClass('loading-content');
                        console.log(xhr);
                    }
                });
            });

            $('#tender-statuses-edit-form').submit(function(e) {
                e.preventDefault();

                var formData = $(this).serialize();

                $.ajax({
                    url: $(this).attr('action'),
                    type: $(this).attr('method'),
                    data: formData,
                    success: function(response) {
                        window.location.reload();
                        toastr.success('Tender Status updated successfully');
                    },
                    error: function(xhr) {
                        var errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            $('.' + key + '_msg_error').html(value[0]);
                        });
                        $('#loading').removeClass('loading');
                        $('#loading-content').removeClass('loading-content');
                    }
                });
            });
        });


    </script>
@endpush
