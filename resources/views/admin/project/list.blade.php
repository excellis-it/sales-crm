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
                                <a href="javascrip:void(0);" class="btn px-5 submit-btn" data-bs-toggle="offcanvas"
                                    data-bs-target="#offcanvasRight" aria-controls="offcanvasRight"><i
                                        class="fa fa-plus"></i> Add a
                                    Project</a>
                            </div>
                        </div>
                    </div>

                    <hr />
                    <div class="row justify-content-end">
                        <div class="col-md-6">
                            <div class="row g-1 justify-content-end">
                                <div class="col-md-8 pr-0">
                                    <div class="search-field prod-search">
                                        <input type="text" name="search" id="search" placeholder="search..." required
                                            class="form-control rounded_search">
                                        <a href="javascript:void(0)" class="prod-search-icon submit_search"><i
                                                class="fa fa-search"></i></a>
                                    </div>
                                </div>
                                {{-- <div class="col-md-3 pl-0 ml-2">
                                    <button class="btn btn-primary button-search" id="search-button"> <span class=""><i
                                                class="ph ph-magnifying-glass"></i></span> Search</button>
                                </div> --}}
                            </div>
                        </div>
                        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight"
                            aria-labelledby="offcanvasRightLabel">
                            <div class="offcanvas-header">
                                <button type="button" class="text-reset cls_btn_left" data-bs-dismiss="offcanvas"
                                    aria-label="Close">
                                    <i class="fa fa-chevron-right" aria-hidden="true"></i>
                                </button>
                                <h4 id="offcanvasEditLabel">Add Project Details</h4>
                            </div>
                            <div class="offcanvas-body">
                                <form action="{{ route('sales-projects.store') }}" method="post" id="form-validation"
                                    data-parsley-validate="" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        {{-- new user and existing user radio button --}}
                                        <div class="col-md-12 mb-3">
                                            <label for="inputEnterYourName" class="col-form-label">Type of customer
                                                <span style="color: red;">*</span></label>
                                            <input type="radio" name="customer" id="new_user" value="1" required
                                                class="customer" data-parsley-trigger="keyup" checked> New user
                                            <input type="radio" name="customer" id="existing_user" value="0" required
                                                class="customer" data-parsley-trigger="keyup"> Existing user
                                        </div>
                                        {{-- select user --}}
                                        <div class="col-md-12 mb-3 select_user" id="select_user">

                                        </div>
                                        {{-- salemangers select option --}}
                                        <div class="col-md-6 mb-3">
                                            <label for="inputEnterYourName" class="col-form-label"> Client Name
                                                <span style="color: red;">*</span></label>
                                            <input type="text" name="client_name" id="client_name" required
                                                data-parsley-trigger="keyup" class="form-control client_name"
                                                value="{{ old('client_name') }}" placeholder="Enter Client Name">
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="inputEnterYourName" class="col-form-label">Client Email
                                                <span style="color: red;">*</span></label>
                                            <input type="text" name="client_email" id="client_email"
                                                class="form-control client_email" value="{{ old('client_email') }}"
                                                placeholder="Enter Client Email">
                                            <span class="client_email_error text-danger"></span>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="inputEnterYourName" class="col-form-label">Client Phone
                                                <span style="color: red;">*</span></label>
                                            <input type="text" name="client_phone" id="client_phone" required
                                                data-parsley-trigger="keyup" data-parsley-type="number"
                                                data-parsley-type-message="Please enter a valid phone number."
                                                class="form-control client_phone" value="{{ old('client_phone') }}"
                                                placeholder="Enter Client Phone Number">
                                        </div>

                                        {{-- clinent address --}}
                                        <div class="col-md-6 mb-3">
                                            <label for="inputEnterYourName" class="col-form-label">Client
                                                Address <span style="color: red;">*</span></label>
                                            <input type="text" name="client_address" id="client_address" required
                                                data-parsley-trigger="keyup" class="form-control client_address"
                                                value="{{ old('client_address') }}" placeholder="Enter Address">
                                            @if ($errors->has('address'))
                                                <div class="error" style="color:red;">
                                                    {{ $errors->first('address') }}</div>
                                            @endif
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label for="inputEnterYourName" class="col-form-label">Business Name
                                                <span style="color: red;">*</span></label>
                                            <input type="text" name="business_name" id="business_name" required
                                                data-parsley-trigger="keyup" class="form-control"
                                                value="{{ old('business_name') }}" placeholder="Enter Business Name">
                                        </div>
                                        <h3 class="mt-4 text-uppercase">Project Details</h3>
                                        <hr>
                                        {{-- project type in select2 box --}}
                                        <div class="col-md-12 mb-3">
                                            <label for="inputEnterYourName" class="col-form-label">Project
                                                Type <span style="color: red;">*</span></label>
                                            <select name="project_type" id="project_type" class="form-control">
                                                <option value="">Select Project Type</option>
                                                <option value="Website Design & Development">Website Design &
                                                    Development</option>
                                                <option value="Mobile Application Development">Mobile
                                                    Application Development</option>
                                                <option value="Digital Marketing">Digital Marketing</option>
                                                <option value="Logo Design">Logo Design</option>
                                                <option value="SEO">SEO</option>
                                                <option value="SMO">SMO</option>
                                                <option value="Other">Other</option>
                                            </select>
                                        </div>
                                        <div id="other-value" class="col-md-12 mb-3">

                                        </div>
                                        {{-- Project value --}}
                                        <div class="col-md-6 mb-3">
                                            <label for="inputEnterYourName" class="col-form-label">Project
                                                Value <span style="color: red;">*</span></label>
                                            <input type="text" name="project_value" id="project_value" required
                                                data-parsley-trigger="keyup" data-parsley-type="number"
                                                data-parsley-type-message="Please enter a valid number."
                                                class="form-control" placeholder="Enter Project Value">
                                        </div>
                                        {{-- Project project_upfront --}}
                                        <div class="col-md-6 mb-3">
                                            <label for="inputEnterYourName" class="col-form-label">Project
                                                Upfront <span style="color: red;">*</span></label>
                                            <input type="text" name="project_upfront" id="project_upfront" required
                                                data-parsley-trigger="keyup" data-parsley-type="number"
                                                data-parsley-type-message="Please enter a valid number."
                                                class="form-control" value="{{ old('project_upfront') }}"
                                                placeholder="Enter Project Upfront">
                                        </div>
                                        {{-- currency select box --}}
                                        <div class="col-md-6 mb-3">
                                            <label for="inputEnterYourName" class="col-form-label">Currency
                                                <span style="color: red;">*</span></label>
                                            <select name="currency" id="currency" class="form-control" required
                                                data-parsley-trigger="keyup">
                                                <option value="">Select Currency</option>
                                                <option value="INR">INR</option>
                                                <option value="USD">USD</option>
                                                <option value="EUR">EUR</option>
                                                <option value="GBP">GBP</option>
                                                <option value="AUD">AUD</option>
                                                <option value="CAD">CAD</option>
                                            </select>
                                        </div>

                                        {{-- Project payment_mode --}}
                                        <div class="col-md-6 mb-3">
                                            <label for="inputEnterYourName" class="col-form-label">Project
                                                Payment Mode <span style="color: red;">*</span></label>
                                            <input type="text" name="payment_mode" required
                                                data-parsley-trigger="keyup" id="payment_mode" class="form-control"
                                                value="{{ old('payment_mode') }}"
                                                placeholder="Enter Project Payment Mode">
                                        </div>
                                        {{-- Project opener --}}
                                        <div class="col-md-6 mb-3">
                                            <label for="inputEnterYourName" class="col-form-label">Project
                                                Opener <span style="color: red;">*</span></label>
                                            <select name="project_opener" id="project_opener" required
                                                class="form-control select2" required>
                                                <option value="">Select Project
                                                    Opener
                                                </option>
                                                @foreach ($project_openers as $project_opener)
                                                    <option value="{{ $project_opener->id }}">{{ $project_opener->name }}
                                                        ({{ $project_opener->email }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        {{-- Project closer --}}
                                        <div class="col-md-6 mb-3">
                                            <label for="inputEnterYourName" class="col-form-label">Project
                                                Closer <span style="color: red;">*</span></label>
                                            <select name="project_closer" id="project_closer" required
                                                class="form-control select2" required>
                                                <option value="">Select Project
                                                    Closer
                                                </option>
                                                @foreach ($users as $user)
                                                    <option value="{{ $user->id }}">{{ $user->name }}
                                                        ({{ $user->email }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        {{-- sale date --}}
                                        <div class="col-md-6 mb-3">
                                            <label for="inputEnterYourName" class="col-form-label">Sale Date
                                                <span style="color: red;">*</span></label>
                                            <input type="date" name="sale_date" id="sale_date" required
                                                data-parsley-trigger="keyup" max="{{ date('Y-m-d') }}"
                                                class="form-control" value="{{ old('sale_date') }}"
                                                placeholder="Enter Sale Date">
                                        </div>
                                        {{-- website --}}
                                        <div class="col-md-6 mb-3">
                                            <label for="inputEnterYourName" class="col-form-label">Website</label>
                                            <input type="text" name="website" id="website"
                                                data-parsley-required="false" data-parsley-trigger="keyup"
                                                data-parsley-type="url"
                                                data-parsley-type-message="Please enter a valid url." class="form-control"
                                                value="{{ old('website') }}" placeholder="Enter Website">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="inputEnterYourName" class="col-form-label">Delivery
                                                TAT
                                                <span style="color: red;">*</span></label>
                                            <input type="date" name="delivery_tat" id="delivery_tat" required
                                                data-parsley-trigger="keyup" min="{{ date('Y-m-d') }}"
                                                class="form-control" value="{{ old('delivery_tat') }}"
                                                placeholder="Enter Sale Date">
                                        </div>

                                        <div class="col-md-12 mb-3">
                                            <label for="inputEnterYourName" class="col-form-label">Assigned To <span
                                                    style="color: red;">*</span></label>
                                            <select name="assigned_to" id="assigned_to " required
                                                class="form-control select2" required>
                                                <option value="">Select user
                                                </option>
                                                @foreach ($account_managers as $account_manager)
                                                    <option value="{{ $account_manager->id }}">
                                                        {{ $account_manager->name }}
                                                        ({{ $account_manager->email }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        {{-- comment --}}
                                        <div class="col-md-12 mb-3">
                                            <label for="inputEnterYourName" class="col-form-label">Comment</label>
                                            <textarea name="comment" id="comment" data-parsley-trigger="keyup" class="form-control"
                                                placeholder="Enter Comment">{{ old('comment') }}</textarea>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label for="inputEnterYourName" class="col-form-label">No. of
                                                Milestone</label>
                                            <input type="number" id="number_of_milestone" required
                                                name="number_of_milestone" class="form-control">
                                        </div>
                                        <div class="col-md-12 mb-3" style="margin-top:40px;">
                                            <button type="button"
                                                class="btn px-5 submit-btn milestone-print">Process</button>
                                        </div>
                                        <div id="milestone_field">
                                            <h3 class="mt-4 text-uppercase">Milestone</h3>
                                            <hr>
                                            {{-- add more functionality for milestone --}}
                                            <div class="add-milestone">
                                            </div>
                                        </div>


                                        <h3 class="mt-4 text-uppercase">Upload PDF</h3>
                                        <hr>
                                        <div class="add-pdf">

                                            <div class="row">
                                                <div class="col-md-12 mb-3">
                                                    <div style="display: flex">
                                                        <input type="file" name="pdf[]" class="form-control"
                                                            value="" data-parsley-required="false"
                                                            data-parsley-trigger="keyup" accept="application/pdf"
                                                            id="">
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-3">
                                                    <button type="button"
                                                        class="btn px-5 submit-btn add-pdf-button good-button"><i
                                                            class="fas fa-plus"></i> Add PDF</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex alin-items-center w-100 text-end">
                                        <button class="print_btn cancel_btn me-3" type="reset"><i
                                                class="far fa-times-circle"></i>
                                            Cancel</button>
                                        <button class="print_btn check-form" type="submit"><i
                                                class="far fa-check-circle"></i>
                                            Create</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div id="edit-project-model">
                            @include('admin.project.edit')
                        </div>

                    </div>
                    <div class="table-responsive" id="project-data">
                        <table class="dd table table-striped  table-hover" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="sorting" data-tippy-content="Sort by Sale Date" data-sorting_type="desc"
                                        data-column_name="sale_date" style="cursor: pointer">Sale Date <span
                                            id="date_icon"><span class="fa fa-sort-down"></span></span></th>
                                    <th data-tippy-content="Cant't sort by Sale By" style="cursor: pointer"> Sale By</th>
                                    <th class="sorting" data-tippy-content="Sort by Project Name" data-sorting_type="asc"
                                        data-column_name="business_name" style="cursor: pointer"> Project Name <span
                                            id="project_name_icon"><span class="fa fa-sort-down"></span> </span></th>
                                    <th class="sorting" data-tippy-content="Sort by Client Name" data-sorting_type="asc"
                                        data-column_name="client_name" style="cursor: pointer"> Client Name <span
                                            id="client_name_icon"><span class="fa fa-sort-down"></span></span></th>
                                    <th class="sorting" data-tippy-content="Sort by Phone" data-sorting_type="asc"
                                        data-column_name="client_phone" style="cursor: pointer"> Phone <span
                                            id="phone_icon"><span class="fa fa-sort-down"></span></span></th>
                                    <th class="sorting" data-tippy-content="Sort by Project Value"
                                        data-sorting_type="asc" data-column_name="project_value" style="cursor: pointer">
                                        Project Value <span id="project_value_icon"><span
                                                class="fa fa-sort-down"></span></span></th>
                                    <th class="sorting" data-tippy-content="Sort by Project Upfront"
                                        data-sorting_type="asc" data-column_name="project_upfront"
                                        style="cursor: pointer"> Project Upfront <span id="project_upfront_icon"><span
                                                class="fa fa-sort-down"></span></span>
                                    </th>
                                    {{-- <th> </th> --}}
                                    <th class="sorting" data-tippy-content="Sort by Currency" data-sorting_type="asc"
                                        data-column_name="currency" style="cursor: pointer"> Currency <span
                                            id="currency_icon"><span class="fa fa-sort-down"></span></span></th>
                                    <th data-tippy-content="Cant't sort by Payment Mode" style="cursor: pointer"> Payment
                                        Mode</th>
                                    <th data-tippy-content="Cant't sort by Due Amount" style="cursor: pointer"> Due Amount
                                    </th>
                                    <th> Status</th>
                                    <th> Action </th>
                                </tr>
                            </thead>
                            <tbody>
                                @include('admin.project.table')

                            </tbody>
                        </table>
                        <input type="hidden" name="hidden_page" id="hidden_page" value="1" />
                        <input type="hidden" name="hidden_column_name" id="hidden_column_name" value="id" />
                        <input type="hidden" name="hidden_sort_type" id="hidden_sort_type" value="desc" />
                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection

@push('scripts')
    <script>
        $(document).on('click', '.view-route', function() {
            window.location.href = $(this).data('route');
        });
    </script>
    {{-- trippy cdn link --}}
    <script src="https://unpkg.com/popper.js@1"></script>
    <script src="https://unpkg.com/tippy.js@5"></script>
    {{-- trippy --}}
    <script>
        tippy('[data-tippy-content]', {
            allowHTML: true,
            placement: 'bottom',
            theme: 'light-theme',
        });
    </script>
    <script>
        $(document).ready(function() {

            function clear_icon() {
                // $('#date_icon').html('');
                // $('#project_name_icon').html('');
                // $('#client_name_icon').html('');
                // $('#phone_icon').html('');
                // $('#project_value_icon').html('');
                // $('#project_upfront_icon').html('');
                // $('#currency_icon').html('');
            }

            function fetch_data(page, sort_type, sort_by, query) {
                $.ajax({
                    url: "{{ route('sales-projects.fetch-data') }}",
                    data: {
                        page: page,
                        sortby: sort_by,
                        sorttype: sort_type,
                        query: query
                    },
                    success: function(data) {
                        $('tbody').html(data.data);
                    }
                });
            }

            $(document).on('keyup', '#search', function() {
                var query = $('#search').val();
                var column_name = $('#hidden_column_name').val();
                var sort_type = $('#hidden_sort_type').val();
                var page = $('#hidden_page').val();
                fetch_data(page, sort_type, column_name, query);
            });

            $(document).on('click', '.sorting', function() {
                var column_name = $(this).data('column_name');
                var order_type = $(this).data('sorting_type');
                var reverse_order = '';
                if (order_type == 'asc') {
                    $(this).data('sorting_type', 'desc');
                    reverse_order = 'desc';
                    clear_icon();
                    $('#' + column_name + '_icon').html(
                        '<span class="fa fa-sort-down"></span>');
                }
                if (order_type == 'desc') {
                    $(this).data('sorting_type', 'asc');
                    reverse_order = 'asc';
                    clear_icon();
                    $('#' + column_name + '_icon').html(
                        '<span class="fa fa-sort-up"></span>');
                }
                $('#hidden_column_name').val(column_name);
                $('#hidden_sort_type').val(reverse_order);
                var page = $('#hidden_page').val();
                var query = $('#search').val();
                fetch_data(page, reverse_order, column_name, query);
            });

            $(document).on('click', '.pagination a', function(event) {
                event.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                $('#hidden_page').val(page);
                var column_name = $('#hidden_column_name').val();
                var sort_type = $('#hidden_sort_type').val();

                var query = $('#search').val();

                $('li').removeClass('active');
                $(this).parent().addClass('active');
                fetch_data(page, sort_type, column_name, query);
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
    <script src="http://parsleyjs.org/dist/parsley.js"></script>
    <!-- PARSLEY -->
    <script>
        window.ParsleyConfig = {
            errorsWrapper: '<div></div>',
            errorTemplate: '<div class="alert alert-danger parsley" role="alert"></div>',
            errorClass: 'has-error',
            successClass: 'has-success'
        };
    </script>

    <script>
        // add more functionality for milestone
        $(document).ready(function() {
            $('.add').click(function() {
                var html = '';
                html += '<div class="row">';
                html += '<div class="col-md-12 mb-3">';
                html += '<div style="display: flex">';
                html +=
                    '<input type="text" name="milestone_name[]" class="form-control" value="" placeholder="Milestone name" id="" required data-parsley-trigger="keyup">';
                html += '</div>';
                html += '</div>';
                html += '<div class="col-md-12 mb-3">';
                html += '<div style="display: flex">';
                html +=
                    '<input type="text" name="milestone_value[]" class="form-control" value="" placeholder="Milestone value" id="" required data-parsley-trigger="keyup" data-parsley-type="number" data-parsley-type-message="Please enter a valid number.">';
                html += '</div>';
                html += '</div>';
                html += '<div class="col-md-12 mb-3">';
                html += '<div style="display: flex">';
                html +=
                    '<select name="payment_status[]" id="payment_status" class="form-control" required data-parsley-trigger="keyup"><option value=""  >Select Payment Status</option><option value="Paid">Paid</option><option value="Due" selected>Due</option></select>';
                html += '</div>';
                html += '</div>';
                // html += '<div class="col-md-12 mb-3">';
                // html += '<div style="display: flex">';
                // html +=
                //     '<input type="date" name="payment_date[]" class="form-control" value="" id="" required data-parsley-trigger="keyup">';
                // html += '</div>';
                // html += '</div>';
                html += '<div class="col-md-12 mb-3">';
                html += '<div style="display: flex">';
                html +=
                    '<textarea name="milestone_comment[]" class="form-control" placeholder="Milestone Comment" id="" cols="3" rows="2" ></textarea>';
                html += '</div>';
                html += '</div>';
                html += '<div class="col-md-12 mb-3">';
                html +=
                    '<button type="button" class="btn btn-danger remove"><i class="fas fa-minus"></i> Remove</button>';
                html += '</div>';
                html += '</div>';

                $('.add-milestone').append(html);
            });
            $(document).on('click', '.remove', function() {
                $(this).closest('.row').remove();
                var number_of_milestone = $('#number_of_milestone').val();
                var new_number_of_milestone = number_of_milestone - 1;
                $('#number_of_milestone').val(new_number_of_milestone);
            });

            // when select2 other option in project type then show other value
            $('#project_type').on('change', function() {
                //    select 2 value get and seo,other value check
                var project_type = $(this).val();
                if (project_type.includes('Other')) {
                    var html = '';
                    html +=
                        '<label for="inputEnterYourName" class="col-form-label">Other Value <span style="color: red;">*</span></label>';
                    html +=
                        '<input type="text" name="other_value" id="other_value" class="form-control" value="{{ old('other_value') }}" placeholder="Enter Other Value" required data-parsley-trigger="keyup">';
                    $('#other-value').html(html);
                } else {
                    $('#other-value').html('');
                }
            });
        });
    </script>
    <script>
        $('.add-pdf-button').click(function() {
            var html = '';
            html += '<div class="row">';
            html += '<div class="col-md-12 mb-3">';
            html += '<div style="display: flex">';
            html +=
                '<input type="file" name="pdf[]" class="form-control" value="" id="" data-parsley-required="false" data-parsley-trigger="keyup" accept="application/pdf">';
            html += '</div>';
            html += '</div>';
            html += '<div class="col-md-12 mb-3">';
            html +=
                '<button type="button" class="btn btn-danger remove-pdf"><i class="fas fa-minus"></i> Remove</button>';
            html += '</div>';
            html += '</div>';
            $('.add-pdf').append(html);
        });
        $(document).on('click', '.remove-pdf', function() {
            $(this).closest('.row').remove();
        });
    </script>

    <script>
        //when payment_type milestone monthly field arenot required
        $('#payment_type').on('change', function() {
            var payment_type = $(this).val();
            if (payment_type.includes('Milestone')) {
                $('#milestone_field').show();
                $('#monthly_field').hide();
                $('#milestone_name').prop('required', true);
                $('#milestone_value').prop('required', true);
                $('#payment_status').prop('required', true);
                $('#payment_date').prop('required', true);
                //monthly field required false
                $('#start_date').prop('required', false);
                $('#end_date').prop('required', false);
            } else if (payment_type.includes('Monthly')) {
                $('#monthly_field').show();
                $('#milestone_field').hide();
                $('#start_date').prop('required', true);
                $('#end_date').prop('required', true);
                //milestone filed required false
                $('#milestone_name').prop('required', false);
                $('#milestone_value').prop('required', false);
                $('#payment_status').prop('required', false);
                $('#payment_date').prop('required', false);
            } else {
                $('#milestone_name').prop('required', false);
                $('#milestone_value').prop('required', false);
                $('#payment_status').prop('required', false);
                $('#payment_date').prop('required', false);
                $('#start_date').prop('required', false);
                $('#end_date').prop('required', false);
                $('#milestone_field').hide();
                $('#monthly_field').hide();
            }
        });
    </script>

    <script>
        $(document).ready(function() {
            $(document).ready(function() {
                $('.select2').select2();
            });
            $('.calculate_date').on('click', function() {

                $('#fetch_month').html('');
                //
                var startDate = new Date($('#start_date').val());
                var endDate = new Date($('#end_date').val());
                var project_value = $('#project_value').val();
                //validation start date and end date
                $('#end_date').next('span').remove();
                $('#start_date').next('span').remove();
                $('#project_value').next('span').remove();
                //if start_datewiil be blank
                if (startDate == 'Invalid Date') {
                    $('#start_date').after(
                        '<span class="error" style="color:red;">Start date is required</span>');
                    return false;
                }
                if (endDate == 'Invalid Date') {
                    $('#end_date').after(
                        '<span class="error" style="color:red;">Start date is required</span>');
                    return false;
                }
                if (startDate > endDate || startDate == endDate) {
                    $('#end_date').after(
                        '<span class="error" style="color:red;">End date must be greater than to start date</span>'
                    );
                    return false;
                }
                if (project_value == '') {
                    $('#project_value').after(
                        '<span class="error" style="color:red;">Project value is required</span>');
                    return false;
                }

                $('#loading').addClass('loading');
                $('#loading-content').addClass('loading-content');
                // count month between two dates
                var months = (endDate.getFullYear() - startDate.getFullYear()) * 12;
                months -= startDate.getMonth();
                months += endDate.getMonth();
                months = months <= 0 ? 0 : months;
                var total = months + 1;
                var new_project_value = project_value / total;
                console.log(project_value);
                console.log(new_project_value);
                //show amount in 2 decimal point
                var update_project_value = new_project_value.toFixed(2);
                console.log(total);

                for (let index = 1; index <= total; index++) {
                    console.log(total);
                    var html = '';
                    html += '<div class="row">';
                    html += '<div class="col-md-12 mb-3">';
                    html += '<div style="display: flex">';
                    html +=
                        '<input type="text" name="milestone_value[]" class="form-control" value="' +
                        update_project_value +
                        '" placeholder="Milestone value" id="" required data-parsley-trigger="keyup" data-parsley-type="number" data-parsley-type-message="Please enter a valid number.">';
                    html += '</div>';
                    html += '</div>';
                    html += '<div class="col-md-12 mb-3">';
                    html += '<div style="display: flex">';
                    html +=
                        '<select name="payment_status[]" id="payment_status" class="form-control" required data-parsley-trigger="keyup"><option value=""  >Select Payment Status</option><option value="Paid">Paid</option><option value="Due" selected>Due</option></select>';
                    html += '</div>';
                    html += '</div>';
                    // html += '<div class="col-md-12 mb-3">';
                    // html += '<div style="display: flex">';
                    // html +=
                    //     '<input type="date" name="payment_date[]" class="form-control"  id="" required data-parsley-trigger="keyup" >';
                    // html += '</div>';
                    // html += '</div>';
                    html += '<div class="col-md-12 mb-3">';
                    html += '<div style="display: flex">';
                    html +=
                        '<textarea name="milestone_comment[]" class="form-control" placeholder="Milestone Comment" id="" cols="3" rows="2" ></textarea>';
                    html += '</div>';
                    html += '</div>';
                    html += '<div class="col-md-12 mb-3">';
                    html +=
                        '<button type="button" class="btn btn-danger remove"><i class="fas fa-minus"></i> Remove</button>';
                    html += '</div>';
                    html += '</div>';

                    $('#fetch_month').append(html);
                }

                $('#loading').removeClass('loading');
                $('#loading-content').removeClass('loading-content');

            });
        });
    </script>

    <script>
        $('.milestone-print').on('click', function() {
            var number_of_milestone = $('#number_of_milestone').val();
            if (number_of_milestone == '') {
                $('#number_of_milestone').html('');
                console.log(number_of_milestone);
                $('#number_of_milestone').after(
                    '<span class="error" style="color:red;">Number of milestone is required</span>');
                return false;
            }
            $('.add-milestone').html('');
            // show milestone field as per number of milestone
            for (let index = 1; index <= number_of_milestone; index++) {
                console.log(number_of_milestone);
                var html = '';
                html += '<div class="row">';
                html += '<div class="col-md-12 mb-3">';
                html += '<div style="display: flex">';
                html +=
                    '<input type="text" name="milestone_name[]" class="form-control" value="" placeholder="Milestone name" id="" required data-parsley-trigger="keyup">';
                html += '</div>';
                html += '</div>';
                html += '<div class="col-md-12 mb-3">';
                html += '<div style="display: flex">';
                html +=
                    '<input type="text" name="milestone_value[]" class="form-control" value="" placeholder="Milestone value" id="" required data-parsley-trigger="keyup" data-parsley-type="number" data-parsley-type-message="Please enter a valid number.">';
                html += '</div>';
                html += '</div>';
                html += '<div class="col-md-12 mb-3">';
                html += '<div style="display: flex">';
                html +=
                    '<select name="payment_status[]" id="payment_status" class="form-control" required data-parsley-trigger="keyup"><option value=""  >Select Payment Status</option><option value="Paid">Paid</option><option value="Due" selected>Due</option></select>';
                html += '</div>';
                html += '</div>';
                // html += '<div class="col-md-12 mb-3">';
                // html += '<div style="display: flex">';
                // html +=
                //     '<input type="date" name="payment_date[]" class="form-control" value="" id="" required data-parsley-trigger="keyup">';
                // html += '</div>';
                // html += '</div>';
                html += '<div class="col-md-12 mb-3">';
                html += '<div style="display: flex">';
                html +=
                    '<textarea name="milestone_comment[]" class="form-control" placeholder="Milestone Comment" id="" cols="3" rows="2" ></textarea>';
                html += '</div>';
                html += '</div>';
                html += '<div class="col-md-12 mb-3">';
                html +=
                    '<button type="button" class="btn btn-danger remove"><i class="fas fa-minus"></i> Remove</button>';
                html += '</div>';
                html += '</div>';
                console.log(html);
                $('.add-milestone').append(html);
            }
        });
    </script>
    <script>
        $(document).ready(function() {
            // Handle the click event for the edit-route button
            $(document).on('click', '.edit-route', function() {
                var route = $(this).data('route');
                // Make an AJAX request to fetch the priceRequest details
                $('#loading').addClass('loading');
                $('#loading-content').addClass('loading-content');
                $.ajax({
                    url: route,
                    type: 'GET',
                    success: function(response) {
                        // console.log(response.view);
                        $('#edit-project-model').html(response.view);
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
        });
    </script>
    <script>
        $(document).ready(function() {
            // Handle the click event for the edit-route button
            $(document).on('change', '.customer', function() {
                var customer = $(this).val();
                if (customer == 0) {
                    $('#loading').addClass('loading');
                    $('#loading-content').addClass('loading-content');
                    $.ajax({
                        url: "{{ route('sales-projects.new-customer') }}",
                        type: 'GET',
                        success: function(response) {
                            // console.log(response.data);
                            $('.select_user').append(
                                ' <label for="inputEnterYourName" class="col-form-label"> Select customer <span style="color: red;">*</span></label> <select name="customer_id" id="customer_id" required data-parsley-trigger="keyup" class="form-control customer_id select2"> <option value="">Select a user</option>'
                            )
                            $.each(response, function(key, value) {
                                $('.customer_id').append('<option value="' + value.id +
                                    '">' + value.customer_name +
                                    '(' + value.customer_email + ')' + '</option>');
                            });
                            $('.select_user').append('</select>');
                            $('#loading').removeClass('loading');
                            $('#loading-content').removeClass('loading-content');
                        },
                        error: function(xhr) {
                            // Handle errors
                            $('#loading').removeClass('loading');
                            $('#loading-content').removeClass('loading-content');
                            console.log(xhr);
                        }
                    })
                } else {
                    $('.select_user').html('');
                    $('.client_email').removeAttr("style");
                    $('.client_phone').removeAttr("style");
                    $('.client_address').removeAttr("style");
                    $('.client_name').removeAttr("style");
                    $('.client_email').val('');
                    $('.client_phone').val('');
                    $('.client_address').val('');
                    $('.client_name').val('');
                }
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $(document).on('change', '#customer_id', function() {
                var customer_id = $(this).val();

                $.ajax({
                    url: "{{ route('sales-projects.customer-details') }}",
                    type: 'GET',
                    data: {
                        customer_id: customer_id
                    },
                    success: function(response) {
                        // console.log(response);
                        $('.client_email').attr("style", "pointer-events: none;");
                        $('.client_phone').attr("style", "pointer-events: none;");
                        $('.client_address').attr("style", "pointer-events: none;");
                        $('.client_name').attr("style", "pointer-events: none;");
                        $('.client_email').val(response.customer_email);
                        $('.client_phone').val(response.customer_phone);
                        $('.client_address').val(response.customer_address);
                        $('.client_name').val(response.customer_name);
                    },
                    error: function(xhr) {
                        // Handle errors
                        console.log(xhr);
                    }
                });
            });
        });
    </script>
    <script>
        // email validation
        $(document).ready(function() {
            var isFormSubmitted = false;

            $(document).on('submit', '#form-validation', function(e) {
                if (isFormSubmitted) {
                    // If the form is already submitted, do nothing
                    return;
                }

                e.preventDefault();

                var client_email = $('#client_email').val();
                var customer_id = $('#customer_id').val() || 0;
                // alert(customer_id)
                $.ajax({
                    url: "{{ route('email-validation') }}",
                    type: 'GET',
                    data: {
                        client_email: client_email,
                        customer_id: customer_id
                    },
                    success: function(response) {
                        if (response.status === false) {
                            $('.client_email_error').html(response.message);
                        } else {
                            // No validation errors, allow the form to submit
                            $('.client_email_error').html('');
                            isFormSubmitted = true;
                            $('#form-validation').submit();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            });
        });
    </script>

    <script>
        // email validation
        $(document).ready(function() {
            var isOkFormSubmitted = false;

            $(document).on('submit', '#edit-form-validation', function(e) {
                if (isOkFormSubmitted) {
                    // If the form is already submitted, do nothing
                    return;
                }

                e.preventDefault();

                var client_email = $('#edit_client_email').val();
                var customer_id = $('.edit_customer_id').val() || 0;
                // alert(customer_id)
                $.ajax({
                    url: "{{ route('email-validation') }}",
                    type: 'GET',
                    data: {
                        client_email: client_email,
                        customer_id: customer_id
                    },
                    success: function(response) {
                        if (response.status === false) {
                            $('.edit_client_email_error').html(response.message);
                        } else {
                            // No validation errors, allow the form to submit
                            $('.edit_client_email_error').html('');
                            isOkFormSubmitted = true;
                            $('#edit-form-validation').submit();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            });
        });
    </script>
@endpush
