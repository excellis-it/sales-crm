@if (isset($type))
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasEdit" aria-labelledby="offcanvasRightLabel">
        <div class="offcanvas-header">
            <button type="button" class="text-reset cls_btn_left" data-bs-dismiss="offcanvas" aria-label="Close">
                <i class="fa fa-chevron-right" aria-hidden="true"></i>
            </button>
            <h4 id="offcanvasEditLabel">Edit Project Details</h4>
        </div>
        <div class="offcanvas-body">
            <form action="{{ route('sales-projects.update', $project->id) }}" method="post" data-parsley-validate=""
                id="edit-form-validation" enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <input type="hidden" name="customer_id" class="edit_customer_id" value="{{ $project->customer_id }}">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="inputEnterYourName" class="col-form-label">Type of customer
                            <span style="color: red;">*</span></label>
                        <input type="radio" name="customer" id="new_user" value="1" required class="customer"
                            data-parsley-trigger="keyup" checked> New user
                        <input type="radio" name="customer" id="existing_user" value="0" required
                            class="customer" data-parsley-trigger="keyup"> Existing user
                    </div>
                    <div class="col-md-12 mb-3 select_user" id="select_user">

                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="inputEnterYourName" class="col-form-label"> Client Name
                            <span style="color: red;">*</span></label>
                        <input type="text" name="client_name" id="client_name" required data-parsley-trigger="keyup"
                            value="{{ $project->client_name }}" class="form-control client_name"
                            placeholder="Enter Client Name">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="inputEnterYourName" class="col-form-label">Client Email
                            <span style="color: red;">*</span></label>
                        <input type="text" name="client_email" id="edit_client_email" required
                            data-parsley-trigger="keyup" data-parsley-type="email"
                            data-parsley-type-message="Please enter a valid email address."
                            class="form-control client_email" value="{{ $project->client_email }}"
                            placeholder="Enter Client Email">
                        <span class="edit_client_email_error" style="color:red;"></span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="inputEnterYourName" class="col-form-label">Client Phone
                            <span style="color: red;">*</span></label>
                        <input type="text" name="client_phone" id="client_phone" required
                            data-parsley-trigger="keyup" data-parsley-type="number"
                            data-parsley-type-message="Please enter a valid phone number."
                            value="{{ $project->client_phone }}" class="form-control client_phone"
                            placeholder="Enter Client Phone Number">
                    </div>

                    {{-- clinent address --}}
                    <div class="col-md-6 mb-3">
                        <label for="inputEnterYourName" class="col-form-label">Client
                            Address <span style="color: red;">*</span></label>
                        <input type="text" name="client_address" id="client_address" required
                            data-parsley-trigger="keyup" class="form-control client_address"
                            value=" {{ $project->client_address }}" placeholder="Enter Address">
                        @if ($errors->has('address'))
                            <div class="error" style="color:red;">
                                {{ $errors->first('address') }}</div>
                        @endif
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="inputEnterYourName" class="col-form-label">Business Name
                            <span style="color: red;">*</span></label>
                        <input type="text" name="business_name" id="business_name" required
                            data-parsley-trigger="keyup" value="{{ $project->business_name }}" class="form-control"
                            placeholder="Enter Business Name">
                    </div>
                    <h3 class="mt-4 text-uppercase">Project Details</h3>
                    <hr>
                    {{-- project type in select2 box --}}
                    <div class="col-md-12 mb-3">
                        <label for="inputEnterYourName" class="col-form-label">Project
                            Type <span style="color: red;">*</span></label>
                        <select name="project_type" id="project_type" required data-parsley-trigger="keyup"
                            class="form-control">
                            <option value="">Select Project Type</option>
                            <option value="Website Design & Development"
                                @if (isset($project['projectTypes']['type']) && $project['projectTypes']['type'] == 'Website Design & Development') {{ 'selected' }} @endif>
                                Website Design & Development</option>
                            <option value="Mobile Application Development"
                                @if (isset($project['projectTypes']['type']) && $project['projectTypes']['type'] == 'Mobile Application Development') {{ 'selected' }} @endif>
                                Mobile Application Development</option>
                            <option value="Digital Marketing"
                                @if (isset($project['projectTypes']['type']) && $project['projectTypes']['type'] == 'Digital Marketing') {{ 'selected' }} @endif>
                                Digital Marketing</option>
                            <option value="Logo Design" @if (isset($project['projectTypes']['type']) && $project['projectTypes']['type'] == 'Logo Design') {{ 'selected' }} @endif>
                                Logo Design</option>
                            <option value="SEO" @if (isset($project['projectTypes']['type']) && $project['projectTypes']['type'] == 'SEO') {{ 'selected' }} @endif>
                                SEO</option>
                            <option value="SMO" @if (isset($project['projectTypes']['type']) && $project['projectTypes']['type'] == 'SMO') {{ 'selected' }} @endif>
                                SMO</option>
                            <option value="Other" @if (isset($project['projectTypes']['type']) && $project['projectTypes']['type'] == 'Other') {{ 'selected' }} @endif>
                                Other</option>
                        </select>
                    </div>
                    <div id="other-value" class="col-md-12 mb-3">
                        @if (isset($project['projectTypes']['type']) && $project['projectTypes']['type'] == 'Other')
                            <label for="inputEnterYourName" class="col-form-label">Other
                                Value <span style="color: red;">*</span></label>
                            <input type="text" name="other_value" id="other_value" required
                                data-parsley-trigger="keyup" class="form-control"
                                value="{{ $project['projectTypes']['name'] }}" placeholder="Enter Other Value">
                        @endif
                    </div>
                    {{-- Project value --}}
                    <div class="col-md-6 mb-3">
                        <label for="inputEnterYourName" class="col-form-label">Project
                            Value <span style="color: red;">*</span></label>
                        <input type="text" name="project_value" id="project_value" required
                            data-parsley-trigger="keyup" data-parsley-type="number"
                            data-parsley-type-message="Please enter a valid number." class="form-control"
                            value="{{ $project->project_value }}" placeholder="Enter Project Value">
                    </div>
                    {{-- Project project_upfront --}}
                    <div class="col-md-6 mb-3">
                        <label for="inputEnterYourName" class="col-form-label">Project
                            Upfront <span style="color: red;">*</span></label>
                        <input type="text" name="project_upfront" id="project_upfront" required
                            data-parsley-trigger="keyup" data-parsley-type="number"
                            data-parsley-type-message="Please enter a valid number." class="form-control"
                            value="{{ $project->project_upfront }}" placeholder="Enter Project Upfront">
                    </div>
                    {{-- currency select box --}}
                    <div class="col-md-6 mb-3">
                        <label for="inputEnterYourName" class="col-form-label">Currency
                            <span style="color: red;">*</span></label>
                        <select name="currency" id="currency" class="form-control" required
                            data-parsley-trigger="keyup">
                            <option value="">Select Currency</option>
                            <option value="INR" {{ $project->currency == 'INR' ? 'selected' : '' }}>
                                INR</option>
                            <option value="USD" {{ $project->currency == 'USD' ? 'selected' : '' }}>
                                USD</option>
                            <option value="EUR" {{ $project->currency == 'EUR' ? 'selected' : '' }}>
                                EUR</option>
                            <option value="GBP" {{ $project->currency == 'GBP' ? 'selected' : '' }}>
                                GBP</option>
                            <option value="AUD" {{ $project->currency == 'AUD' ? 'selected' : '' }}>
                                AUD</option>
                            <option value="CAD" {{ $project->currency == 'CAD' ? 'selected' : '' }}>
                                CAD</option>
                        </select>
                    </div>

                    {{-- Project payment_mode --}}
                    <div class="col-md-6 mb-3">
                        <label for="inputEnterYourName" class="col-form-label">Project
                            Payment Mode <span style="color: red;">*</span></label>
                        <input type="text" name="payment_mode" required data-parsley-trigger="keyup"
                            id="payment_mode" class="form-control" value="{{ $project->payment_mode }}"
                            placeholder="Enter Project Payment Mode">
                    </div>
                    {{-- Project opener --}}
                    <div class="col-md-6 mb-3">
                        <label for="inputEnterYourName" class="col-form-label">Project
                            Opener <span style="color: red;">*</span></label>
                        <select name="project_opener" id="project_opener" required class="form-control select2">
                            <option value="">Select Project
                                Opener
                            </option>
                            @foreach ($project_openers as $project_opener)
                                <option value="{{ $project_opener->id }}"
                                    {{ $project->project_opener == $project_opener->id ? 'selected' : '' }}>
                                    {{ $project_opener->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    {{-- Project closer --}}
                    <div class="col-md-6 mb-3">
                        <label for="inputEnterYourName" class="col-form-label">Project
                            Closer <span style="color: red;">*</span></label>
                        <select name="project_closer" id="project_closer" required class="form-control select2">
                            <option value="">Select Project
                                Closer
                            </option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}"
                                    {{ $project->project_closer == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    {{-- sale date --}}
                    <div class="col-md-6 mb-3">
                        <label for="inputEnterYourName" class="col-form-label">Sale Date
                            <span style="color: red;">*</span></label>
                        <input type="date" name="sale_date" id="edit_sale_date" required data-parsley-trigger="keyup"
                            data-parsley-type="date" data-parsley-type-message="Please enter a valid date."
                            class="form-control picker" value="{{ $project->sale_date }}" placeholder="Enter Sale Date">
                    </div>
                    {{-- website --}}
                    <div class="col-md-6 mb-3">
                        <label for="inputEnterYourName" class="col-form-label">Website</label>
                        <input type="text" name="website" id="website" data-parsley-required="false"
                            data-parsley-trigger="keyup" data-parsley-type="url"
                            data-parsley-type-message="Please enter a valid url." class="form-control"
                            value="{{ $project->website }}" placeholder="Enter Website">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="inputEnterYourName" class="col-form-label">Delivery
                            TAT
                            <span style="color: red;">*</span></label>
                        <input type="date" name="delivery_tat" id="edit_delivery_tat" required
                            data-parsley-trigger="keyup" data-parsley-type="date"
                            data-parsley-type-message="Please enter a valid date." class="form-control picker"
                            value="{{ $project->delivery_tat }}" placeholder="Enter Sale Date">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="inputEnterYourName" class="col-form-label">Assigned To <span
                                style="color: red;">*</span></label>
                        <select name="assigned_to" id="assigned_to " required class="form-control select2" required>
                            <option value="">Select user
                            </option>
                            @foreach ($account_managers as $account_manager)
                                <option value="{{ $account_manager->id }}"
                                    {{ $project->assigned_to == $account_manager->id ? 'selected' : '' }}>
                                    {{ $account_manager->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    {{-- comment --}}
                    <div class="col-md-12 mb-3">
                        <label for="inputEnterYourName" class="col-form-label">Comment</label>
                        <textarea name="comment" id="comment" data-parsley-trigger="keyup" class="form-control"
                            placeholder="Enter Comment">{{ $project->comment }}</textarea>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="inputEnterYourName" class="col-form-label">No. of
                            Milestone</label>
                        <input type="number" id="number_of_milestone_edit" min="0"
                            value="{{ $project->projectMilestones->count() }}" required
                            name="number_of_milestone_edit" class="form-control">
                    </div>
                    <div class="col-md-12 mb-3" style="margin-top:40px;">
                        <button type="button" class="btn px-5 submit-btn edit-milestone-print">Process</button>
                    </div>

                    <input type="hidden" value="{{ $project->payment_type }}" name="payment_types">
                    <h3 class="mt-4 text-uppercase">Milestone</h3>
                    <hr>
                    <div class="edit-milestone">
                    </div>

                        @if ($project->projectMilestones->count() > 0)
                            @foreach ($project->projectMilestones as $key => $milestone)
                                <div class="row">
                                    <div class="col-md-12  mb-3">
                                        <div style="display: flex">
                                            <input type="text" name="milestone_name[]" class="form-control"
                                                required data-parsley-trigger="keyup" placeholder="Milestone name"
                                                value="{{ $milestone->milestone_name }}" id="" required>
                                        </div>
                                    </div>
                                    <div class="col-md-12  mb-3">
                                        <div style="display: flex">
                                            <input type="text" name="milestone_value[]" class="form-control"
                                                value="{{ $milestone->milestone_value }}"
                                                placeholder="Milestone value" data-parsley-trigger="keyup"
                                                data-parsley-type="number"
                                                data-parsley-type-message="Please enter a valid number."
                                                id="" required>
                                        </div>
                                    </div>
                                    <div class="col-md-12  mb-3">
                                        <div style="display: flex">
                                            <select name="payment_status[]" id="payment" class="form-control payment-fetch"
                                                data-parsley-trigger="keyup" data-id="{{$key}}">
                                                <option value="Paid"
                                                    {{ $milestone->payment_status == 'Paid' ? 'selected' : '' }}>
                                                    Paid</option>
                                                <option value="Due"
                                                    {{ $milestone->payment_status == 'Due' ? 'selected' : '' }}>
                                                    Due</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="fetch-payment-hide-{{ $key }}">
                                        @if($milestone->payment_status=='Paid')
                                        {{-- <div id="for-paid" style="display:none;"> --}}                              
                                            
                                            <div class="col-md-12 mb-3">
                                                <div style="display: flex">
                                                    <input type="date" name="milestone_payment_date[]"
                                                        class="form-control picker" value="{{ $milestone->payment_date }}"
                                                        required data-parsley-trigger="keyup" id="fetch-milestone-date-{{ $key }}">
                                                </div>
                                            </div>
                                            <div class="col-md-12 mb-3">
                                                <div style="display: flex">
                                                    <input type="text" name="milestone_payment_mode[]"
                                                        class="form-control" value="{{ $milestone->payment_mode }}"
                                                    required data-parsley-trigger="keyup"
                                                        placeholder="Milestone payment mode" id="fetch-milestone-mode-{{ $key }}">
                                                </div>
                                            </div>
                                        @else
                                        
                                        @endif
                                    </div>
                                    {{-- </div> --}}
                                    {{-- <div class="col-md-12  mb-3">
                                        <div style="display: flex">
                                            <input type="date" name="payment_date[]"
                                                class="form-control"
                                                value="{{ $milestone->payment_date }}">
                                        </div>
                                    </div> --}}
                                    <div class="col-md-12  mb-3">
                                        <div style="display: flex">
                                            <textarea name="milestone_comment[]" class="form-control" placeholder="Milestone Comment" id=""
                                                cols="3" rows="2">{{ $milestone->milestone_comment }}</textarea>
                                        </div>
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        @if ($milestone->payment_status == 'Paid')
                                            <button type="button" class="btn btn-danger remove" disabled><i
                                                    class="fas fa-minus"></i>
                                                Remove</button>
                                        @else
                                            <button type="button" class="btn btn-danger remove"><i
                                                    class="fas fa-minus"></i>
                                                Remove</button>
                                        @endif

                                    </div>
                                </div>
                            @endforeach
                        @endif
                    
                    <h3 class="mt-4 text-uppercase">Upload PDF</h3>
                    <hr>
                    <div class="edit-pdf">

                        <div class="row">
                            <div class="col-md-12  mb-3">
                                <div style="display: flex">
                                    <input type="file" name="pdf[]" class="form-control" value=""
                                        data-parsley-required="false" data-parsley-trigger="keyup"
                                        accept="application/pdf" id="">
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <button type="button" class="btn px-5 submit-btn edit-pdf-button good-button"><i
                                        class="fas fa-plus"></i> Add PDF</button>
                            </div>
                        </div>

                        {{-- </br> --}}
                    </div>
                    @if ($project->projectDocuments->count() > 0)
                        <div class="row">
                            <h4 class="mt-4 text-uppercase">Download Documents</h4>
                            @foreach ($project->projectDocuments as $key => $document)
                                <div class="col-1 mb-3 download-button">
                                    <a href="{{ route('sales-projects.document.download', $document->id) }}"
                                        download="downloaded_project_documents.pdf">
                                        <button type="button" class="btn submit-btn add-pdf-button good-button">
                                            <i class="fas fa-download"></i>
                                        </button>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @endif

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
                html += '<div class="col-md-12  mb-3">';
                html += '<div style="display: flex">';
                html +=
                    '<input type="text" name="milestone_name[]" class="form-control" value="" placeholder="Milestone name" id="" required data-parsley-trigger="keyup">';
                html += '</div>';
                html += '</div>';
                html += '<div class="col-md-12  mb-3">';
                html += '<div style="display: flex">';
                html +=
                    '<input type="text" name="milestone_value[]" class="form-control" value="" placeholder="Milestone value" id="" required data-parsley-trigger="keyup" data-parsley-type="number" data-parsley-type-message="Please enter a valid number.">';
                html += '</div>';
                html += '</div>';
                html += '<div class="col-md-12  mb-3">';
                html += '<div style="display: flex">';
                html +=
                    '<select name="payment_status[]" id="payment_status" class="form-control payment" required data-parsley-trigger="keyup"><option value="" disabled >Select Payment Status</option><option value="Paid">Paid</option><option value="Due" selected>Due</option></select>';
                html += '</div>';
                html += '</div>';
                html += '<div class="col-md-12 mb-3">';
                html += '<div style="display: flex">';
                html +=
                    '<input type="date" name="milestone_payment_date[]" class="form-control picker" value="" id="" required data-parsley-trigger="keyup">';
                html += '</div>';
                html += '</div>';
                html += '<div class="col-md-12 mb-3">';
                html += '<div style="display: flex">';
                html +=
                    '<input type="text" name="milestone_payment_mode[]" class="form-control" value="" id="" placeholder="Milestone payment mode" required data-parsley-trigger="keyup">';
                html += '</div>';
                html += '</div>';
                // html += '<div class="col-md-12  mb-3">';
                // html += '<div style="display: flex">';
                // html +=
                //     '<input type="date" name="payment_date[]" class="form-control" value="" id="" required data-parsley-trigger="keyup">';
                // html += '</div>';
                // html += '</div>';
                html += '<div class="col-md-12  mb-3">';
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
                $('.edit-milestone').append(html);
            });
            $(document).on('click', '.remove', function() {
                $(this).closest('.row').remove();
                var number_of_milestone_edit = $('#number_of_milestone_edit').val();
                var new_number_of_milestone = number_of_milestone_edit - 1;
                $('#number_of_milestone_edit').val(new_number_of_milestone);
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
        $('.edit-pdf-button').click(function() {
            var html = '';
            html += '<div class="row">';
            html += '<div class="col-md-12  mb-3">';
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
            $('.edit-pdf').append(html);
        });
        $(document).on('click', '.remove-pdf', function() {
            $(this).closest('.row').remove();
        });
    </script>

    <script>
        $(document).ready(function() {
              $('.select2').each(function() {
                $(this).select2({
                    dropdownParent: $(this).parent()
                });
            })
            $('.calculate_date').on('click', function() {
                $('#fetch_month').html('');
                var startDate = new Date($('#start_date').val());
                var endDate = new Date($('#end_date').val());
                var project_value = $('#project_value').val();

                //validation start date and end date
                $('#end_date').next('span').remove();
                $('#start_date').next('span').remove();
                $('#project_value').next('span').remove();
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
                if (startDate == 'Invalid Date') {
                    $('#start_date').after(
                        '<span class="error" style="color:red;">Start date is required</span>');
                    return false;
                }
                if (endDate == 'Invalid Date') {
                    $('#end_date').after(
                        '<span class="error" style="color:red;">End date is required</span>');
                    return false;
                }

                $('#loading').addClass('loading');
                $('#loading-content').addClass('loading-content');
                // count month between two dates
                var months = (endDate.getFullYear() - startDate.getFullYear()) * 12;
                months -= startDate.getMonth();
                months += endDate.getMonth();
                months = months <= 0 ? 0 : months;
                var total_count = months + 1;
                var projectmilestone = '{{ $project->projectMilestones->count() }}';
                var total = total_count - projectmilestone;
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
                    html += '<div class="col-md-12  mb-3">';
                    html += '<div style="display: flex">';
                    html +=
                        '<input type="text" name="milestone_value[]" class="form-control" placeholder="Milestone value" id="" required data-parsley-trigger="keyup" data-parsley-type="number" data-parsley-type-message="Please enter a valid number.">';
                    html += '</div>';
                    html += '</div>';
                    html += '<div class="col-md-12  mb-3">';
                    html += '<div style="display: flex">';
                    html +=
                        '<select name="payment_status[]" id="payment_status" class="form-control" required data-parsley-trigger="keyup"><option value="" disabled >Select Payment Status</option><option value="Paid">Paid</option><option value="Due" selected>Due</option></select>';
                    html += '</div>';
                    html += '</div>';
                    html += '<div class="col-md-12 mb-3">';
                    html += '<div style="display: flex">';
                    html +=
                        '<input type="date" name="milestone_payment_date[]" class="form-control picker" value="" id="" required data-parsley-trigger="keyup">';
                    html += '</div>';
                    html += '</div>';
                    html += '<div class="col-md-12 mb-3">';
                    html += '<div style="display: flex">';
                    html +=
                        '<input type="text" name="milestone_payment_mode[]" class="form-control" value="" placeholder="Milestone payment mode" id="" required data-parsley-trigger="keyup">';
                    html += '</div>';
                    html += '</div>';
                    // html += '<div class="col-md-12  mb-3">';
                    // html += '<div style="display: flex">';
                    // html +=
                    //     '<input type="date" name="payment_date[]" class="form-control"  id="" required data-parsley-trigger="keyup" >';
                    // html += '</div>';
                    // html += '</div>';
                    html += '<div class="col-md-12  mb-3">';
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
        $('.edit-milestone-print').on('click', function() {
            
            var number_of_milestone_edit = $('#number_of_milestone_edit').val();
            if (number_of_milestone_edit == '') {
                $('#number_of_milestone_edit').html('');
                $('#number_of_milestone_edit').after(
                    '<span class="error" style="color:red;">Number of milestone is required</span>');
                return false;
            }
            //count .edit-milestone div and show in number_of_milestone_edit field
            var count = $('.edit-milestone').children().length;
            console.log(number_of_milestone_edit, count);
            if (count > 0) {
                if (number_of_milestone_edit > count) {
                    var new_number_of_milestone = number_of_milestone_edit - count;
                    $('#number_of_milestone_edit').html('');
                } else {
                    $('#number_of_milestone_edit').after(
                        '<span class="error" style="color:red;">Please enter valid number of milestone</span>')
                    return false;
                }
            } else {
                $('#number_of_milestone_edit').html('');
                $('.edit-milestone').html('');
                var new_number_of_milestone = number_of_milestone_edit;
            }
            console.log(new_number_of_milestone);

            // show milestone field as per number of milestone
            for (let index = 1; index <= new_number_of_milestone; index++) {
                // console.log(number_of_milestone_edit);
                var html = '';
                html += '<div class="row">';
                html += '<div class="col-md-12  mb-3">';
                html += '<div style="display: flex">';
                html +=
                    '<input type="text" name="milestone_name[]" class="form-control" value="" placeholder="Milestone name" id="" required data-parsley-trigger="keyup">';
                html += '</div>';
                html += '</div>';
                html += '<div class="col-md-12  mb-3">';
                html += '<div style="display: flex">';
                html +=
                    '<input type="text" name="milestone_value[]" class="form-control" value="" placeholder="Milestone value" id="" required data-parsley-trigger="keyup" data-parsley-type="number" data-parsley-type-message="Please enter a valid number.">';
                html += '</div>';
                html += '</div>';
                html += '<div class="col-md-12  mb-3">';
                html += '<div style="display: flex">';
                html +=
                    '<select name="payment_status[]" id="edit_payment_status" class="form-control payment-status" data-id="' +
                        index +
                        '" required data-parsley-trigger="keyup"><option value="" disabled >Select Payment Status</option><option value="Paid">Paid</option><option value="Due" selected>Due</option></select>';
                html += '</div>';
                html += '</div>';
                html += '<div class="edit-payment-hide-'+index+'" style="display:none;">';
                html += '<div class="col-md-12 mb-3">';
                html += '<div style="display: flex">';
                html +=
                    '<input type="date" name="milestone_payment_date[]" class="form-control picker" value="" id="edit-milestone-date-'+index+'"  data-parsley-trigger="keyup">';
                html += '</div>';
                html += '</div>';
                html += '<div class="col-md-12 mb-3">';
                html += '<div style="display: flex">';
                html +=
                    '<input type="text" name="milestone_payment_mode[]" class="form-control" value="" id="edit-milestone-mode-'+index+'" placeholder="Milestone payment mode"  data-parsley-trigger="keyup">';
                html += '</div>';
                html += '</div>';
                html += '</div>';
                // html += '<div class="col-md-12  mb-3">';
                // html += '<div style="display: flex">';
                // html +=
                //     '<input type="date" name="payment_date[]" class="form-control" value="" id="" required data-parsley-trigger="keyup">';
                // html += '</div>';
                // html += '</div>';
                html += '<div class="col-md-12  mb-3">';
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
                //
                $('.edit-milestone').append(html);

                //count .edit-milestone div and show in number_of_milestone_edit field

            }
        });
    </script>
   
    <script>
        $(document).ready(function() {
            // Attach an event listener to the Sale Date input
            $('#edit_sale_date').on('change', function() {
                // Get the selected Sale Date
                var saleDate = $(this).val();
    
                // Set the minimum date for Delivery TAT to be one day after the Sale Date
                $('#edit_delivery_tat').attr('min', incrementDate(saleDate, 1));
            });
    
            // Function to increment date by a specified number of days
            function incrementDate(date, days) {
                var result = new Date(date);
                result.setDate(result.getDate() + days);
                return result.toISOString().split('T')[0];
            }
        });
    </script>

<script>
   
    $(document).on('change', '.edit_payment_status', function() {
       
        var id = $(this).data('id');
        var payment_status = $(this).val();
       
        // $('#milestone_payment_date').prop('required', false);
        $('#edit-milestone-date-'+id).prop('required', false);
        $('#edit-milestone-mode-'+id).prop('required', false);
        if (payment_status == 'Paid') {
            $('.edit-payment-hide-'+id).show();
            $('#edit-milestone-date-'+id).prop('required', true);
            $('#edit-milestone-mode-'+id).prop('required', true);
        } else {
            $('.edit-payment-hide-'+id).hide();
            $('#edit-milestone-date-'+id).prop('required', false);
            $('#edit-milestone-mode-'+id).prop('required', false);
        }
    });

</script>

<script>
    $(document).on('change','.payment-fetch', function(){
        var id = $(this).data('id');
        var payment_status = $(this).val();
        console.log(payment_status);
        $('#fetch-milestone-date-'+id).prop('required', false);
        $('#fetch-milestone-mode-'+id).prop('required', false);
        if (payment_status == 'Paid') {
            console.log(payment_status);
            // $('.fetch-payment-hide-'+id).show();
            $('.fetch-payment-hide-'+id).html('<div class="col-md-12 mb-3"><div style="display: flex"><input type="date" name="milestone_payment_date[]" class="form-control picker" value="" required data-parsley-trigger="keyup" id="fetch-milestone-date-'+id+'"></div></div><div class="col-md-12 mb-3"><div style="display: flex"> <input type="text" name="milestone_payment_mode[]"class="form-control" value="" required data-parsley-trigger="keyup" placeholder="Milestone payment mode" id="fetch-milestone-mode-'+id+'"></div></div>');
            $('#fetch-milestone-date-'+id).prop('required', true);
            $('#fetch-milestone-mode-'+id).prop('required', true);
        } else {
            // $('.fetch-payment-hide-'+id).hide();
            $('.fetch-payment-hide-'+id).html('');
            $('#fetch-milestone-date-'+id).prop('required', false);
            $('#fetch-milestone-mode-'+id).prop('required', false);
        }
    });
    </script>
@endif
