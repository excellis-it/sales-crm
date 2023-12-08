@extends('admin.layouts.master')
@section('title')
    All Customers Details - {{ env('APP_NAME') }}
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
                        <h3 class="page-title">Customers Information</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('customers.index') }}">Customers</a>
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
                                <h4 class="mb-0">Customers Details</h4>
                            </div>
                            <div class="col-md-6 text-end">
                                <a href="javascript:void(0);" class="btn px-5 submit-btn" data-bs-toggle="offcanvas"
                                data-bs-target="#offcanvasRight" aria-controls="offcanvasRight"><i
                                        class="fas fa-plus"></i> Add New
                                    customer</a>
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
                                <h4 id="offcanvasEditLabel">Add Customer Details</h4>
                            </div>
                            <div class="offcanvas-body">
                                <form action="{{ route('customers.store') }}" method="post"
                                    enctype="multipart/form-data" id="cutomer-form-create">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label for="inputEnterYourName" class="col-form-label"> Name <span
                                                    style="color: red;">*</span></label>
                                            <input type="text" name="name" class="form-control"
                                                value="{{ old('name') }}" placeholder="Enter Customer Name">
                                            <span class="text-danger name_error"></span>
                                        </div>
                                        
                                        <div class="col-md-12 mb-3">
                                            <label for="inputEnterYourName" class="col-form-label"> Email <span
                                                    style="color: red;">*</span></label>
                                            <input type="text" name="email" class="form-control"
                                                value="{{ old('email') }}" placeholder="Enter Customer Email">
                                            <span class="text-danger email_error"></span>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label for="inputEnterYourName" class="col-form-label"> Phone <span
                                                    style="color: red;">*</span></label>
                                            <input type="text" name="phone" class="form-control"
                                                value="{{ old('phone') }}" placeholder="Enter Phone">
                                            <span class="text-danger phone_error"></span>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label for="inputEnterYourName" class="col-form-label"> Address <span
                                                    style="color: red;">*</span></label>
                                            <textarea name="address" class="form-control"
                                                value="{{ old('address') }}" ></textarea>
                                            <span class="text-danger address_error"></span>
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
                                <h4 id="offcanvasEditLabel">Edit Customers Details</h4>
                            </div>
                            <div class="offcanvas-body">
                                <form action="" method="POST" enctype="multipart/form-data"
                                    id="customer-edit-form">
                                    @method('PUT')
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label for="inputEnterYourName" class="col-form-label"> Name <span
                                                    style="color: red;">*</span></label>
                                            <input type="text" name="name" class="form-control" id="name"
                                                value="{{ old('name') }}" placeholder="Enter Customer Name">
                                            <span class="text-danger name_msg_error"></span>

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
                        @include('admin.customer.table')
                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection

@push('scripts')
    
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
        $(document).ready(function() {
            //how to place holder in "jquery datatable" search box
            $('#myTable_filter input').attr("placeholder", "Search");
        });
    </script>
    
      @if (count($customers) > 0)
   
      @endif
@endpush
