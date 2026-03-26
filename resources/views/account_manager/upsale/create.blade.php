<div class="p-4">
    <form action="{{ route('account-manager.projects.upsale-store') }}" method="POST" id="upsale-form" data-parsley-validate="">
        @csrf
        <input type="hidden" name="project_id" value="{{ $project->id }}">

        <!-- Project Type -->
        <div class="mb-3">
            <label class="form-label fw-semibold">Upsale Project Type <span class="text-danger">*</span></label>
            <select name="upsale_project_type[]" id="upsale_project_type" class="form-control select2-upsale" multiple required>
                <option value="Website Design & Development">Website Design &amp; Development</option>
                <option value="Mobile Application Development">Mobile Application Development</option>
                <option value="Digital Marketing">Digital Marketing</option>
                <option value="Logo Design">Logo Design</option>
                <option value="SEO">SEO</option>
                <option value="SMO">SMO</option>
                <option value="Other">Other</option>
            </select>
        </div>
        <div id="upsale-other-type" class="mb-3" style="display:none;">
            <label class="form-label">Other Project Type</label>
            <input type="text" name="other_project_type" class="form-control" placeholder="Specify project type">
        </div>

        <div class="row">
            <!-- Upsale Value -->
            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Upsale Value <span class="text-danger">*</span></label>
                <input type="number" step="0.01" min="0" name="upsale_value" id="upsale_value" class="form-control" placeholder="0.00" required>
            </div>
            <!-- Upsale Upfront -->
            <div class="col-md-6 mb-3">
                <label class="form-label fw-semibold">Upsale Upfront</label>
                <input type="number" step="0.01" min="0" name="upsale_upfront" id="upsale_upfront" class="form-control" placeholder="0.00" value="0">
            </div>
            <!-- Currency -->
            <div class="col-md-4 mb-3">
                <label class="form-label fw-semibold">Currency <span class="text-danger">*</span></label>
                <select name="upsale_currency" class="form-control" required>
                    <option value="INR">INR</option>
                    <option value="USD" selected>USD</option>
                    <option value="EUR">EUR</option>
                    <option value="GBP">GBP</option>
                    <option value="AUD">AUD</option>
                    <option value="CAD">CAD</option>
                </select>
            </div>
            <!-- Payment Method -->
            <div class="col-md-4 mb-3">
                <label class="form-label fw-semibold">Payment Method <span class="text-danger">*</span></label>
                <select name="upsale_payment_method" class="form-control" required>
                    <option value="">Select Method</option>
                    <option value="Bank Transfer">Bank Transfer</option>
                    <option value="PayPal">PayPal</option>
                    <option value="Stripe">Stripe</option>
                    <option value="Wise">Wise</option>
                    <option value="Crypto">Crypto</option>
                    <option value="Cash">Cash</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            <!-- Upsale Date -->
            <div class="col-md-4 mb-3">
                <label class="form-label fw-semibold">Upsale Date <span class="text-danger">*</span></label>
                <input type="date" name="upsale_date" class="form-control" max="{{ date('Y-m-d') }}" required>
            </div>
        </div>

        <!-- Upsale Milestones -->
        <div class="premium-card bg-light-soft border mb-3">
            <div class="section-header d-flex justify-content-between align-items-center">
                <span>Upsale Milestones</span>
                <button type="button" class="btn btn-sm btn-primary add-upsale-milestone-btn">
                    <i class="fas fa-plus me-1"></i> Add
                </button>
            </div>
            <div class="p-3">
                <div id="upsale-milestone-container"></div>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="offcanvas">Cancel</button>
            <button type="submit" class="btn btn-success px-4">Save Upsale</button>
        </div>
    </form>
</div>

<script>
(function () {
    // Select2 for project type
    if (typeof $.fn.select2 !== 'undefined') {
        $('#upsale_project_type').select2({ dropdownParent: $('#offcanvasUpsale') });
    }

    // Show/hide "Other" text input
    $('#upsale_project_type').on('change', function () {
        var vals = $(this).val() || [];
        $('#upsale-other-type').toggle(vals.includes('Other'));
    });

    var upsaleMilestoneIndex = 0;

    $('.add-upsale-milestone-btn').off('click').on('click', function () {
        upsaleMilestoneIndex++;
        var html = `
        <div class="milestone-item position-relative border rounded p-3 mb-2" id="upsale-ms-${upsaleMilestoneIndex}">
            <span class="remove-upsale-milestone position-absolute" style="top:8px;right:10px;cursor:pointer;color:#dc3545;" data-id="${upsaleMilestoneIndex}">
                <i class="fas fa-times-circle"></i>
            </span>
            <div class="row g-2">
                <div class="col-md-6">
                    <label class="form-label small">Milestone Name</label>
                    <input type="text" name="milestone_name[]" class="form-control form-control-sm" placeholder="e.g. Phase 2">
                </div>
                <div class="col-md-6">
                    <label class="form-label small">Value</label>
                    <input type="number" step="0.01" min="0" name="milestone_value[]" class="form-control form-control-sm" placeholder="0.00">
                </div>
                <div class="col-md-4">
                    <label class="form-label small">Status</label>
                    <select name="payment_status[]" class="form-control form-control-sm upsale-ms-status">
                        <option value="Unpaid">Unpaid</option>
                        <option value="Paid">Paid</option>
                    </select>
                </div>
                <div class="col-md-4 upsale-ms-date-col" style="display:none;">
                    <label class="form-label small">Payment Date</label>
                    <input type="date" name="milestone_payment_date[]" class="form-control form-control-sm">
                </div>
                <div class="col-md-4">
                    <label class="form-label small">Payment Mode</label>
                    <input type="text" name="milestone_payment_mode[]" class="form-control form-control-sm" placeholder="e.g. PayPal">
                </div>
                <div class="col-md-12">
                    <label class="form-label small">Comment</label>
                    <input type="text" name="milestone_comment[]" class="form-control form-control-sm" placeholder="Optional note">
                </div>
            </div>
        </div>`;
        $('#upsale-milestone-container').append(html);

        // Toggle payment date on status change
        $(`#upsale-ms-${upsaleMilestoneIndex} .upsale-ms-status`).on('change', function () {
            $(this).closest('.milestone-item').find('.upsale-ms-date-col').toggle($(this).val() === 'Paid');
        });

        // Remove milestone
        $(`#upsale-ms-${upsaleMilestoneIndex} .remove-upsale-milestone`).on('click', function () {
            $(`#upsale-ms-${$(this).data('id')}`).remove();
        });
    });
})();
</script>
