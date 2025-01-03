@if (isset($type))
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasEdit" aria-labelledby="offcanvasRightLabel">
        <div class="offcanvas-header">
            <button type="button" class="text-reset cls_btn_left" data-bs-dismiss="offcanvas" aria-label="Close">
                <i class="fa fa-chevron-right" aria-hidden="true"></i>
            </button>
            <h4 id="offcanvasEditLabel">Edit Prospect Details</h4>
        </div>
        <div class="offcanvas-body">
            <form action="{{ route('bdm.transfer-taken.update', $prospect->id) }}"
                method="POST" enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="inputEnterYourName" class="col-form-label"> Sales
                            Executive
                            <span style="color: red;">*</span></label>
                        <select name="user_id" id="user_id" class="form-control" required
                            data-parsley-trigger="keyup">
                            <option value="">Select sales executive</option>
                            @foreach ($sales_executives as $sales_executive)
                                <option value="{{ $sales_executive->id }}"
                                    {{ $sales_executive->id == $prospect->user_id ? 'selected' : '' }}>
                                    {{ $sales_executive->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="inputEnterYourName" class="col-form-label"> Client Name
                            <span style="color: red;">*</span></label>
                        <input type="text" name="client_name" id="client_name" required
                            data-parsley-trigger="keyup" class="form-control"
                            value="{{ $prospect->client_name }}"
                            placeholder="Enter Client Name">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="inputEnterYourName" class="col-form-label">Business Name
                            <span style="color: red;">*</span></label>
                        <input type="text" name="business_name" id="business_name"
                            required data-parsley-trigger="keyup" class="form-control"
                            value="{{ $prospect->business_name }}"
                            placeholder="Enter Business Name">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="inputEnterYourName" class="col-form-label">Client Email
                            <span style="color: red;">*</span></label>
                        <input type="text" name="client_email" id="client_email" required
                            data-parsley-trigger="keyup" data-parsley-type="email"
                            data-parsley-type-message="Please enter a valid email address."
                            class="form-control" value="{{ $prospect->client_email }}"
                            placeholder="Enter Client Email">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="inputEnterYourName" class="col-form-label">Client Phone
                            <span style="color: red;">*</span></label>
                        <input type="text" name="client_phone" id="client_phone" required
                            data-parsley-trigger="keyup" data-parsley-type="number"
                            data-parsley-type-message="Please enter a valid phone number."
                            class="form-control" value="{{ $prospect->client_phone }}"
                            placeholder="Enter Client Phone Number">
                    </div>

                    {{-- clinent address --}}
                    <div class="col-md-12 mb-3">
                        <label for="inputEnterYourName" class="col-form-label">Business
                            Address <span style="color: red;">*</span></label>
                        <input type="text" name="business_address" id="business_address"
                            required data-parsley-trigger="keyup" class="form-control"
                            value="{{ $prospect->business_address }}"
                            placeholder="Enter Address">
                    </div>

                    {{-- website --}}
                    <div class="col-md-12 mb-3">
                        <label for="inputEnterYourName" class="col-form-label">Website
                            Link</label>
                        <input type="text" name="website" id="website"
                            data-parsley-required="false" data-parsley-trigger="keyup"
                            data-parsley-type="url"
                            data-parsley-type-message="Please enter a valid url."
                            class="form-control" value="{{ $prospect->website }}"
                            placeholder="Enter Website">
                    </div>
                    {{-- offer for --}}
                    <div class="col-md-12 mb-3">
                        <label for="inputEnterYourName" class="col-form-label">Service
                            Offered
                            <span style="color: red;">*</span></label>
                        <select name="offered_for" id="prospect_type" required
                            data-parsley-trigger="keyup" class="form-control">
                            <option value="">Select Service Offered</option>
                            <option value="Website Design & Development"
                                {{ $prospect->offered_for == 'Website Design & Development' ? 'selected' : '' }}>
                                Website Design & Development</option>
                            <option value="Mobile Application Development"
                                {{ $prospect->offered_for == 'Mobile Application Development' ? 'selected' : '' }}>
                                Application Development</option>
                            <option value="Digital Marketing & SEO"
                                {{ $prospect->offered_for == 'Digital Marketing & SEO' ? 'selected' : '' }}>
                                Digital Marketing & SEO</option>
                            <option value="Logo Design"
                                {{ $prospect->offered_for == 'Logo Design' ? 'selected' : '' }}>
                                Logo Design</option>
                            <option value="SEO"
                                {{ $prospect->offered_for == 'SEO' ? 'selected' : '' }}>
                                SEO</option>
                            <option value="SMO"
                                {{ $prospect->offered_for == 'SMO' ? 'selected' : '' }}>
                                SMO</option>
                            <option value="Other"
                                {{ $prospect->offered_for != 'SMO' && $prospect->offered_for != 'SEO' && $prospect->offered_for != 'Logo Design' && $prospect->offered_for != 'Digital Marketing & SEO' && $prospect->offered_for != 'Mobile Application Development' && $prospect->offered_for != 'Website Design & Development' ? 'selected' : '' }}>
                                Other</option>
                        </select>
                    </div>
                    <div id="other-value" class="col-md-12 mb-3">
                        @if (
                            $prospect->offered_for != 'SMO' &&
                                $prospect->offered_for != 'SEO' &&
                                $prospect->offered_for != 'Logo Design' &&
                                $prospect->offered_for != 'Digital Marketing & SEO' &&
                                $prospect->offered_for != 'Mobile Application Development' &&
                                $prospect->offered_for != 'Website Design & Development')
                            <label for="inputEnterYourName" class="col-form-label">Other
                                Value <span style="color: red;">*</span></label>
                            <input type="text" name="other_value" id="other_value"
                                required data-parsley-trigger="keyup" class="form-control"
                                value="{{ $prospect->offered_for }}"
                                placeholder="Enter Other Value">
                        @endif
                    </div>
                    {{--  price_quote --}}
                    <div class="col-md-12 mb-3">
                        <label for="inputEnterYourName" class="col-form-label">Price Quote
                            <span style="color: red;">*</span></label>
                        <input type="text" name="price_quote" id="price_quote"
                            required data-parsley-trigger="keyup"
                            data-parsley-type="number"
                            data-parsley-type-message="Please enter a valid number."
                            class="form-control" value="{{ $prospect->price_quote }}"
                            placeholder="Enter Price Quote">
                    </div>

                    {{-- transfer_token_by --}}
                    <div class="col-md-12 mb-3">
                        <label for="inputEnterYourName" class="col-form-label">Transfer
                            Taken By <span style="color: red;">*</span>
                        </label>
                        <select name="transfer_token_by" id="transfer_token_by"
                            class="form-control select2" required>
                            <option value="">Select Transfer Token By</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}"
                                    {{ $prospect->transfer_token_by == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    {{-- followup_date --}}
                    <div class="col-md-12 mb-3">
                        <label for="inputEnterYourName" class="col-form-label">Followup
                            Date <span style="color: red;">*</span></label>
                        <input type="date" name="followup_date" id="followup_date"
                            required class="form-control picker"
                            value="{{ $prospect->followup_date }}"
                            placeholder="Enter Followup Date">
                    </div>
                    {{-- followup_time --}}
                    <div class="col-md-12 mb-3">
                        <label for="inputEnterYourName" class="col-form-label">Followup
                            Time</label>
                        <input type="time" name="followup_time" id="followup_time"
                            class="form-control" value="{{ $prospect->followup_time }}"
                            placeholder="Enter Followup Time">
                    </div>
                    {{-- status --}}
                    <div class="col-md-12 mb-3">
                        <label for="inputEnterYourName" class="col-form-label">Status
                            <span style="color: red;">*</span></label>
                        <select name="status" id="status_edit" class="form-control"
                            required data-parsley-trigger="keyup">
                            <option value="">Select Status</option>
                            <option value="Win"
                                {{ $prospect->status == 'Win' ? 'selected' : '' }}>On board
                            </option>
                            <option value="Follow Up"
                                {{ $prospect->status == 'Follow Up' ? 'selected' : '' }}>
                                Follow Up</option>
                            <option value="Sent Proposal"
                                {{ $prospect->status == 'Sent Proposal' ? 'selected' : '' }}>
                                Sent Proposal</option>
                            <option value="Close"
                                {{ $prospect->status == 'Close' ? 'selected' : '' }}>Cancel
                            </option>
                        </select>
                    </div>
                    <div class="row" id="upfront_value_show_edit">
                        @if ($prospect->status == 'Win')
                            <div class="col-md-12 mb-3">
                                <label for="inputEnterYourName"
                                    class="col-form-label">Upfront
                                    Value</label>
                                <input type="text" name="upfront_value"
                                    id="upfront_value" class="form-control"
                                    value="{{ $prospect->upfront_value ?? '' }}"
                                    placeholder="Enter Upfront Value">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="inputEnterYourName"
                                    class="col-form-label">Sale
                                    Date</label>
                                <input type="date" name="sale_date" id="sale_date"
                                    class="form-control picker"
                                    value="{{ $prospect->sale_date ?? '' }}"
                                    placeholder="Enter Sale Date">
                            </div>
                        @endif
                    </div>
                    {{-- comments --}}
                    <div class="col-md-12 mb-3">
                        <label for="inputEnterYourName"
                            class="col-form-label">Comments</label>
                        <textarea name="comments" id="comments" cols="30" rows="10" class="form-control"
                            placeholder="Enter Comments"> {{ $prospect['comments'] }} </textarea>
                    </div>
                </div>
                <div class="d-flex alin-items-center w-100 text-end">
                    <button class="print_btn cancel_btn me-3" type="reset"><i class="far fa-times-circle"></i>
                        Cancel</button>
                    <button class="print_btn" type="submit"><i class="far fa-check-circle"></i>
                        Update</button>
                </div>
            </form>
        </div>
    </div>

@endif
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
    $(document).ready(function() {
          $('.select2').each(function() {
                $(this).select2({
                    dropdownParent: $(this).parent()
                });
            })
    });
</script>
<script>
    $(document).ready(function() {
        $('#prospect_type').on('change', function() {
            //    select 2 value get and seo,other value check
            var prospect_type = $(this).val();
            if (prospect_type.includes('Other')) {
                var html = '';
                html +=
                    '<label for="inputEnterYourName" class="col-form-label">Others Service <span style="color: red;">*</span></label>';
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
    $(document).ready(function() {
        $('#status_edit').on('change', function() {
            // get value win show the upfront value
            var status = $(this).val();
            // alert(status);
            if (status.includes('Win')) {
                var html = '';
                html +=
                    '<div class="col-md-12 mb-3"><label for="inputEnterYourName" data-parsley-type="number" class="col-form-label">Upfront Value <span style="color: red;">*</span></label><input type="text" name="upfront_value" id="upfront_value"  required data-parsley-trigger="keyup" data-parsley-type="number" data-parsley-type-message="Please enter a valid number." class="form-control" value="{{ old('upfront_value') }}" placeholder="Enter Upfront Value"></div><div class="col-md-12 mb-3"> <label for = "inputEnterYourName" class="col-form-label"> Sale Date <span style="color: red;">*</span></label></label> <input type="date" name ="sale_date" id ="sale_date" class="form-control picker"></div><h3 class="mt-4 text-uppercase">Milestone</h3><hr><div class="row"><div class="col-md-12 mb-3 pb-3"><div style="display: flex"><input type="text" name="milestone_name[]" class="form-control" value="" placeholder="Milestone name" id="" required data-parsley-trigger="keyup"></div></div><div class="col-md-12 mb-3 pb-3"><div style="display: flex"><input type="text" name="milestone_value[]" class="form-control" value="" placeholder="Milestone value" id="" required data-parsley-trigger="keyup" data-parsley-type="number" data-parsley-type-message="Please enter a valid number."></div></div><div class="col-md-12 mb-3 pb-3"><div style="display: flex"><textarea name="milestone_comment[]" class="form-control" placeholder="Milestone Comment" id="" cols="3" rows="2" ></textarea></div></div></div><div class="col-md-12 mb-3"><button type="button" class="btn btn-primary milestone-print-edit"><i class="fas fa-plus"></i> Add Milestone</button></div><div class="add-milestone-edit"></div></div>';
                $('#upfront_value_show_edit').html(html);
            } else {
                $('#upfront_value_show_edit').html('');
            }
        });
    });
</script>

<script>
    $(document).on('click', '.milestone-print-edit', function() {

    var html = '';
    html += '<div class="row">';
    html += '<div class="col-md-12 mb-3 pb-3">';
    html += '<div style="display: flex">';
    html +=
        '<input type="text" name="milestone_name[]" class="form-control" value="" placeholder="Milestone name" id="" required data-parsley-trigger="keyup">';
    html += '</div>';
    html += '</div>';
    html += '<div class="col-md-12 mb-3 pb-3">';
    html += '<div style="display: flex">';
    html +=
        '<input type="text" name="milestone_value[]" class="form-control" value="" placeholder="Milestone value" id="" required data-parsley-trigger="keyup" data-parsley-type="number" data-parsley-type-message="Please enter a valid number.">';
    html += '</div>';
    html += '</div>';
    // html += '<div class="col-md-12 mb-3 pb-3">';
    // html += '<div style="display: flex">';
    // html +=
    //     '<select name="payment_status[]" id="payment_status" class="form-control" required data-parsley-trigger="keyup"><option value="" disabled >Select Payment Status</option><option value="Paid">Paid</option><option value="Due" selected>Due</option></select>';
    // html += '</div>';
    // html += '</div>';
    // html += '<div class="col-md-12 mb-3 pb-3">';
    // html += '<div style="display: flex">';
    // html += '<input type="date" name="milestone_payment_date[]" class="form-control picker" value="" id="" required data-parsley-trigger="keyup">';
    // html += '</div>';
    // html += '</div>';
    // html += '<div class="col-md-12 mb-3 pb-3">';
    // html += '<div style="display: flex">';
    // html += '<input type="text" name="milestone_payment_mode[]" class="form-control" value="" id="" placeholder="Milestone payment mode" required data-parsley-trigger="keyup">';
    // html += '</div>';
    // html += '</div>';
    // html += '<div class="col-md-12 mb-3 pb-3">';
    // html += '<div style="display: flex">';
    // html +=
    //     '<input type="date" name="payment_date[]" class="form-control" value="" id="" required data-parsley-trigger="keyup">';
    // html += '</div>';
    // html += '</div>';
    html += '<div class="col-md-12 mb-3 pb-3">';
    html += '<div style="display: flex">';
    html +=
        '<textarea name="milestone_comment[]" class="form-control" placeholder="Milestone Comment" id="" cols="3" rows="2" ></textarea>';
    html += '</div>';
    html += '</div>';
    html += '<div class="col-md-12 mb-3 pb-3">';
    html +=
        '<button type="button" class="btn btn-danger remove"><i class="fas fa-minus"></i> Remove</button>';
    html += '</div>';
    html += '</div>';
    $('.add-milestone-edit').append(html);
});

$(document).on('click', '.remove', function() {
    $(this).closest('.row').remove();
});
</script>
