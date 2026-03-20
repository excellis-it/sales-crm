@if (isset($type))
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasEdit" aria-labelledby="offcanvasRightLabel">
        <div class="offcanvas-header">
            <button type="button" class="text-reset cls_btn_left" data-bs-dismiss="offcanvas" aria-label="Close">
                <i class="fa fa-chevron-right" aria-hidden="true"></i>
            </button>
            <h4 id="offcanvasEditLabel" class="text-dark">Edit Project: {{ $project->business_name }}</h4>
        </div>

        <div class="offcanvas-body">
            <form action="{{ route('sales-projects.update', $project->id) }}" method="post" id="edit-form-validation"
                data-parsley-validate="" enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <input type="hidden" name="customer" value="0">
                <input type="hidden" name="customer_id" class="edit_customer_id" value="{{ $project->customer_id }}">
                <input type="hidden" name="page_no" id="edit-page-no">

                <div class="row">
                    <div class="col-md-12">
                        <div class="section-header"><i class="fas fa-user-circle"></i> Customer Information</div>
                        <div class="card card-custom p-3 bg-white mb-4">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Client Name</label>
                                    <input type="text" name="client_name" value="{{ $project->client_name }}"
                                        class="form-control">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Client Email</label>
                                    <input type="email" name="client_email" id="edit_client_email"
                                        value="{{ $project->client_email }}" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Client Phone</label>
                                    <input type="text" value="{{ $project->client_phone }}" class="form-control"
                                        name="client_phone">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Client Address</label>
                                    <input type="text" value="{{ $project->client_address }}" class="form-control"
                                        name="client_address">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Business Name</label>
                                    <input type="text" value="{{ $project->business_name }}" class="form-control"
                                        name="business_name">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="section-header"><i class="fas fa-briefcase"></i> Project Details</div>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Project Type <span class="text-danger">*</span></label>
                        <select name="project_type[]" id="project_type_other" class="form-control mySelects"
                            multiple="multiple" required>
                            @php $selectedTypes = $project->projectTypes()->pluck('type')->toArray(); @endphp
                            <option value="Website Design & Development"
                                {{ in_array('Website Design & Development', $selectedTypes) ? 'selected' : '' }}>Website
                                Design & Development</option>
                            <option value="Mobile Application Development"
                                {{ in_array('Mobile Application Development', $selectedTypes) ? 'selected' : '' }}>
                                Mobile Application Development</option>
                            <option value="Digital Marketing"
                                {{ in_array('Digital Marketing', $selectedTypes) ? 'selected' : '' }}>Digital Marketing
                            </option>
                            <option value="Logo Design"
                                {{ in_array('Logo Design', $selectedTypes) ? 'selected' : '' }}>Logo Design</option>
                            <option value="SEO" {{ in_array('SEO', $selectedTypes) ? 'selected' : '' }}>SEO</option>
                            <option value="SMO" {{ in_array('SMO', $selectedTypes) ? 'selected' : '' }}>SMO</option>
                            <option value="Other" {{ in_array('Other', $selectedTypes) ? 'selected' : '' }}>Other
                            </option>
                        </select>
                    </div>

                    <div id="other-value-edit" class="col-md-12 mb-3">
                        @foreach ($project->projectTypes as $type)
                            @if ($type->type == 'Other')
                                <label class="form-label">Other Project Type Value <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="other_value" value="{{ $type->name }}"
                                    class="form-control" required>
                            @endif
                        @endforeach
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Project Value <span class="text-danger">*</span></label>
                        <input type="number" name="project_value" value="{{ $project->project_value }}" required
                            class="form-control">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Project Upfront <span class="text-danger">*</span></label>
                        <input type="number" name="project_upfront" value="{{ $project->project_upfront }}"
                            required class="form-control">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Currency <span class="text-danger">*</span></label>
                        <select name="currency" class="form-select" required>
                            <option value="INR" {{ $project->currency == 'INR' ? 'selected' : '' }}>INR</option>
                            <option value="USD" {{ $project->currency == 'USD' ? 'selected' : '' }}>USD</option>
                            <option value="EUR" {{ $project->currency == 'EUR' ? 'selected' : '' }}>EUR</option>
                            <option value="GBP" {{ $project->currency == 'GBP' ? 'selected' : '' }}>GBP</option>
                            <option value="AUD" {{ $project->currency == 'AUD' ? 'selected' : '' }}>AUD</option>
                            <option value="CAD" {{ $project->currency == 'CAD' ? 'selected' : '' }}>CAD</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Payment Mode <span class="text-danger">*</span></label>
                        <input type="text" name="payment_mode" value="{{ $project->payment_mode }}" required
                            class="form-control">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Project Opener <span class="text-danger">*</span></label>
                        <select name="project_opener" required class="form-select select2-edit">
                            @foreach ($project_openers as $opener)
                                <option value="{{ $opener->id }}"
                                    {{ $project->project_opener == $opener->id ? 'selected' : '' }}>
                                    {{ $opener->name }} ({{ $opener->email }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Project Closer</label>
                        <select name="project_closer" class="form-select select2-edit">
                            <option value="">Select Closer</option>
                            @foreach ($users as $u)
                                <option value="{{ $u->id }}"
                                    {{ $project->project_closer == $u->id ? 'selected' : '' }}>{{ $u->name }}
                                    ({{ $u->email }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Assigned To <span class="text-danger">*</span></label>
                        <select name="assigned_to" required class="form-select select2-edit">
                            @foreach ($account_managers as $am)
                                <option value="{{ $am->id }}"
                                    {{ $project->assigned_to == $am->id ? 'selected' : '' }}>{{ $am->name }}
                                    ({{ $am->email }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Sale Date <span class="text-danger">*</span></label>
                        <input type="date" name="sale_date" id="edit_sale_date"
                            value="{{ $project->sale_date }}" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Delivery TAT</label>
                        <input type="date" name="delivery_tat" id="edit_delivery_tat"
                            value="{{ $project->delivery_tat }}" class="form-control">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Website</label>
                        <input type="text" name="website" value="{{ $project->website }}" class="form-control"
                            placeholder="https://example.com">
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Project Description</label>
                        <textarea name="project_description" class="form-control" rows="3">{{ $project->project_description }}</textarea>
                    </div>

                    {{-- <div class="col-md-12 mb-3">
                        <label class="form-label">Internal Comment</label>
                        <textarea name="comment" class="form-control" rows="2">{{ $project->comment }}</textarea>
                    </div> --}}

                    <div class="col-md-12">
                        <div class="section-header"><i class="fas fa-tasks"></i> Milestones</div>
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="w-75">
                                <label class="form-label">Number of New Milestones</label>
                                <input type="number" id="number_of_milestone_edit" min="0"
                                    class="form-control" placeholder="Add more...">
                            </div>
                            <div class="ms-2">
                                <button type="button" class="btn btn-process mt-4 edit-milestone-print">Add</button>
                            </div>
                        </div>

                        <div id="existing-milestones">
                            @foreach ($project->projectMilestones as $key => $milestone)
                                <div class="milestone-item mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="mb-0 text-primary">Milestone #{{ $key + 1 }}</h6>
                                        @if ($milestone->payment_status != 'Paid')
                                            <i class="fas fa-times-circle remove-btn remove"></i>
                                        @endif
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Name</label>
                                            <input type="text" name="milestone_name[]"
                                                value="{{ $milestone->milestone_name }}" class="form-control"
                                                required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Value</label>
                                            <input type="number" name="milestone_value[]"
                                                value="{{ $milestone->milestone_value }}" class="form-control"
                                                required>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label class="form-label">Status</label>
                                            <select name="payment_status[]" class="form-select payment-status-toggle"
                                                data-id="edit-{{ $key }}">
                                                <option value="Due"
                                                    {{ $milestone->payment_status == 'Due' ? 'selected' : '' }}>Due
                                                </option>
                                                <option value="Paid"
                                                    {{ $milestone->payment_status == 'Paid' ? 'selected' : '' }}>Paid
                                                </option>
                                            </select>
                                        </div>
                                        <div class="payment-details-edit-{{ $key }}"
                                            style="{{ $milestone->payment_status == 'Paid' ? '' : 'display:none;' }}">
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Payment Date</label>
                                                    <input type="date" name="milestone_payment_date[]"
                                                        value="{{ $milestone->payment_date }}" class="form-control"
                                                        {{ $milestone->payment_status == 'Paid' ? 'required' : '' }}>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Payment Mode</label>
                                                    <select name="milestone_payment_mode[]" class="form-select"
                                                        {{ $milestone->payment_status == 'Paid' ? 'required' : '' }}>
                                                        <option value="">Select Mode</option>
                                                        <option value="Paypal"
                                                            {{ $milestone->payment_mode == 'Paypal' ? 'selected' : '' }}>
                                                            Paypal</option>
                                                        <option value="Stripe"
                                                            {{ $milestone->payment_mode == 'Stripe' ? 'selected' : '' }}>
                                                            Stripe</option>
                                                        <option value="Bank Transfer"
                                                            {{ $milestone->payment_mode == 'Bank Transfer' ? 'selected' : '' }}>
                                                            Bank Transfer</option>
                                                        <option value="Payoneer"
                                                            {{ $milestone->payment_mode == 'Payoneer' ? 'selected' : '' }}>
                                                            Payoneer</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label">Comment</label>
                                            <textarea name="milestone_comment[]" class="form-control" rows="2">{{ $milestone->milestone_comment }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="edit-milestone"></div>
                    </div>

                    <div class="col-md-12">
                        <div class="section-header"><i class="fas fa-file-pdf"></i> Documents</div>
                        @if ($project->projectDocuments->count() > 0)
                            <div class="mb-3">
                                <label class="form-label d-block">Existing Documents</label>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach ($project->projectDocuments as $doc)
                                        <a href="{{ route('sales-projects.document.download', $doc->id) }}"
                                            class="btn btn-outline-primary btn-sm rounded-pill">
                                            <i class="fas fa-download me-1"></i> Doc #{{ $loop->iteration }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        <div class="edit-pdf">
                            <div class="row milestone-item mx-0">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Upload New PDF</label>
                                    <input type="file" name="pdf[]" class="form-control"
                                        accept="application/pdf">
                                </div>
                                <div class="col-md-12">
                                    <button type="button" class="btn add-more-btn edit-pdf-button"><i
                                            class="fas fa-plus"></i> Add Another PDF</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-4 border-top text-end">
                    <button class="btn btn-light px-4 me-2" type="button"
                        data-bs-dismiss="offcanvas">Cancel</button>
                    <button class="btn submit-btn-modern" type="submit">Update Project</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Initialize Select2
            function initSelect2() {
                $('.select2-edit, .mySelects').each(function() {
                    $(this).select2({
                        dropdownParent: $('#offcanvasEdit')
                    });
                });
            }
            initSelect2();

            // Project Type "Other" Toggle
            $('#project_type_other').on('change', function() {
                if ($(this).val().includes('Other')) {
                    if ($('#other-value-edit input').length === 0) {
                        $('#other-value-edit').html(
                            '<label class="form-label">Other Project Type Value <span class="text-danger">*</span></label><input type="text" name="other_value" required class="form-control" placeholder="Specify type">'
                            );
                    }
                } else {
                    $('#other-value-edit').html('');
                }
            });

            // Milestone Payment Status Toggle (using delegation for dynamic elements)
            $(document).on('change', '.payment-status-toggle', function() {
                var targetId = $(this).data('id');
                var status = $(this).val();
                var $container = $('.payment-details-' + targetId);

                if (status === 'Paid') {
                    $container.slideDown();
                    $container.find('input, select').prop('required', true);
                } else {
                    $container.slideUp();
                    $container.find('input, select').prop('required', false);
                }
            });

            // Add New Milestones
            $('.edit-milestone-print').on('click', function() {
                var count = $('#number_of_milestone_edit').val();
                if (!count || count <= 0) return;

                var currentCount = $('.milestone-item').length;
                for (let i = 1; i <= count; i++) {
                    var newId = currentCount + i;
                    var html = `
                        <div class="milestone-item mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0 text-primary">New Milestone #${newId}</h6>
                                <i class="fas fa-times-circle remove-btn remove"></i>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Name</label>
                                    <input type="text" name="milestone_name[]" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Value</label>
                                    <input type="number" name="milestone_value[]" class="form-control" required>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Status</label>
                                    <select name="payment_status[]" class="form-select payment-status-toggle" data-id="new-${newId}">
                                        <option value="Due">Due</option>
                                        <option value="Paid">Paid</option>
                                    </select>
                                </div>
                                <div class="payment-details-new-${newId}" style="display:none;">
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
                    $('.edit-milestone').append(html);
                }
                $('#number_of_milestone_edit').val('');
            });

            // Remove Milestone/PDF
            $(document).on('click', '.remove', function() {
                $(this).closest('.milestone-item, .row').fadeOut(300, function() {
                    $(this).remove();
                });
            });

            // PDF Add More
            $('.edit-pdf-button').click(function() {
                var html = `
                    <div class="row milestone-item mx-0 mt-3">
                        <div class="col-md-10 mb-2">
                            <input type="file" name="pdf[]" class="form-control" accept="application/pdf">
                        </div>
                        <div class="col-md-2 text-end">
                            <i class="fas fa-trash-alt remove-btn remove"></i>
                        </div>
                    </div>`;
                $('.edit-pdf').append(html);
            });

            // Date validation for Sale Date and Delivery TAT
            $('#edit_sale_date').on('change', function() {
                var saleDate = $(this).val();
                if (saleDate) {
                    var minDate = new Date(saleDate);
                    minDate.setDate(minDate.getDate() + 1);
                    $('#edit_delivery_tat').attr('min', minDate.toISOString().split('T')[0]);
                }
            });
        });
    </script>
@endif
