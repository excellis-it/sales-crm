@extends('tender_user.layouts.master')
@section('title')
    {{ env('APP_NAME') }} | Edit Tender Project
@endsection

@push('styles')
    <style>
        .form-section-title {
            color: #ff9b44;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 20px;
            padding-bottom: 5px;
            border-bottom: 2px solid #eee;
            display: flex;
            align-items: center;
        }

        .form-section-title i {
            margin-right: 10px;
        }

        .card {
            border-radius: 12px;
            border: none;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }

        .btn-submit {
            background: linear-gradient(135deg, #ff9b44 0%, #fc6075 100%);
            color: #fff;
            padding: 10px 30px;
            border-radius: 30px;
            font-weight: 600;
            border: none;
            box-shadow: 0 4px 15px rgba(255, 155, 68, 0.3);
        }

        .btn-submit:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(255, 155, 68, 0.4);
            color: #fff;
        }

        .btn-cancel {
            background: #6c757d;
            color: #fff;
            padding: 10px 30px;
            border-radius: 30px;
            font-weight: 600;
            border: none;
            box-shadow: 0 4px 15px rgba(108, 117, 125, 0.3);
            margin-left: 10px;
        }

        .btn-cancel:hover {
            background: #5a6268;
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(108, 117, 125, 0.4);
            color: #fff;
        }

        .btn-add-ms {
            background: #343a40;
            color: #fff;
            border-radius: 30px;
            padding: 5px 20px;
            border: none;
            font-size: 12px;
        }

        .milestone-table thead {
            background: #f8f9fa;
        }

        .milestone-table th {
            border: none !important;
            color: #555;
            font-size: 13px;
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
                        <h3 class="page-title">Tender Management</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('tender-user.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('tender-user.tender-projects.index') }}">Tender
                                    Projects</a></li>
                            <li class="breadcrumb-item active">Edit Project</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <form action="{{ route('tender-user.tender-projects.update', $tender->id) }}" method="POST"
                        id="edit_tender_project_form">
                        @csrf
                        @method('PUT')
                        <div class="card">
                            <div class="card-body">
                                <h4 class="form-section-title"><i class="fas fa-edit"></i> Edit Project Information</h4>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Tender Name <span class="text-danger">*</span></label>
                                            <input type="text" name="tender_name" class="form-control"
                                                value="{{ $tender->tender_name }}">
                                            <span class="text-danger tender_name_error"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Tender ID / Reference No <span class="text-danger">*</span></label>
                                            <input type="text" name="tender_id_ref_no" class="form-control"
                                                value="{{ $tender->tender_id_ref_no }}">
                                            <span class="text-danger tender_id_ref_no_error"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Department / Organization <span class="text-danger">*</span></label>
                                            <input type="text" name="department_org" class="form-control"
                                                value="{{ $tender->department_org }}">
                                            <span class="text-danger department_org_error"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Category <span class="text-danger">*</span></label>
                                            <select name="category" id="category_select" class="form-control select2">
                                                <option value="">Select Category</option>
                                                <option value="Hardware"
                                                    {{ $tender->category == 'Hardware' ? 'selected' : '' }}>Hardware
                                                </option>
                                                <option value="AMC" {{ $tender->category == 'AMC' ? 'selected' : '' }}>
                                                    AMC</option>
                                                <option value="Software"
                                                    {{ $tender->category == 'Software' ? 'selected' : '' }}>Software
                                                </option>
                                            </select>
                                            <span class="text-danger category_error"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-3" id="category_title_div"
                                        style="{{ $tender->category ? '' : 'display:none;' }}">
                                        <div class="form-group">
                                            <label>Category Title <span class="text-danger">*</span></label>
                                            <input type="text" name="category_title" class="form-control"
                                                value="{{ $tender->category_title }}">
                                            <span class="text-danger category_title_error"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Tender Value (Lakhs)</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend"><span class="input-group-text">₹</span>
                                                </div>
                                                <input type="number" step="0.01" name="tender_value_lakhs"
                                                    class="form-control" value="{{ $tender->tender_value_lakhs }}">
                                            </div>
                                            <span class="text-danger tender_value_lakhs_error"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>EMD</label>
                                            <input type="text" name="emd" class="form-control"
                                                value="{{ $tender->emd }}">
                                            <span class="text-danger emd_error"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Delivery Date</label>
                                            <input type="date" name="delivery_date" class="form-control"
                                                value="{{ $tender->delivery_date }}">
                                            <span class="text-danger delivery_date_error"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Status <span class="text-danger">*</span></label>
                                            <select name="status" class="form-control select2">
                                                @foreach ($statuses as $status)
                                                    <option value="{{ $status->id }}"
                                                        {{ $tender->status == $status->id ? 'selected' : '' }}>
                                                        {{ $status->name }}</option>
                                                @endforeach
                                            </select>
                                            <span class="text-danger status_error"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>L1 (Quoted Value)</label>
                                            <input type="number" step="0.01" name="l1_quoted_value"
                                                class="form-control" value="{{ $tender->l1_quoted_value }}">
                                            <span class="text-danger l1_quoted_value_error"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Excellis IT Quote</label>
                                            <input type="number" step="0.01" name="excellis_it_quoted_price"
                                                class="form-control" value="{{ $tender->excellis_it_quoted_price }}">
                                            <span class="text-danger excellis_it_quoted_price_error"></span>
                                        </div>
                                    </div>
                                </div>

                                <h4 class="form-section-title mt-4"> Contacts</h4>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Contact Name</label>
                                            <input type="text" name="contact_authority_name" class="form-control"
                                                value="{{ $tender->contact_authority_name }}">
                                            <span class="text-danger contact_authority_name_error"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Contact Phone</label>
                                            <input type="text" name="contact_authority_phone" class="form-control"
                                                value="{{ $tender->contact_authority_phone }}">
                                            <span class="text-danger contact_authority_phone_error"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Contact Email</label>
                                            <input type="email" name="contact_authority_email" class="form-control"
                                                value="{{ $tender->contact_authority_email }}">
                                            <span class="text-danger contact_authority_email_error"></span>
                                        </div>
                                    </div>
                                </div>

                                <h4 class="form-section-title mt-4 d-flex justify-content-between">
                                    <span><i class="fas fa-list-ol"></i> Milestones</span>
                                    <button type="button" class="btn btn-add-ms" id="add_milestone_btn"><i
                                            class="fas fa-plus"></i> Add Milestone</button>
                                </h4>

                                <div class="table-responsive">
                                    <table class="table milestone-table" id="milestones_table">
                                        <thead>
                                            <tr>
                                                <th style="width: 25%;">Name <span class="text-danger">*</span></th>
                                                <th>Value</th>
                                                <th>Status</th>
                                                <th>Date</th>
                                                <th>Comment</th>
                                                <th>Mode</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($tender->milestones as $key => $milestone)
                                                <tr>
                                                    <td><input type="text"
                                                            name="milestones[{{ $key }}][milestone_name]"
                                                            class="form-control"
                                                            value="{{ $milestone->milestone_name }}"></td>
                                                    <td><input type="text"
                                                            name="milestones[{{ $key }}][milestone_value]"
                                                            class="form-control"
                                                            value="{{ $milestone->milestone_value }}"></td>
                                                    <td>
                                                        <select name="milestones[{{ $key }}][payment_status]"
                                                            class="form-control">
                                                            <option value="Due"
                                                                {{ $milestone->payment_status == 'Due' ? 'selected' : '' }}>
                                                                Due</option>
                                                            <option value="Paid"
                                                                {{ $milestone->payment_status == 'Paid' ? 'selected' : '' }}>
                                                                Paid</option>
                                                        </select>
                                                    </td>
                                                    <td><input type="date"
                                                            name="milestones[{{ $key }}][payment_date]"
                                                            class="form-control" value="{{ $milestone->payment_date }}">
                                                    </td>
                                                    <td>
                                                        <textarea name="milestones[{{ $key }}][milestone_comment]" class="form-control" rows="1">{{ $milestone->milestone_comment }}</textarea>
                                                    </td>
                                                    <td><select name="milestones[{{ $key }}][payment_mode]"
                                                            class="form-control">
                                                            <option value="">Select Mode</option>
                                                            <option value="Paypal">Paypal</option>
                                                            <option value="Stripe">Stripe</option>
                                                            <option value="Bank Transfer">Bank Transfer</option>
                                                            <option value="Cheque">Cheque</option>
                                                        </select></td>
                                                    <td><button type="button"
                                                            class="btn btn-outline-danger btn-sm remove-row"><i
                                                                class="fa fa-times"></i></button></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="submit-section mt-5 text-end">
                                    <button class="btn btn-submit" type="submit">Update Tender Project</button>
                                    <a href="{{ route('tender-user.tender-projects.index') }}"
                                        class="btn btn-cancel">Back</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            var msCount = {{ count($tender->milestones) }};

            // Category toggle
            $('#category_select').change(function() {
                if ($(this).val() != '') {
                    $('#category_title_div').fadeIn();
                } else {
                    $('#category_title_div').fadeOut();
                }
            });

            // Add milestone
            $('#add_milestone_btn').click(function() {
                var html = `<tr>
                <td><input type="text" name="milestones[${msCount}][milestone_name]" class="form-control" placeholder="Phase Name"></td>
                <td><input type="text" name="milestones[${msCount}][milestone_value]" class="form-control"></td>
                <td>
                    <select name="milestones[${msCount}][payment_status]" class="form-control">
                        <option value="Due">Due</option>
                        <option value="Paid">Paid</option>
                    </select>
                </td>
                <td><input type="date" name="milestones[${msCount}][payment_date]" class="form-control"></td>
                <td><textarea name="milestones[${msCount}][milestone_comment]" class="form-control" rows="1"></textarea></td>
                <td><td><select name="milestones[${msCount}][payment_mode]" class="form-control">
                                            <option value="">Select Mode</option>
                                            <option value="Paypal">Paypal</option>
                                            <option value="Stripe">Stripe</option>
                                          <option value="Bank Transfer">Bank Transfer</option>
                                                            <option value="Cheque">Cheque</option>
                                        </select></td>
                <td><button type="button" class="btn btn-outline-danger btn-sm remove-row"><i class="fa fa-times"></i></button></td>
            </tr>`;
                $('#milestones_table tbody').append(html);
                msCount++;
            });

            $(document).on('click', '.remove-row', function() {
                $(this).closest('tr').remove();
            });

            // AJAX Update
            $('#edit_tender_project_form').on('submit', function(e) {
                e.preventDefault();
                $('.text-danger').html('');
                $('#loading').addClass('loading');

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#loading').removeClass('loading');
                        if (response.success) {
                            toastr.success(response.success);
                            setTimeout(function() {
                                window.location.href =
                                    "{{ route('tender-user.tender-projects.index') }}";
                            }, 1000);
                        }
                    },
                    error: function(xhr) {
                        $('#loading').removeClass('loading');
                        if (xhr.status == 422) {
                            var errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                var errorKey = key.replace(/\./g, '_');
                                $('.' + errorKey + '_error').html(value[0]);
                                toastr.error(value[0]);
                            });
                        } else {
                            toastr.error('Internal Server Error. Please contact admin.');
                        }
                    }
                });
            });
        });
    </script>
@endpush
