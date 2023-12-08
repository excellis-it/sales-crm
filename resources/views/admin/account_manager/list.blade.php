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
                            <li class="breadcrumb-item"><a href="{{ route('account_managers.index') }}">Account managers</a>
                            </li>
                            <li class="breadcrumb-item active">List</li>
                        </ul>
                    </div>
                    {{-- <div class="col-md-4">
                        <div class="d-flex">
                            <select class="form-select w-50 rounded-0" aria-label="Default select example">
                              <option selected>All (29)</option>
                              <option value="1">Active (20)</option>
                              <option value="2">Inactive (9)</option>
                            </select>
                            <a href="{{ route('account_managers.create') }}" class="btn add-btn"> Add New
                                account manager</a>
                        </div>
                    </div> --}}
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                        <div class="row">
                            <div class="col-md-6">
                                <h4 class="mb-0">Account managers Details</h4>
                            </div>
                            <div class="col-md-6 text-end">
                                <a href="javascript:void(0);" class="btn px-5 submit-btn" data-bs-toggle="offcanvas"
                                data-bs-target="#offcanvasRight" aria-controls="offcanvasRight"><i
                                        class="fas fa-plus"></i> Add New
                                    account manager</a>
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
                                <h4 id="offcanvasEditLabel">Add Account managers Details</h4>
                            </div>
                            <div class="offcanvas-body">
                                <form action="{{ route('account_managers.store') }}" method="post"
                                    enctype="multipart/form-data" id="account-manager-form-create">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label for="inputEnterYourName" class="col-form-label"> Name <span
                                                    style="color: red;">*</span></label>
                                            <input type="text" name="name" class="form-control"
                                                value="{{ old('name') }}" placeholder="Enter Account manager Name">
                                            <span class="text-danger name_error"></span>

                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label for="inputEnterYourName" class="col-form-label"> Employee Id </label>
                                            <input type="text" name="employee_id" class="form-control"
                                                value="{{ old('employee_id') }}" placeholder="Enter Employee Id">
                                            <span class="text-danger employee_id_error"></span>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label for="inputEnterYourName" class="col-form-label"> Date Of Joining </label>
                                            <input type="date" name="date_of_joining" max="{{ date('Y-m-d') }}"
                                                class="form-control" value="{{ old('date_of_joining') }}">
                                            <span class="text-danger date_of_joining_error"></span>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label for="inputEnterYourName" class="col-form-label"> Email <span
                                                    style="color: red;">*</span></label>
                                            <input type="text" name="email" class="form-control"
                                                value="{{ old('email') }}" placeholder="Enter Account manager Email">
                                            <span class="text-danger email_error"></span>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label for="inputEnterYourName" class="col-form-label"> Phone <span
                                                    style="color: red;">*</span></label>
                                            <input type="text" name="phone" class="form-control"
                                                value="{{ old('phone') }}" placeholder="Enter Phone Number">
                                            <span class="text-danger phone_error"></span>
                                        </div>


                                        <div class="col-md-12 mb-3">
                                            <label for="inputEnterYourName" class="col-form-label"> Password
                                                <span style="color: red;">*</span></label>
                                            <input type="password" name="password" class="form-control"
                                                value="{{ old('password') }}" placeholder="Enter pasword">
                                            <span class="text-danger password_error"></span>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label for="inputEnterYourName" class="col-form-label"> Confirm
                                                Password <span style="color: red;">*</span></label>
                                            <input type="password" name="confirm_password" class="form-control"
                                                value="{{ old('confirm_password') }}">
                                            <span class="text-danger confirm_password_error"></span>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label for="inputEnterYourName" class="col-form-label"> Status
                                                <span style="color: red;">*</span></label>
                                            <select name="status" class="form-control">
                                                <option value="">Select a Status</option>
                                                <option value="1">Active</option>
                                                <option value="0">Inactive</option>
                                            </select>
                                            <span class="text-danger status_error"></span>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label for="inputEnterYourName" class="col-form-label"> Profile
                                                Picture </label>
                                            <input type="file" name="profile_picture" class="form-control"
                                                value="{{ old('profile_picture') }}">
                                            <span class="text-danger profile_picture_error"></span>
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
                                <h4 id="offcanvasEditLabel">Edit Account managers Details</h4>
                            </div>
                            <div class="offcanvas-body">
                                <form action="" method="POST" enctype="multipart/form-data"
                                    id="account-manager-edit-form">
                                    @method('PUT')
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label for="inputEnterYourName" class="col-form-label"> Name <span
                                                    style="color: red;">*</span></label>
                                            <input type="text" name="name" class="form-control" id="name"
                                                value="{{ old('name') }}" placeholder="Enter Account manager Name">
                                            <span class="text-danger name_msg_error"></span>

                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label for="inputEnterYourName" class="col-form-label"> Employee Id </label>
                                            <input type="text" name="employee_id" class="form-control"
                                                id="employee_id" value="{{ old('employee_id') }}"
                                                placeholder="Enter Employee Id">
                                            <span class="text-danger employee_id_msg_error"></span>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label for="inputEnterYourName" class="col-form-label"> Date Of Joining
                                            </label>
                                            <input type="date" name="date_of_joining" max="{{ date('Y-m-d') }}"
                                                class="form-control" id="date_of_joining"
                                                value="{{ old('date_of_joining') }}">
                                            <span class="text-danger date_of_joining_msg_error"></span>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label for="inputEnterYourName" class="col-form-label"> Email <span
                                                    style="color: red;">*</span></label>
                                            <input type="text" name="email" class="form-control" id="email"
                                                value="{{ old('email') }}" placeholder="Enter Account manager Email">
                                            <span class="text-danger email_msg_error"></span>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label for="inputEnterYourName" class="col-form-label"> Phone <span
                                                    style="color: red;">*</span></label>
                                            <input type="text" name="phone" class="form-control" id="phone"
                                                value="{{ old('phone') }}" placeholder="Enter Phone Number">
                                            <span class="text-danger phone_msg_error"></span>
                                        </div>


                                        <div class="col-md-12 mb-3">
                                            <label for="inputEnterYourName" class="col-form-label"> Password
                                                <span style="color: red;">*</span></label>
                                            <input type="password" name="password" class="form-control" id="password"
                                                value="{{ old('password') }}" placeholder="Enter pasword">
                                            <span class="text-danger password_msg_error"></span>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label for="inputEnterYourName" class="col-form-label"> Confirm
                                                Password <span style="color: red;">*</span></label>
                                            <input type="password" name="confirm_password" id="confirm_password"
                                                class="form-control" value="{{ old('confirm_password') }}">
                                            <span class="text-danger confirm_password_msg_error"></span>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label for="inputEnterYourName" class="col-form-label"> Status
                                                <span style="color: red;">*</span></label>
                                            <select name="status" class="form-control" id="status">
                                                <option value="">Select a Status</option>
                                                <option value="1">Active</option>
                                                <option value="0">Inactive</option>
                                            </select>
                                            <span class="text-danger status_msg_error"></span>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label for="inputEnterYourName" class="col-form-label"> Profile
                                                Picture </label>
                                            <input type="file" name="profile_picture" class="form-control"
                                                value="{{ old('profile_picture') }}">
                                            <span class="text-danger profile_picture_msg_error"></span>
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
                    <div class="table-responsive" id="account_managers_data">
                        @include('admin.account_manager.table')
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
                url = "{{ route('account_managers.search') }}"
                $('#loading').addClass('loading');
                $('#loading-content').addClass('loading-content');
                $.ajax({
                    url: url,
                    type: 'GET',
                    data: {
                        text: text,
                    },
                    success: function(response) {
                        $('#account_managers_data').html(response.view);
                        $('#loading').removeClass('loading');
                        $('#loading-content').removeClass('loading-content');
                    }
                });
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
                url: '{{ route('account_managers.change-status') }}',
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
            $('#account-manager-form-create').submit(function(e) {
                e.preventDefault();

                var formData = $(this).serialize();
                $('#loading').addClass('loading');
                $('#loading-content').addClass('loading-content');
                $.ajax({
                    url: $(this).attr('action'),
                    type: $(this).attr('method'),
                    data: formData,
                    success: function(response) {
                        //windows load with toastr message
                        $('#loading').removeClass('loading');
                        $('#loading-content').removeClass('loading-content');
                        window.location.reload();
                        toastr.success('Follow-Up details added successfully');
                    },
                    error: function(xhr) {
                        // Handle errors (e.g., display validation errors)
                        //clear any old errors
                        $('.text-danger').html('');
                        // Handle errors (e.g., display validation errors)
                        var errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            // Assuming you have a span with class "text-danger" next to each input
                            $('.' + key + '_error').html(value[0]);
                        });
                        $('#loading').removeClass('loading');
                        $('#loading-content').removeClass('loading-content');
                    }
                });
            });
        });
    </script>
      @if (count($account_managers) > 0)
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
                        var account_manager = response.account_manager;

                        $('#name').val(account_manager.name);
                        $('#employee_id').val(account_manager.employee_id);
                        $('#date_of_joining').val(account_manager.date_of_joining);
                        $('#email').val(account_manager.email);
                        $('#phone').val(account_manager.phone);
                        $('#status').val(account_manager.status);
                        var updateRoute =
                            "{{ route('account_managers.update', ['account_manager' => ':id']) }}";
                        updateRoute = updateRoute.replace(':id', account_manager.id);
                        $('#account-manager-edit-form').attr('action', updateRoute);
                        $('#loading').removeClass('loading');
                        $('#loading-content').removeClass('loading-content');
                        $('#offcanvasEdit').offcanvas('show');
                    },
                    error: function(xhr) {
                        // Handle errors
                        $('#loading').removeClass('loading');
                        $('#loading-content').removeClass('loading-content');
                        console.log(xhr);
                    }
                });
            });

            // Handle the form submission
            $('#account-manager-edit-form').submit(function(e) {
                e.preventDefault();

                var formData = $(this).serialize();

                $.ajax({
                    url: $(this).attr('action'),
                    type: $(this).attr('method'),
                    data: formData,
                    success: function(response) {
                        window.location.reload();
                        toastr.success('Account Manager details updated successfully');
                    },
                    error: function(xhr) {
                        // Handle errors (e.g., display validation errors)
                        var errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            // Assuming you have a span with class "text-danger" next to each input
                            $('.' + key + '_msg_error').html(value[0]);
                        });
                        $('#loading').removeClass('loading');
                        $('#loading-content').removeClass('loading-content');
                    }
                });
            });
        });


    </script>
      @endif
@endpush
