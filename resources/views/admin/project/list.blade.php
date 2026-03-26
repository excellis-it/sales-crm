@extends('admin.layouts.master')
@section('title')
    All Project Details - {{ env('APP_NAME') }}
@endsection
@push('styles')
    <style>
        .dataTables_filter {
            margin-bottom: 20px !important;
        }

        .offcanvas {
            width: 45% !important;
            background-color: #f8f9fa;
            border-left: none;
            box-shadow: -5px 0 15px rgba(0, 0, 0, 0.1);
        }

        .offcanvas-header {
            background: #fff;
            border-bottom: 1px solid #eee;
            padding: 20px;
        }

        .offcanvas-body {
            padding: 25px;
        }

        .section-header {
            margin-top: 25px;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #e9ecef;
            color: #343a40;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
        }

        .section-header i {
            margin-right: 10px;
            color: #ff9b44;
        }

        .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 6px;
            font-size: 14px;
        }

        .form-control,
        .form-select {
            border-radius: 8px;
            border: 1px solid #ced4da;
            padding: 10px 15px;
            transition: all 0.2s;
            background-color: #fff;
        }

        .form-control:focus {
            border-color: #ff9b44;
            box-shadow: 0 0 0 0.2rem rgba(255, 155, 68, 0.25);
        }

        .card-custom {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
        }

        .milestone-item {
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 15px;
            border: 1px solid #e9ecef;
            position: relative;
            transition: all 0.3s;
        }

        .milestone-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .remove-btn {
            color: #dc3545;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 18px;
        }

        .remove-btn:hover {
            color: #a71d2a;
            transform: scale(1.1);
        }

        .submit-btn-modern {
            background: linear-gradient(135deg, #ff9b44 0%, #fc6075 100%);
            border: none;
            color: #fff;
            padding: 12px 30px;
            border-radius: 30px;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(255, 155, 68, 0.3);
            transition: all 0.3s;
        }

        .submit-btn-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 155, 68, 0.4);
            color: #fff;
        }

        .btn-process {
            background-color: #343a40;
            color: #fff;
            border-radius: 8px;
            padding: 8px 20px;
            transition: all 0.2s;
        }

        .btn-process:hover {
            background-color: #23272b;
            color: #fff;
        }

        .add-more-btn {
            color: #ff9b44;
            background: transparent;
            border: 2px dashed #ff9b44;
            border-radius: 8px;
            padding: 10px;
            width: 100%;
            font-weight: 600;
            margin-top: 10px;
            transition: all 0.2s;
        }

        .add-more-btn:hover {
            background: rgba(255, 155, 68, 0.05);
            transform: translateY(-1px);
        }

        .customer-type-group {
            display: flex;
            gap: 20px;
            background: #fff;
            padding: 15px;
            border-radius: 10px;
            border: 1px solid #e9ecef;
            margin-bottom: 20px;
        }

        .form-check-input:checked {
            background-color: #ff9b44;
            border-color: #ff9b44;
        }

        .prod-search {
            position: relative;
            display: flex;
            align-items: center;
        }

        .prod-search-icon {
            position: absolute;
            right: 15px;
            color: #ff9b44;
            transition: all 0.3s;
        }

        .prod-search-icon:hover {
            color: #fc6075;
        }

        .rounded_search {
            padding-right: 45px !important;
            border-radius: 8px !important;
        }

        @media (max-width: 991px) {
            .offcanvas {
                width: 80% !important;
            }
        }

        @media (max-width: 767px) {
            .offcanvas {
                width: 100% !important;
            }
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
                                <div class="d-flex align-items-center">
                                    <h4 class="mb-0">Projects List</h4>

                                </div>

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
                    <div class="row align-items-center">
                        <div class="col-md-9">
                            <div class="row g-2">
                                <div class="col-md-3">
                                    <label class="form-label mb-0">Start Date</label>
                                    <input type="date" name="start_date" id="start_date" class="form-control" value="{{ $startDate }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label mb-0">End Date</label>
                                    <input type="date" name="end_date" id="end_date" class="form-control" value="{{ $endDate }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label mb-0">Search</label>
                                    <div class="search-field prod-search">
                                        <input type="text" name="search" id="search" placeholder="search..." required value="{{ $search ?? '' }}"
                                            class="form-control rounded_search">
                                        <a href="javascript:void(0)" class="prod-search-icon submit_search"><i
                                                class="fa fa-search"></i></a>
                                    </div>
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <div class="w-100">
                                        <label class="form-label mb-0">&nbsp;</label>
                                        <button class="btn btn-secondary w-100" id="reset-filters"
                                            style="height: 45px; border-radius: 8px; margin-bottom:10px;">Reset</button>
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
                                <h4 id="offcanvasEditLabel" class="text-dark">Add Project Details</h4>
                            </div>
                            <div class="offcanvas-body">
                                <form action="{{ route('sales-projects.store') }}" method="post" id="form-validation"
                                    data-parsley-validate="" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="section-header"><i class="fas fa-user-circle"></i> Customer
                                                Information</div>
                                            <div class="customer-type-group mb-3">
                                                <div class="form-check">
                                                    <input class="form-check-input customer" type="radio" name="customer"
                                                        id="new_user" value="1" checked required>
                                                    <label class="form-check-label" for="new_user">New Customer</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input customer" type="radio" name="customer"
                                                        id="existing_user" value="0" required>
                                                    <label class="form-check-label" for="existing_user">Existing
                                                        Customer</label>
                                                </div>
                                            </div>

                                            <div id="select_user" class="mb-3"></div>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Client Name <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="client_name" id="client_name" required
                                                data-parsley-trigger="keyup" class="form-control" placeholder="John Doe">
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Client Email <span
                                                    class="text-danger">*</span></label>
                                            <input type="email" name="client_email" id="client_email" required
                                                data-parsley-trigger="keyup" class="form-control"
                                                placeholder="john@example.com">
                                            <span class="client_email_error text-danger"></span>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Client Phone <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="client_phone" id="client_phone" required
                                                data-parsley-trigger="keyup" data-parsley-type="number"
                                                class="form-control" placeholder="1234567890">
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Client Address <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="client_address" id="client_address" required
                                                data-parsley-trigger="keyup" class="form-control"
                                                placeholder="City, Country">
                                        </div>

                                        <div class="col-md-12 mb-3">
                                            <label class="form-label">Business Name <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="business_name" id="business_name" required
                                                data-parsley-trigger="keyup" class="form-control"
                                                placeholder="Business Solutions Inc.">
                                        </div>

                                        <div class="col-md-12">
                                            <div class="section-header"><i class="fas fa-briefcase"></i> Project Details
                                            </div>
                                        </div>

                                        <div class="col-md-12 mb-3">
                                            <label class="form-label">Project Type <span
                                                    class="text-danger">*</span></label>
                                            <select name="project_type[]" id="project_type" class="form-control mySelect"
                                                multiple="multiple" required>
                                                <option value="Website Design & Development">Website Design & Development
                                                </option>
                                                <option value="Mobile Application Development">Mobile Application
                                                    Development</option>
                                                <option value="Digital Marketing">Digital Marketing</option>
                                                <option value="Logo Design">Logo Design</option>
                                                <option value="SEO">SEO</option>
                                                <option value="SMO">SMO</option>
                                                <option value="Other">Other</option>
                                            </select>
                                        </div>

                                        <div id="other-value" class="col-md-12 mb-3"></div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Project Value (Total) <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="text" name="project_value" id="project_value" required
                                                    data-parsley-trigger="keyup" data-parsley-type="number"
                                                    class="form-control" placeholder="0.00">
                                            </div>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Project Upfront <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="project_upfront" id="project_upfront" required
                                                data-parsley-trigger="keyup" data-parsley-type="number"
                                                class="form-control" placeholder="0.00">
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Currency <span class="text-danger">*</span></label>
                                            <select name="currency" id="currency" class="form-select" required>
                                                <option value="">Select Currency</option>
                                                <option value="INR">INR</option>
                                                <option value="USD">USD</option>
                                                <option value="EUR">EUR</option>
                                                <option value="GBP">GBP</option>
                                                <option value="AUD">AUD</option>
                                                <option value="CAD">CAD</option>
                                            </select>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Payment Mode <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="payment_mode" required
                                                data-parsley-trigger="keyup" id="payment_mode" class="form-control"
                                                placeholder="e.g. Bank Transfer, Paypal">
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Project Opener <span
                                                    class="text-danger">*</span></label>
                                            <select name="project_opener" id="project_opener" required
                                                class="form-select select2">
                                                <option value="">Select Opener</option>
                                                @foreach ($project_openers as $project_opener)
                                                    <option value="{{ $project_opener->id }}">{{ $project_opener->name }}
                                                        ({{ $project_opener->email }})</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Project Closer</label>
                                            <select name="project_closer" id="project_closer"
                                                class="form-select select2">
                                                <option value="">Select Closer</option>
                                                @foreach ($users as $user)
                                                    <option value="{{ $user->id }}">{{ $user->name }}
                                                        ({{ $user->email }})</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Sale Date <span class="text-danger">*</span></label>
                                            <input type="date" name="sale_date" id="sale_date" required
                                                class="form-control picker">
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Delivery TAT</label>
                                            <input type="date" name="delivery_tat" id="delivery_tat"
                                                class="form-control picker">
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Website</label>
                                            <input type="text" name="website" id="website" data-parsley-type="url"
                                                class="form-control" placeholder="https://example.com">
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Assigned To <span
                                                    class="text-danger">*</span></label>
                                            <select name="assigned_to" id="assigned_to" required
                                                class="form-select select2">
                                                <option value="">Select User</option>
                                                @foreach ($account_managers as $account_manager)
                                                    <option value="{{ $account_manager->id }}">
                                                        {{ $account_manager->name }} ({{ $account_manager->email }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-12 mb-3">
                                            <label class="form-label">Project Description</label>
                                            <textarea name="project_description" id="project_description" rows="3" class="form-control"
                                                placeholder="Briefly describe the project scope..."></textarea>
                                        </div>

                                        <div class="col-md-12 mb-3">
                                            <label class="form-label">Internal Comment</label>
                                            <textarea name="comment" id="comment" rows="2" class="form-control" readonly
                                                placeholder="Additional internal details..."></textarea>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="section-header"><i class="fas fa-tasks"></i> Milestones</div>
                                            <div class="row align-items-end mb-4">
                                                <div class="col-md-8">
                                                    <label class="form-label">Number of Milestones</label>
                                                    <input type="number" id="number_of_milestone" min="0"
                                                        name="number_of_milestone" class="form-control" placeholder="0">
                                                </div>
                                                <div class="col-md-4">
                                                    <button type="button"
                                                        class="btn btn-process w-100 milestone-print">Process</button>
                                                </div>
                                            </div>

                                            <div id="milestone_field">
                                                <div class="add-milestone"></div>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="section-header"><i class="fas fa-file-pdf"></i> Documents</div>
                                            <div class="add-pdf">
                                                <div class="row milestone-item mx-0">
                                                    <div class="col-md-12 mb-3">
                                                        <label class="form-label">Upload PDF</label>
                                                        <input type="file" name="pdf[]" class="form-control"
                                                            accept="application/pdf">
                                                    </div>
                                                    <div class="col-md-12">
                                                        <button type="button" class="btn add-more-btn add-pdf-button"><i
                                                                class="fas fa-plus"></i> Add Another PDF</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-4 pt-4 border-top text-end">
                                        <button class="btn btn-light px-4 me-2" type="reset"
                                            data-bs-dismiss="offcanvas">Cancel</button>
                                        <button class="btn submit-btn-modern check-form" type="submit">Create
                                            Project</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div id="edit-project-model">
                            @include('admin.project.edit')
                        </div>
                        {{-- Upsale offcanvas loaded via AJAX --}}
                        <div id="upsale-panel-container"></div>
                        {{-- Edit upsale offcanvas --}}
                        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasUpsaleEdit" style="width:600px;">
                            <div class="offcanvas-header bg-light border-bottom">
                                <button type="button" class="text-reset cls_btn_left" data-bs-dismiss="offcanvas" aria-label="Close">
                                    <i class="fa fa-chevron-right" aria-hidden="true"></i>
                                </button>
                                <h4 class="text-dark mb-0">Edit Upsale</h4>
                            </div>
                            <div class="offcanvas-body p-0" id="upsale-edit-body">
                                <div class="p-4 text-center text-muted">Loading...</div>
                            </div>
                        </div>

                    </div>
                    <div class="table-responsive">
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
                                    <th class="sorting" data-tippy-content="Sort by Project Value (Base + Upsale)"
                                        data-sorting_type="asc" data-column_name="project_value" style="cursor: pointer">
                                        Value (Base+Upsale) <span id="project_value_icon"><span
                                                class="fa fa-sort-down"></span></span></th>
                                    <th class="sorting" data-tippy-content="Sort by Project Upfront (Base + Upsale)"
                                        data-sorting_type="asc" data-column_name="project_upfront"
                                        style="cursor: pointer"> Total Upfront <span id="project_upfront_icon"><span
                                                class="fa fa-sort-down"></span></span>
                                    </th>
                                    {{-- <th> </th> --}}
                                    <th class="sorting" data-tippy-content="Sort by Currency" data-sorting_type="asc"
                                        data-column_name="currency" style="cursor: pointer"> Currency <span
                                            id="currency_icon"><span class="fa fa-sort-down"></span></span></th>
                                    <th data-tippy-content="Cant't sort by Payment Mode" style="cursor: pointer"> Payment
                                        Mode</th>
                                    <th data-tippy-content="Cant't sort by Paid Milestone" style="cursor: pointer"> Paid Milestone </th>
                                    <th data-tippy-content="Cant't sort by Due Amount" style="cursor: pointer"> Due Amount
                                    </th>
                                    <th> Status</th>
                                    <th> Action </th>
                                </tr>
                            </thead>
                            <tbody id="project-data">
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
    @include('bdm.includes.followup_modal')
    <script src="http://parsleyjs.org/dist/parsley.js"></script>
    <script>
        window.ParsleyConfig = {
            errorsWrapper: '<div></div>',
            errorTemplate: '<div class="alert alert-danger parsley" role="alert"></div>',
            errorClass: 'has-error',
            successClass: 'has-success'
        };

        $(document).ready(function() {
            // Initialize Select2
            $('.select2').each(function() {
                $(this).select2({
                    dropdownParent: $('#offcanvasRight')
                });
            });
            $('.mySelect').select2({
                dropdownParent: $('#offcanvasRight')
            });

            // Handle Customer Type Toggle
            $(document).on('change', '.customer', function() {
                var customer = $(this).val();
                if (customer == 0) {
                    $('#loading').addClass('loading');
                    $.ajax({
                        url: "{{ route('sales-projects.new-customer') }}",
                        type: 'GET',
                        success: function(response) {
                            var html =
                                '<label class="form-label mt-2">Select Customer <span class="text-danger">*</span></label>';
                            html +=
                                '<select name="customer_id" id="customer_id" required class="form-select select2-customer">';
                            html += '<option value="">Select a customer</option>';
                            $.each(response, function(key, value) {
                                html += '<option value="' + value.id + '">' + value
                                    .customer_name + ' (' + value.customer_email +
                                    ')</option>';
                            });
                            html += '</select>';
                            $('#select_user').html(html);
                            $('.select2-customer').select2();
                            $('#loading').removeClass('loading');
                        }
                    });
                } else {
                    $('#select_user').html('');
                    $('.form-control').prop('readonly', false);
                    $('#client_name, #client_email, #client_phone, #client_address').val('');
                }
            });

            // Auto-fill Customer Details
            $(document).on('change', '#customer_id', function() {
                var customer_id = $(this).val();
                if (!customer_id) return;
                $.ajax({
                    url: "{{ route('sales-projects.customer-details') }}",
                    type: 'GET',
                    data: {
                        customer_id: customer_id
                    },
                    success: function(response) {
                        $('#client_name').val(response.customer_name).prop('readonly', true);
                        $('#client_email').val(response.customer_email).prop('readonly', true);
                        $('#client_phone').val(response.customer_phone).prop('readonly', true);
                        $('#client_address').val(response.customer_address).prop('readonly',
                            true);
                        // $('#business_name').val(response.business_name).prop('readonly', true);
                    }
                });
            });

            // Project Type "Other" Toggle
            $('#project_type').on('change', function() {
                if ($(this).val().includes('Other')) {
                    $('#other-value').html(
                        '<label class="form-label">Other Project Type Value <span class="text-danger">*</span></label><input type="text" name="other_value" required class="form-control" placeholder="Specify type">'
                        );
                } else {
                    $('#other-value').html('');
                }
            });

            // Milestone Generation
            $('.milestone-print').on('click', function() {
                var count = $('#number_of_milestone').val();
                if (!count || count < 0) return;

                $('.add-milestone').html('');
                for (let i = 1; i <= count; i++) {
                    var html = `
                        <div class="milestone-item">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0 text-primary">Milestone #${i}</h6>
                                <i class="fas fa-times-circle remove-btn remove"></i>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Milestone Name <span class="text-danger">*</span></label>
                                    <input type="text" name="milestone_name[]" class="form-control" placeholder="Initial Payment" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Value <span class="text-danger">*</span></label>
                                    <input type="number" name="milestone_value[]" class="form-control" placeholder="0.00" required>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Payment Status <span class="text-danger">*</span></label>
                                    <select name="payment_status[]" class="form-select payment-status-toggle" data-id="${i}" required>
                                        <option value="Due">Due</option>
                                        <option value="Paid">Paid</option>
                                    </select>
                                </div>
                                <div class="payment-details-${i}" style="display:none;">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Payment Date</label>
                                            <input type="date" name="milestone_payment_date[]" class="form-control">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Payment Mode</label>
                                            <select name="milestone_payment_mode[]" class="form-select">
                                                <option value="">Select Mode</option>
                                                <option value="Paypal">Paypal</option>
                                                <option value="Stripe">Stripe</option>
                                               <option value="Bank Transfer">Bank Transfer</option>
                                                            <option value="Payoneer">Payoneer</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Comment</label>
                                    <textarea name="milestone_comment[]" class="form-control" rows="2"></textarea>
                                </div>
                            </div>
                        </div>`;
                    $('.add-milestone').append(html);
                }
            });

            // Toggle Payment Details within Milestone
            $(document).on('change', '.payment-status-toggle', function() {
                var id = $(this).data('id');
                var $details = $('.payment-details-' + id);
                if ($(this).val() === 'Paid') {
                    $details.show().find('input, select').prop('required', true);
                } else {
                    $details.hide().find('input, select').prop('required', false);
                }
            });

            // Remove Milestone/PDF
            $(document).on('click', '.remove', function() {
                $(this).closest('.milestone-item, .row').fadeOut(300, function() {
                    $(this).remove();
                });
            });

            // PDF Add More
            $('.add-pdf-button').click(function() {
                var html = `
                    <div class="row milestone-item mx-0 mt-3">
                        <div class="col-md-10 mb-2">
                            <input type="file" name="pdf[]" class="form-control" accept="application/pdf">
                        </div>
                        <div class="col-md-2 text-end">
                            <i class="fas fa-trash-alt remove-btn remove"></i>
                        </div>
                    </div>`;
                $('.add-pdf').append(html);
            });

            // Form Submission with Email Validation
            $(document).on('submit', '#form-validation', function(e) {
                var $form = $(this);
                if ($form.data('valid')) return true;
                e.preventDefault();

                $.ajax({
                    url: "{{ route('email-validation') }}",
                    type: 'GET',
                    data: {
                        client_email: $('#client_email').val(),
                        customer_id: $('#customer_id').val() || 0
                    },
                    success: function(response) {
                        if (response.status === false) {
                            $('.client_email_error').html(response.message);
                        } else {
                            $('.client_email_error').html('');
                            $form.data('valid', true).submit();
                        }
                    }
                });
            });

            // Edit Modal Loading
            $(document).on('click', '.edit-route', function() {
                var route = $(this).data('route');
                $('#loading').addClass('loading');
                $.ajax({
                    url: route,
                    type: 'GET',
                    success: function(response) {
                        $('#edit-project-model').html(response.view);
                        $('#loading').removeClass('loading');
                        var offcanvas = new bootstrap.Offcanvas(document.getElementById(
                            'offcanvasEdit'));
                        offcanvas.show();
                        $('#edit-form-validation').parsley();
                    }
                });
            });

            // Delete Project
            $(document).on('click', '.delete', function(e) {
                var route = $(this).data('route');
                swal({
                    title: "Are you sure?",
                    text: "You want to delete this project?",
                    type: "warning",
                    confirmButtonText: "Yes, delete it!",
                    showCancelButton: true
                }).then((result) => {
                    if (result.value) {
                        window.location.href = route;
                    } else if (result.dismiss === 'cancel') {
                        swal(
                            'Cancelled',
                            'Your project is safe :)',
                            'error'
                        )
                    }
                });
            });

            // AJAX Searching, Sorting and Pagination
            function fetch_data(page, sort_type, sort_by, query) {
                $('#loading').addClass('loading');
                var start_date = $('#start_date').val();
                var end_date = $('#end_date').val();

                $.ajax({
                    url: "{{ route('sales-projects.fetch-data') }}?page=" + page + "&sortby=" + sort_by +
                        "&sorttype=" + sort_type + "&query=" + query + "&start_date=" + start_date +
                        "&end_date=" + end_date,
                    success: function(data) {
                        $('#project-data').html(data.data);
                        $('#loading').removeClass('loading');
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

            $(document).on('change', '#start_date, #end_date', function() {
                var query = $('#search').val();
                var column_name = $('#hidden_column_name').val();
                var sort_type = $('#hidden_sort_type').val();
                var page = 1; // Reset to page 1 on filter change
                $('#hidden_page').val(page);
                fetch_data(page, sort_type, column_name, query);
            });

            $(document).on('click', '#reset-filters', function() {
                $('#start_date').val('');
                $('#end_date').val('');
                $('#search').val('');
                fetch_data(1, 'desc', 'sale_date', '');
            });

            $(document).on('click', '.sorting', function() {
                var column_name = $(this).data('column_name');
                var order_type = $(this).data('sorting_type');
                var reverse_order = '';
                if (order_type == 'asc') {
                    $(this).data('sorting_type', 'desc');
                    reverse_order = 'desc';
                    $('.sorting').find('.fa').removeClass('fa-sort-up fa-sort-down').addClass('fa-sort');
                    $(this).find('.fa').removeClass('fa-sort').addClass('fa-sort-down');
                } else {
                    $(this).data('sorting_type', 'asc');
                    reverse_order = 'asc';
                    $('.sorting').find('.fa').removeClass('fa-sort-up fa-sort-down').addClass('fa-sort');
                    $(this).find('.fa').removeClass('fa-sort').addClass('fa-sort-up');
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

            // Open upsale panel for a project
            $(document).on('click', '.btn-open-upsale', function (e) {
                e.stopPropagation();
                var projectId = $(this).data('project-id');
                $('#loading').addClass('loading');
                $.ajax({
                    url: '{{ url("admin/sales-projects") }}/' + projectId + '/upsale',
                    type: 'GET',
                    success: function (res) {
                        $('#upsale-panel-container').html(res.view);
                        $('#loading').removeClass('loading');
                        var offcanvasEl = document.getElementById('offcanvasUpsale');
                        if (offcanvasEl) {
                            var bsOffcanvas = new bootstrap.Offcanvas(offcanvasEl);
                            bsOffcanvas.show();
                            // Init select2 inside offcanvas
                            if (typeof $.fn.select2 !== 'undefined') {
                                $('#upsale_project_type').select2({ dropdownParent: $('#offcanvasUpsale') });
                            }
                        }
                    },
                    error: function () {
                        $('#loading').removeClass('loading');
                    }
                });
            });

            // Edit upsale - load into separate edit offcanvas
            $(document).on('click', '.btn-edit-upsale', function (e) {
                e.stopPropagation();
                var upsaleId = $(this).data('id');
                // Close the upsale panel first
                var panelEl = document.getElementById('offcanvasUpsale');
                if (panelEl) {
                    var panelInstance = bootstrap.Offcanvas.getInstance(panelEl);
                    if (panelInstance) panelInstance.hide();
                }
                $('#loading').addClass('loading');
                $.ajax({
                    url: '{{ url("admin/upsale") }}/' + upsaleId + '/edit',
                    type: 'GET',
                    success: function (res) {
                        $('#upsale-edit-body').html(res.view);
                        $('#loading').removeClass('loading');
                        var editEl = document.getElementById('offcanvasUpsaleEdit');
                        var bsEdit = new bootstrap.Offcanvas(editEl);
                        bsEdit.show();
                        if (typeof $.fn.select2 !== 'undefined') {
                            $('#upsale_project_type_edit').select2({ dropdownParent: $('#offcanvasUpsaleEdit') });
                        }
                    },
                    error: function () {
                        $('#loading').removeClass('loading');
                    }
                });
            });
        });
    </script>
@endpush
