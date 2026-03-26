<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasUpsale" aria-labelledby="offcanvasUpsaleLabel" style="width:600px;">
    <div class="offcanvas-header">
        <button type="button" class="text-reset cls_btn_left" data-bs-dismiss="offcanvas" aria-label="Close">
            <i class="fa fa-chevron-right" aria-hidden="true"></i>
        </button>
        <h4 id="offcanvasUpsaleLabel">Upsale - {{ $project->business_name }}</h4>
    </div>
    <div class="offcanvas-body">

        {{-- Existing Upsales --}}
        @if($project->upsales->count() > 0)
            <h3 class="text-uppercase">Existing Upsales</h3>
            <hr>
            @foreach($project->upsales as $idx => $upsale)
            <div class="border rounded p-3 mb-3" style="border-left:3px solid #6f42c1 !important;">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <strong>Upsale #{{ $idx + 1 }}</strong>
                        <small class="text-muted ms-2">{{ $upsale->upsale_date ? date('d M, Y', strtotime($upsale->upsale_date)) : '' }}</small>
                    </div>
                    <div>
                        <button class="btn btn-sm btn-outline-primary btn-edit-upsale" data-id="{{ $upsale->id }}" title="Edit">
                            <i class="fa fa-edit"></i>
                        </button>
                        <form action="{{ route('admin.upsale.destroy', $upsale->id) }}" method="POST" class="d-inline"
                            onsubmit="return confirm('Are you sure you want to delete this upsale?')">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                <i class="fa fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-1">
                        <label class="col-form-label p-0"><strong>Project Type:</strong></label>
                        <div>{{ $upsale->project_type_label }}</div>
                    </div>
                    <div class="col-md-6 mb-1">
                        <label class="col-form-label p-0"><strong>Value:</strong></label>
                        <div>{{ number_format($upsale->upsale_value, 2) }} {{ $upsale->upsale_currency }}</div>
                    </div>
                    <div class="col-md-6 mb-1">
                        <label class="col-form-label p-0"><strong>Upfront:</strong></label>
                        <div>{{ number_format($upsale->upsale_upfront, 2) }}</div>
                    </div>
                    <div class="col-md-6 mb-1">
                        <label class="col-form-label p-0"><strong>Payment Method:</strong></label>
                        <div>{{ $upsale->upsale_payment_method }}</div>
                    </div>
                </div>
                @if($upsale->milestones->count() > 0)
                    <hr class="my-2">
                    <label class="col-form-label p-0"><strong>Milestones:</strong></label>
                    <table class="table table-sm table-bordered mb-0 mt-1">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Value</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($upsale->milestones as $ms)
                            <tr>
                                <td>{{ $ms->milestone_name }}</td>
                                <td>{{ number_format($ms->milestone_value, 2) }}</td>
                                <td>
                                    @if($ms->payment_status == 'Paid')
                                        <span class="badge bg-success">Paid</span>
                                    @else
                                        <span class="badge bg-warning text-dark">{{ $ms->payment_status }}</span>
                                    @endif
                                </td>
                                <td>{{ $ms->payment_date ? date('d M, Y', strtotime($ms->payment_date)) : '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
            @endforeach
        @endif

        {{-- Add New Upsale Form --}}
        <h3 class="mt-4 text-uppercase">Add New Upsale</h3>
        <hr>
        <form action="{{ route('admin.sales-projects.upsale-store') }}" method="POST" id="upsale-form">
            @csrf
            <input type="hidden" name="project_id" value="{{ $project->id }}">
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label class="col-form-label">Assign To Account Manager <small class="text-muted">(Goal will be credited to this AM)</small></label>
                    <select name="assigned_to" class="form-control" id="upsale_assigned_to">
                        <option value="">-- Use Project's AM ({{ $project->accountManager->name ?? 'None' }}) --</option>
                        @foreach($account_managers as $am)
                            <option value="{{ $am->id }}">{{ $am->name }} ({{ $am->email }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-12 mb-3">
                    <label class="col-form-label">Upsale Project Type <span style="color:red;">*</span></label>
                    <select name="upsale_project_type[]" id="upsale_project_type" class="form-control" multiple >
                        <option value="Website Design & Development">Website Design & Development</option>
                        <option value="Mobile Application Development">Mobile Application Development</option>
                        <option value="Digital Marketing">Digital Marketing</option>
                        <option value="Logo Design">Logo Design</option>
                        <option value="SEO">SEO</option>
                        <option value="SMO">SMO</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div id="upsale-other-type" class="col-md-12 mb-3" style="display:none;">
                    <label class="col-form-label">Other Project Type</label>
                    <input type="text" name="other_project_type" class="form-control" placeholder="Enter other project type">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="col-form-label">Upsale Value <span style="color:red;">*</span></label>
                    <input type="text" name="upsale_value"  data-parsley-trigger="keyup" data-parsley-type="number" class="form-control" placeholder="0.00">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="col-form-label">Upsale Upfront</label>
                    <input type="text" name="upsale_upfront" data-parsley-trigger="keyup" data-parsley-type="number" class="form-control" placeholder="0.00" value="0">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="col-form-label">Currency <span style="color:red;">*</span></label>
                    <select name="upsale_currency" class="form-control" >
                        <option value="INR">INR</option>
                        <option value="USD" selected>USD</option>
                        <option value="EUR">EUR</option>
                        <option value="GBP">GBP</option>
                        <option value="AUD">AUD</option>
                        <option value="CAD">CAD</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="col-form-label">Payment Method <span style="color:red;">*</span></label>
                    <select name="upsale_payment_method" class="form-control" >
                        <option value="">Select Payment Method</option>
                        <option value="Bank Transfer">Bank Transfer</option>
                        <option value="PayPal">PayPal</option>
                        <option value="Stripe">Stripe</option>
                        <option value="Wise">Wise</option>
                        <option value="Payoneer">Payoneer</option>
                        <option value="Crypto">Crypto</option>
                        <option value="Cash">Cash</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="col-form-label">Upsale Date <span style="color:red;">*</span></label>
                    <input type="date" name="upsale_date" class="form-control" max="{{ date('Y-m-d') }}" required>
                </div>
            </div>
            <div id="upsale-errors" class="alert alert-danger" style="display:none;"></div>

            {{-- Upsale Milestones --}}
            <h3 class="mt-2 text-uppercase">Upsale Milestones</h3>
            <hr>
            <div class="add-upsale-milestone"></div>
            <div class="col-md-12 mb-3">
                <button type="button" class="btn btn-sm btn-primary add-upsale-ms-btn"><i class="fa fa-plus"></i> Add Milestone</button>
            </div>

            <div class="col-md-12 mb-3 text-end">
                <button type="submit" class="btn submit-btn px-5" id="btn-save-upsale">Save Upsale</button>
            </div>
        </form>
    </div>
</div>

<script>
(function () {
    // Select2
    if (typeof $.fn.select2 !== 'undefined') {
        $('#upsale_project_type').select2({ dropdownParent: $('#offcanvasUpsale') });
        $('#upsale_assigned_to').select2({ dropdownParent: $('#offcanvasUpsale') });
    }

    // AJAX Form Submit
    $('#upsale-form').on('submit', function(e) {
        e.preventDefault();
        var $form = $(this);
        var $btn = $('#btn-save-upsale');
        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');
        $('.error-msg').remove();

        $.ajax({
            url: $form.attr('action'),
            method: 'POST',
            data: new FormData(this),
            processData: false,
            contentType: false,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(response) {
                if (response.status === 'success') {
                    toastr.success(response.message);
                    bootstrap.Offcanvas.getInstance(document.getElementById('offcanvasUpsale')).hide();
                    location.reload(); // Reload to update table
                }
            },
            error: function(xhr) {
                $btn.prop('disabled', false).text('Save Upsale');
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    $.each(errors, function(key, val) {
                        var fieldName = key;
                        if (key.includes('.')) {
                            var parts = key.split('.');
                            fieldName = parts[0] + '[]';
                            var index = parts[1];
                            var $field = $form.find('[name="' + fieldName + '"]').eq(index);
                        } else if (key === 'upsale_project_type') {
                             var $field = $form.find('[name="upsale_project_type[]"]');
                         } else {
                            var $field = $form.find('[name="' + fieldName + '"]');
                        }

                        if ($field.length) {
                             var $parent = $field.closest('.mb-3');
                             $parent.append('<div class="text-danger mt-1 error-msg" style="font-size: 13px;">' + val[0] + '</div>');
                        }
                    });
                    toastr.error('Please fix the errors below.');
                } else {
                    toastr.error('Something went wrong!');
                }
            }
        });
    });

    // Other type toggle
    $('#upsale_project_type').on('change', function () {
        var vals = $(this).val() || [];
        $('#upsale-other-type').toggle(vals.includes('Other'));
    });

    // Add milestone
    var upsaleMsIdx = 0;
    $('.add-upsale-ms-btn').off('click').on('click', function () {
        upsaleMsIdx++;
        var html = '';
        html += '<div class="row" id="upsale-ms-row-' + upsaleMsIdx + '">';
        html += '<div class="col-md-12 mb-3">';
        html += '<div style="display: flex">';
        html += '<input type="text" name="milestone_name[]" class="form-control" placeholder="Milestone name" >';
        html += '</div></div>';
        html += '<div class="col-md-12 mb-3">';
        html += '<div style="display: flex">';
        html += '<input type="text" name="milestone_value[]" class="form-control" placeholder="Milestone value" >';
        html += '</div></div>';
        html += '<div class="col-md-12 mb-3">';
        html += '<div style="display: flex">';
        html += '<select name="payment_status[]" class="form-control upsale-payment-status" data-id="' + upsaleMsIdx + '" >';
        html += '<option value="">Select Payment Status</option><option value="Paid">Paid</option><option value="Due" selected>Due</option></select>';
        html += '</div></div>';
        html += '<div class="upsale-payment-hide-' + upsaleMsIdx + '" style="display:none;">';
        html += '<div class="col-md-12 mb-3"><div style="display: flex">';
        html += '<input type="date" name="milestone_payment_date[]" class="form-control" id="upsale-ms-date-' + upsaleMsIdx + '">';
        html += '</div></div>';
        html += '<div class="col-md-12 mb-3"><div style="display: flex">';
        html += '<select name="milestone_payment_mode[]" class="form-control" id="upsale-ms-mode-' + upsaleMsIdx + '">';
        html += '<option value="">Select Payment Mode</option>';
        html += '<option value="Paypal">Paypal</option>';
        html += '<option value="Stripe">Stripe</option>';
        html += '<option value="Bank Transfer">Bank Transfer</option>';
        html += '<option value="Payoneer">Payoneer</option>';
        html += '</select>';
        html += '</div></div>';
        html += '</div>';
        html += '<div class="col-md-12 mb-3"><div style="display: flex">';
        html += '<textarea name="milestone_comment[]" class="form-control" placeholder="Milestone Comment" cols="3" rows="2"></textarea>';
        html += '</div></div>';
        html += '<div class="col-md-12 mb-3">';
        html += '<button type="button" class="btn btn-danger remove-upsale-ms"><i class="fa fa-minus"></i> Remove</button>';
        html += '</div></div>';
        $('.add-upsale-milestone').append(html);
    });

    // Remove milestone
    $(document).on('click', '.remove-upsale-ms', function () {
        $(this).closest('.row').remove();
    });

    // Toggle payment fields on status change
    $(document).on('change', '.upsale-payment-status', function () {
        var id = $(this).data('id');
        var status = $(this).val();
        if (status == 'Paid') {
            $('.upsale-payment-hide-' + id).show();
            $('#upsale-ms-date-' + id).prop('', true);
            $('#upsale-ms-mode-' + id).prop('', true);
        } else {
            $('.upsale-payment-hide-' + id).hide();
            $('#upsale-ms-date-' + id).prop('', false);
            $('#upsale-ms-mode-' + id).prop('', false);
        }
    });
})();
</script>
