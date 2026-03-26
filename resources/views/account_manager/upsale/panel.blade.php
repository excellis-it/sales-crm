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
                        <form action="{{ route('account-manager.upsale.destroy', $upsale->id) }}" method="POST" class="d-inline"
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
        <form action="{{ route('account-manager.projects.upsale-store') }}" method="POST" id="upsale-form" data-parsley-validate="">
            @csrf
            <input type="hidden" name="project_id" value="{{ $project->id }}">
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label class="col-form-label">Upsale Project Type <span style="color:red;">*</span></label>
                    <select name="upsale_project_type[]" id="upsale_project_type" class="form-control" multiple required>
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
                    <input type="text" name="upsale_value" required data-parsley-trigger="keyup" data-parsley-type="number" class="form-control" placeholder="0.00">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="col-form-label">Upsale Upfront</label>
                    <input type="text" name="upsale_upfront" data-parsley-trigger="keyup" data-parsley-type="number" class="form-control" placeholder="0.00" value="0">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="col-form-label">Currency <span style="color:red;">*</span></label>
                    <select name="upsale_currency" class="form-control" required>
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
                    <select name="upsale_payment_method" class="form-control" required>
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

            {{-- Upsale Milestones --}}
            <h3 class="mt-2 text-uppercase">Upsale Milestones</h3>
            <hr>
            <div class="add-upsale-milestone"></div>
            <div class="col-md-12 mb-3">
                <button type="button" class="btn btn-sm btn-primary add-upsale-ms-btn"><i class="fa fa-plus"></i> Add Milestone</button>
            </div>

            <div class="col-md-12 mb-3 text-end">
                <button type="submit" class="btn submit-btn px-5">Save Upsale</button>
            </div>
        </form>
    </div>
</div>

<script>
(function () {
    // Select2
    if (typeof $.fn.select2 !== 'undefined') {
        $('#upsale_project_type').select2({ dropdownParent: $('#offcanvasUpsale') });
    }

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
        html += '<input type="text" name="milestone_name[]" class="form-control" placeholder="Milestone name" required data-parsley-trigger="keyup">';
        html += '</div></div>';
        html += '<div class="col-md-12 mb-3">';
        html += '<div style="display: flex">';
        html += '<input type="text" name="milestone_value[]" class="form-control" placeholder="Milestone value" required data-parsley-trigger="keyup" data-parsley-type="number" data-parsley-type-message="Please enter a valid number.">';
        html += '</div></div>';
        html += '<div class="col-md-12 mb-3">';
        html += '<div style="display: flex">';
        html += '<select name="payment_status[]" class="form-control upsale-payment-status" data-id="' + upsaleMsIdx + '" required data-parsley-trigger="keyup">';
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
            $('#upsale-ms-date-' + id).prop('required', true);
            $('#upsale-ms-mode-' + id).prop('required', true);
        } else {
            $('.upsale-payment-hide-' + id).hide();
            $('#upsale-ms-date-' + id).prop('required', false);
            $('#upsale-ms-mode-' + id).prop('required', false);
        }
    });
})();
</script>
