<div class="p-4">
    <form action="{{ route('account-manager.upsale.update', $upsale->id) }}" method="POST" id="upsale-edit-form" data-parsley-validate="">
        @csrf
        <input type="hidden" name="project_id" value="{{ $project->id }}">
        <div class="row">
            <div class="col-md-12 mb-3">
                <label class="col-form-label">Upsale Project Type <span style="color:red;">*</span></label>
                @php $selectedTypes = $upsale->upsale_project_type ?? []; @endphp
                <select name="upsale_project_type[]" id="upsale_project_type_edit" class="form-control" multiple required>
                    @foreach(['Website Design & Development','Mobile Application Development','Digital Marketing','Logo Design','SEO','SMO','Other'] as $opt)
                        <option value="{{ $opt }}" {{ in_array($opt, $selectedTypes) ? 'selected' : '' }}>{{ $opt }}</option>
                    @endforeach
                </select>
            </div>
            <div id="upsale-other-type-edit" class="col-md-12 mb-3" style="display:{{ in_array('Other', $selectedTypes) ? 'block' : 'none' }};">
                <label class="col-form-label">Other Project Type</label>
                <input type="text" name="other_project_type" class="form-control" value="{{ $upsale->other_project_type }}" placeholder="Enter other project type">
            </div>

            <div class="col-md-6 mb-3">
                <label class="col-form-label">Upsale Value <span style="color:red;">*</span></label>
                <input type="text" name="upsale_value" required data-parsley-trigger="keyup" data-parsley-type="number" class="form-control" value="{{ $upsale->upsale_value }}">
            </div>
            <div class="col-md-6 mb-3">
                <label class="col-form-label">Upsale Upfront</label>
                <input type="text" name="upsale_upfront" data-parsley-trigger="keyup" data-parsley-type="number" class="form-control" value="{{ $upsale->upsale_upfront }}">
            </div>
            <div class="col-md-6 mb-3">
                <label class="col-form-label">Currency <span style="color:red;">*</span></label>
                <select name="upsale_currency" class="form-control" required>
                    @foreach(['INR','USD','EUR','GBP','AUD','CAD'] as $cur)
                        <option value="{{ $cur }}" {{ $upsale->upsale_currency == $cur ? 'selected' : '' }}>{{ $cur }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="col-form-label">Payment Method <span style="color:red;">*</span></label>
                <select name="upsale_payment_method" class="form-control" required>
                    <option value="">Select Payment Method</option>
                    @foreach(['Bank Transfer','PayPal','Stripe','Wise','Payoneer','Crypto','Cash','Other'] as $pm)
                        <option value="{{ $pm }}" {{ $upsale->upsale_payment_method == $pm ? 'selected' : '' }}>{{ $pm }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="col-form-label">Upsale Date <span style="color:red;">*</span></label>
                <input type="date" name="upsale_date" class="form-control" value="{{ $upsale->upsale_date }}" max="{{ date('Y-m-d') }}" required>
            </div>
        </div>

        <h3 class="mt-2 text-uppercase">Upsale Milestones</h3>
        <hr>
        <div class="edit-upsale-milestone">
            @foreach($upsale->milestones as $idx => $ms)
            <div class="row" id="edit-upsale-ms-row-{{ $idx }}">
                <div class="col-md-12 mb-3">
                    <div style="display: flex">
                        <input type="text" name="milestone_name[]" class="form-control" value="{{ $ms->milestone_name }}" placeholder="Milestone name" required data-parsley-trigger="keyup">
                    </div>
                </div>
                <div class="col-md-12 mb-3">
                    <div style="display: flex">
                        <input type="text" name="milestone_value[]" class="form-control" value="{{ $ms->milestone_value }}" placeholder="Milestone value" required data-parsley-trigger="keyup" data-parsley-type="number">
                    </div>
                </div>
                <div class="col-md-12 mb-3">
                    <div style="display: flex">
                        <select name="payment_status[]" class="form-control edit-upsale-payment-status" data-id="{{ $idx }}" required>
                            <option value="">Select Payment Status</option>
                            <option value="Paid" {{ $ms->payment_status == 'Paid' ? 'selected' : '' }}>Paid</option>
                            <option value="Due" {{ $ms->payment_status != 'Paid' ? 'selected' : '' }}>Due</option>
                        </select>
                    </div>
                </div>
                <div class="edit-upsale-payment-hide-{{ $idx }}" style="display:{{ $ms->payment_status == 'Paid' ? 'block' : 'none' }};">
                    <div class="col-md-12 mb-3">
                        <div style="display: flex">
                            <input type="date" name="milestone_payment_date[]" class="form-control" value="{{ $ms->payment_date }}" id="edit-upsale-ms-date-{{ $idx }}">
                        </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <div style="display: flex">
                            <select name="milestone_payment_mode[]" class="form-control" id="edit-upsale-ms-mode-{{ $idx }}">
                                <option value="">Select Payment Mode</option>
                                <option value="Paypal" {{ $ms->payment_mode == 'Paypal' ? 'selected' : '' }}>Paypal</option>
                                <option value="Stripe" {{ $ms->payment_mode == 'Stripe' ? 'selected' : '' }}>Stripe</option>
                                <option value="Bank Transfer" {{ $ms->payment_mode == 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                <option value="Payoneer" {{ $ms->payment_mode == 'Payoneer' ? 'selected' : '' }}>Payoneer</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mb-3">
                    <div style="display: flex">
                        <textarea name="milestone_comment[]" class="form-control" placeholder="Milestone Comment" cols="3" rows="2">{{ $ms->milestone_comment }}</textarea>
                    </div>
                </div>
                <div class="col-md-12 mb-3">
                    <button type="button" class="btn btn-danger remove-edit-upsale-ms"><i class="fa fa-minus"></i> Remove</button>
                </div>
            </div>
            @endforeach
        </div>
        <div class="col-md-12 mb-3">
            <button type="button" class="btn btn-sm btn-primary add-edit-upsale-ms-btn"><i class="fa fa-plus"></i> Add Milestone</button>
        </div>

        <div class="col-md-12 mb-3 text-end">
            <button type="submit" class="btn submit-btn px-5">Update Upsale</button>
        </div>
    </form>
</div>

<script>
(function () {
    if (typeof $.fn.select2 !== 'undefined') {
        $('#upsale_project_type_edit').select2({ dropdownParent: $('#offcanvasUpsaleEdit') });
    }

    $('#upsale_project_type_edit').on('change', function () {
        var vals = $(this).val() || [];
        $('#upsale-other-type-edit').toggle(vals.includes('Other'));
    });

    // Existing milestone status toggle
    $(document).on('change', '.edit-upsale-payment-status', function () {
        var id = $(this).data('id');
        var status = $(this).val();
        if (status == 'Paid') {
            $('.edit-upsale-payment-hide-' + id).show();
            $('#edit-upsale-ms-date-' + id).prop('required', true);
            $('#edit-upsale-ms-mode-' + id).prop('required', true);
        } else {
            $('.edit-upsale-payment-hide-' + id).hide();
            $('#edit-upsale-ms-date-' + id).prop('required', false);
            $('#edit-upsale-ms-mode-' + id).prop('required', false);
        }
    });

    // Remove milestone
    $(document).on('click', '.remove-edit-upsale-ms', function () {
        $(this).closest('.row').remove();
    });

    // Add new milestone
    var editMsIdx = 1000;
    $('.add-edit-upsale-ms-btn').off('click').on('click', function () {
        editMsIdx++;
        var html = '';
        html += '<div class="row" id="edit-upsale-ms-row-' + editMsIdx + '">';
        html += '<div class="col-md-12 mb-3"><div style="display: flex">';
        html += '<input type="text" name="milestone_name[]" class="form-control" placeholder="Milestone name" required data-parsley-trigger="keyup">';
        html += '</div></div>';
        html += '<div class="col-md-12 mb-3"><div style="display: flex">';
        html += '<input type="text" name="milestone_value[]" class="form-control" placeholder="Milestone value" required data-parsley-trigger="keyup" data-parsley-type="number">';
        html += '</div></div>';
        html += '<div class="col-md-12 mb-3"><div style="display: flex">';
        html += '<select name="payment_status[]" class="form-control edit-upsale-payment-status" data-id="' + editMsIdx + '" required>';
        html += '<option value="">Select Payment Status</option><option value="Paid">Paid</option><option value="Due" selected>Due</option></select>';
        html += '</div></div>';
        html += '<div class="edit-upsale-payment-hide-' + editMsIdx + '" style="display:none;">';
        html += '<div class="col-md-12 mb-3"><div style="display: flex">';
        html += '<input type="date" name="milestone_payment_date[]" class="form-control" id="edit-upsale-ms-date-' + editMsIdx + '">';
        html += '</div></div>';
        html += '<div class="col-md-12 mb-3"><div style="display: flex">';
        html += '<select name="milestone_payment_mode[]" class="form-control" id="edit-upsale-ms-mode-' + editMsIdx + '">';
        html += '<option value="">Select Payment Mode</option>';
        html += '<option value="Paypal">Paypal</option>';
        html += '<option value="Stripe">Stripe</option>';
        html += '<option value="Bank Transfer">Bank Transfer</option>';
        html += '<option value="Payoneer">Payoneer</option>';
        html += '</select>';
        html += '</div></div></div>';
        html += '<div class="col-md-12 mb-3"><div style="display: flex">';
        html += '<textarea name="milestone_comment[]" class="form-control" placeholder="Milestone Comment" cols="3" rows="2"></textarea>';
        html += '</div></div>';
        html += '<div class="col-md-12 mb-3">';
        html += '<button type="button" class="btn btn-danger remove-edit-upsale-ms"><i class="fa fa-minus"></i> Remove</button>';
        html += '</div></div>';
        $('.edit-upsale-milestone').append(html);
    });
})();
</script>
