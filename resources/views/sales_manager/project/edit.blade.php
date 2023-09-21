@extends('sales_manager.layouts.master')
@section('title')
    {{ env('APP_NAME') }} | Edit Project
@endsection
@push('styles')
@endpush

@section('content')
    <div class="page-wrapper">

        <div class="content container-fluid">

            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Edit</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('projects.index') }}">Projects</a></li>
                            <li class="breadcrumb-item active">Edit Project</li>
                        </ul>
                    </div>
                    <div class="col-auto float-end ms-auto">
                        {{-- <a href="#" class="btn add-btn" data-bs-toggle="modal" data-bs-target="#add_group"><i
                            class="fa fa-plus"></i> Add Project</a> --}}
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                        <div class="row">
                            <div class="col-xl-12 mx-auto">
                                <h3 class="mb-0 text-uppercase">Edit A Project</h3>
                                <hr>
                                <div class="card border-0 border-4">
                                    <div class="card-body">
                                        <form action="{{ route('projects.update', $project->id) }}" method="post"
                                            data-parsley-validate="" enctype="multipart/form-data">
                                            @method('PUT')
                                            @csrf
                                            <div class="border p-4 rounded">
                                                <div class="row">
                                                  
                                                    <div class="col-md-6">
                                                        <label for="inputEnterYourName" class="col-form-label"> Client Name
                                                            <span style="color: red;">*</span></label>
                                                        <input type="text" name="client_name" id="client_name" required
                                                            data-parsley-trigger="keyup" value="{{ $project->client_name }}"
                                                            class="form-control" placeholder="Enter Client Name">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="inputEnterYourName" class="col-form-label">Business Name
                                                            <span style="color: red;">*</span></label>
                                                        <input type="text" name="business_name" id="business_name"
                                                            required data-parsley-trigger="keyup"
                                                            value="{{ $project->business_name }}" class="form-control"
                                                            placeholder="Enter Business Name">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="inputEnterYourName" class="col-form-label">Client Email
                                                            <span style="color: red;">*</span></label>
                                                        <input type="text" name="client_email" id="client_email" required
                                                            data-parsley-trigger="keyup" data-parsley-type="email"
                                                            data-parsley-type-message="Please enter a valid email address."
                                                            class="form-control" value="{{ $project->client_email }}"
                                                            placeholder="Enter Client Email">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="inputEnterYourName" class="col-form-label">Client Phone
                                                            <span style="color: red;">*</span></label>
                                                        <input type="text" name="client_phone" id="client_phone" required
                                                            data-parsley-trigger="keyup" data-parsley-type="number"
                                                            data-parsley-type-message="Please enter a valid phone number."
                                                            value="{{ $project->client_phone }}" class="form-control"
                                                            placeholder="Enter Client Phone Number">
                                                    </div>

                                                    {{-- clinent address --}}
                                                    <div class="col-md-6">
                                                        <label for="inputEnterYourName" class="col-form-label">Client
                                                            Address <span style="color: red;">*</span></label>
                                                        <input type="text" name="client_address" id="client_address"
                                                            required data-parsley-trigger="keyup" class="form-control"
                                                            value=" {{ $project->client_address }}"
                                                            placeholder="Enter Address">
                                                        @if ($errors->has('address'))
                                                            <div class="error" style="color:red;">
                                                                {{ $errors->first('address') }}</div>
                                                        @endif
                                                    </div>
                                                    <h3 class="mt-4 text-uppercase">Project Details</h3>
                                                    <hr>
                                                    {{-- project type in select2 box --}}
                                                    <div class="col-md-6">
                                                        <label for="inputEnterYourName" class="col-form-label">Project
                                                            Type <span style="color: red;">*</span></label>
                                                            <select name="project_type" id="project_type" required
                                                            data-parsley-trigger="keyup" class="form-control">
                                                            <option value="" disabled>Select Project Type</option>
                                                            <option value="Website Design & Development"
                                                                @if ($project['projectTypes']['type'] == 'Website Design & Development') {{ 'selected' }} @endif>
                                                                Website Design & Development</option>
                                                            <option value="Mobile Application Development"
                                                                @if ($project['projectTypes']['type'] == 'Mobile Application Development') {{ 'selected' }} @endif>
                                                                Mobile Application Development</option>
                                                            <option value="Digital Marketing"
                                                                @if ($project['projectTypes']['type'] == 'Digital Marketing') {{ 'selected' }} @endif>
                                                                Digital Marketing</option>
                                                            <option value="Logo Design"
                                                                @if ($project['projectTypes']['type'] == 'Logo Design') {{ 'selected' }} @endif>
                                                                Logo Design</option>
                                                            <option value="SEO"
                                                                @if ($project['projectTypes']['type'] == 'SEO') {{ 'selected' }} @endif>
                                                                SEO</option>
                                                            <option value="SMO"
                                                                @if ($project['projectTypes']['type'] == 'SMO') {{ 'selected' }} @endif>
                                                                SMO</option>
                                                            <option value="Other"
                                                                @if ($project['projectTypes']['type'] == 'Other') {{ 'selected' }} @endif>
                                                                Other</option>
                                                        </select>
                                                    </div>
                                                    <div id="other-value" class="col-md-6">
                                                        @if ($project['projectTypes']['type'] == 'Other')
                                                            <label for="inputEnterYourName" class="col-form-label">Other
                                                                Value <span style="color: red;">*</span></label>
                                                            <input type="text" name="other_value" id="other_value"
                                                                required data-parsley-trigger="keyup" class="form-control"
                                                                value="{{ $project['projectTypes']['name'] }}"
                                                                placeholder="Enter Other Value">
                                                        @endif
                                                    </div>
                                                    {{-- Project value --}}
                                                    <div class="col-md-6">
                                                        <label for="inputEnterYourName" class="col-form-label">Project
                                                            Value <span style="color: red;">*</span></label>
                                                        <input type="text" name="project_value" id="project_value"
                                                            required data-parsley-trigger="keyup"
                                                            data-parsley-type="number"
                                                            data-parsley-type-message="Please enter a valid number."
                                                            class="form-control" value="{{ $project->project_value }}"
                                                            placeholder="Enter Project Value">
                                                    </div>
                                                    {{-- Project project_upfront --}}
                                                    <div class="col-md-6">
                                                        <label for="inputEnterYourName" class="col-form-label">Project
                                                            Upfront <span style="color: red;">*</span></label>
                                                        <input type="text" name="project_upfront" id="project_upfront"
                                                            required data-parsley-trigger="keyup"
                                                            data-parsley-type="number"
                                                            data-parsley-type-message="Please enter a valid number."
                                                            class="form-control" value="{{ $project->project_upfront }}"
                                                            placeholder="Enter Project Upfront">
                                                    </div>
                                                    {{-- currency select box --}}
                                                    <div class="col-md-6">
                                                        <label for="inputEnterYourName" class="col-form-label">Currency
                                                            <span style="color: red;">*</span></label>
                                                        <select name="currency" id="currency" class="form-control"
                                                            required data-parsley-trigger="keyup">
                                                            <option value="" disabled>Select Currency</option>
                                                            <option value="INR"
                                                                {{ $project->currency == 'INR' ? 'selected' : '' }}>
                                                                INR</option>
                                                            <option value="USD"
                                                                {{ $project->currency == 'USD' ? 'selected' : '' }}>
                                                                USD</option>
                                                            <option value="EUR"
                                                                {{ $project->currency == 'EUR' ? 'selected' : '' }}>
                                                                EUR</option>
                                                            <option value="GBP"
                                                                {{ $project->currency == 'GBP' ? 'selected' : '' }}>
                                                                GBP</option>
                                                            <option value="AUD"
                                                                {{ $project->currency == 'AUD' ? 'selected' : '' }}>
                                                                AUD</option>
                                                            <option value="CAD"
                                                                {{ $project->currency == 'CAD' ? 'selected' : '' }}>
                                                                CAD</option>
                                                        </select>
                                                    </div>

                                                    {{-- Project payment_mode --}}
                                                    <div class="col-md-6">
                                                        <label for="inputEnterYourName" class="col-form-label">Project
                                                            Payment Mode <span style="color: red;">*</span></label>
                                                        <input type="text" name="payment_mode" required
                                                            data-parsley-trigger="keyup" id="payment_mode"
                                                            class="form-control" value="{{ $project->payment_mode }}"
                                                            placeholder="Enter Project Payment Mode">
                                                    </div>
                                                    {{-- Project opener --}}
                                                    <div class="col-md-6">
                                                        <label for="inputEnterYourName" class="col-form-label">Project
                                                            Opener <span style="color: red;">*</span></label>
                                                        <input type="text" name="project_opener" id="project_opener"
                                                            required data-parsley-trigger="keyup" class="form-control"
                                                            value="{{ $project->project_opener }}""
                                                            placeholder="Enter Project Opener">
                                                    </div>
                                                    {{-- Project closer --}}
                                                    <div class="col-md-6">
                                                        <label for="inputEnterYourName" class="col-form-label">Project
                                                            Closer <span style="color: red;">*</span></label>
                                                        <input type="text" name="project_closer" id="project_closer"
                                                            required data-parsley-trigger="keyup" class="form-control"
                                                            value="{{ $project->project_closer }}"
                                                            placeholder="Enter Project Closer">
                                                    </div>
                                                    {{-- sale date --}}
                                                    <div class="col-md-6">
                                                        <label for="inputEnterYourName" class="col-form-label">Sale Date
                                                            <span style="color: red;">*</span></label>
                                                        <input type="date" name="sale_date" id="sale_date" required
                                                            data-parsley-trigger="keyup" data-parsley-type="date"
                                                            data-parsley-type-message="Please enter a valid date."
                                                            class="form-control" value="{{ $project->sale_date }}"
                                                            placeholder="Enter Sale Date">
                                                    </div>
                                                    {{-- website --}}
                                                    <div class="col-md-6">
                                                        <label for="inputEnterYourName"
                                                            class="col-form-label">Website</label>
                                                        <input type="text" name="website" id="website"
                                                            data-parsley-required="false" data-parsley-trigger="keyup"
                                                            data-parsley-type="url"
                                                            data-parsley-type-message="Please enter a valid url."
                                                            class="form-control" value="{{ $project->website }}"
                                                            placeholder="Enter Website">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="inputEnterYourName" class="col-form-label">Payment
                                                            Type</label>
                                                        <select name="payment_type" id="payment_type" disabled
                                                            class="form-control" required data-parsley-trigger="keyup">
                                                            <option value="Monthly"
                                                                {{ $project->payment_type == 'Monthly' ? 'selected' : '' }}>
                                                                Monthly</option>
                                                            <option value="Milestone"
                                                                {{ $project->payment_type == 'Milestone' ? 'selected' : '' }}>
                                                                Milestone</option>
                                                        </select>
                                                    </div>

                                                    <input type="hidden"  value="{{ $project->payment_type }}" name="payment_types">


                                                    @if ($project->projectMilestones->count() > 0 && $project->payment_type == 'Milestone')
                                                        <h3 class="mt-4 text-uppercase">Milestone</h3>
                                                        <hr>
                                                        <div class="add-milestone">
                                                            @foreach ($project->projectMilestones as $key => $milestone)
                                                                <div class="row">
                                                                    <div class="col-md-4 pb-3">
                                                                        <div style="display: flex">
                                                                            <input type="text" name="milestone_name[]"
                                                                                class="form-control" required
                                                                                data-parsley-trigger="keyup"
                                                                                placeholder="Milestone name"
                                                                                value="{{ $milestone->milestone_name }}"
                                                                                id="" required>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4 pb-3">
                                                                        <div style="display: flex">
                                                                            <input type="text" name="milestone_value[]"
                                                                                class="form-control"
                                                                                value="{{ $milestone->milestone_value }}"
                                                                                placeholder="Milestone value"
                                                                                data-parsley-trigger="keyup"
                                                                                data-parsley-type="number"
                                                                                data-parsley-type-message="Please enter a valid number."
                                                                                id="" required>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4 pb-3">
                                                                        <div style="display: flex">
                                                                            <select name="payment_status[]" id="payment"
                                                                                class="form-control"
                                                                                data-parsley-trigger="keyup">
                                                                                <option value="Paid"
                                                                                    {{ $milestone->payment_status == 'Paid' ? 'selected' : '' }}>
                                                                                    Paid</option>
                                                                                <option value="Due"
                                                                                    {{ $milestone->payment_status == 'Due' ? 'selected' : '' }}>
                                                                                    Due</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4 pb-3">
                                                                        <div style="display: flex">
                                                                            <input type="date" name="payment_date[]"
                                                                                class="form-control"
                                                                                value="{{ $milestone->payment_date }}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4 pb-3">
                                                                        <div style="display: flex">
                                                                            <textarea name="milestone_comment[]" class="form-control" placeholder="Milestone Comment" id=""
                                                                                cols="3" rows="2">{{ $milestone->milestone_comment }}</textarea>
                                                                        </div>
                                                                    </div>
                                                                    @if ($key == 0)
                                                                        <div class="col-md-4">
                                                                            <button type="button"
                                                                                class="btn btn-success add good-button"><i
                                                                                    class="fas fa-plus"></i> Add
                                                                                Milestone</button>
                                                                        </div>
                                                                    @else
                                                                    <div class="col-md-4">
                                                                        @if($milestone->payment_status == 'Paid')                                                                       
                                                                        <button type="button"
                                                                        class="btn btn-danger remove" disabled><i
                                                                            class="fas fa-minus"></i>
                                                                        Remove</button>                                                         
                                                                        @else
                                                                            <button type="button"
                                                                            class="btn btn-danger remove"><i
                                                                                class="fas fa-minus"></i>
                                                                            Remove</button>
                                                                        @endif    
                                                                        
                                                                    </div>
                                                                    @endif
                                                                </div>
                                                            @endforeach

                                                        </div>
                                                    @else
                                                        <h3 class="mt-4 text-uppercase" id="monthly_hd">Monthly</h3>
                                                        <hr>

                                                        <div class="row">
                                                            <div class="col-md-4 pb-3">
                                                                <div style="display: flex">
                                                                    <input placeholder="Start Date" name="start_date"
                                                                        class="form-control textbox-n"
                                                                        value="{{ $project->projectTypes->start_date }}"
                                                                        type="text" onfocus="(this.type='date')"
                                                                        id="start_date" required readonly/>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4 pb-3">
                                                                <div style="display: flex">
                                                                    {{-- <input type="date" id="end_date" name="end_date"
                                                                        class="form-control" required> --}}
                                                                    <input placeholder="End Date" name="end_date"
                                                                        class="form-control textbox-n"
                                                                        value="{{ $project->projectTypes->end_date }}"
                                                                        type="text" onfocus="(this.type='date')"
                                                                        id="end_date" required />
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <button type="button"
                                                                    class="btn btn-success calculate_date good-button">Process</button>
                                                            </div>
                                                            
                                                            @if ($project->projectMilestones->count() > 0 && $project->payment_type == 'Monthly')
                                                                @foreach ($project->projectMilestones as $key => $monthly)
                                                                    <div class="row">
                                                                        <div class="col-md-4 pb-3">
                                                                            <div style="display: flex">
                                                                                <input type="text"
                                                                                    name="milestone_value[]"
                                                                                    class="form-control"
                                                                                    value="{{ $monthly->milestone_value }}"
                                                                                    placeholder="Milestone value"
                                                                                    id="" required
                                                                                    data-parsley-trigger="keyup"
                                                                                    data-parsley-type="number"
                                                                                    data-parsley-type-message="Please enter a valid number.">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4 pb-3">
                                                                            <div style="display: flex">
                                                                                <select name="payment_status[]"
                                                                                    id="payment_status"
                                                                                    class="form-control" required
                                                                                    data-parsley-trigger="keyup">
                                                                                    <option value="Paid"
                                                                                        {{ $monthly->payment_status == 'Paid' ? 'selected' : '' }}>
                                                                                        Paid</option>
                                                                                    <option value="Due"
                                                                                        {{ $monthly->payment_status == 'Due' ? 'selected' : '' }}>
                                                                                        Due</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4 pb-3">
                                                                            <div style="display: flex">

                                                                                <input type="date"
                                                                                    name="payment_date[]"
                                                                                    class="form-control"
                                                                                    value="{{ $monthly->payment_date }}"
                                                                                    required data-parsley-trigger="keyup">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4 pb-3">
                                                                            <div style="display: flex">

                                                                                <textarea name="milestone_comment[]" class="form-control" placeholder="Milestone Comment" id=""
                                                                                    cols="3" rows="2">{{ $monthly->milestone_comment }}</textarea>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            @if($monthly->payment_status == 'Paid')                                                                       
                                                                            <button type="button"
                                                                            class="btn btn-danger remove" disabled><i
                                                                                class="fas fa-minus"></i>
                                                                            Remove</button>                                                         
                                                                            @else
                                                                                <button type="button"
                                                                                class="btn btn-danger remove"><i
                                                                                    class="fas fa-minus"></i>
                                                                                Remove</button>
                                                                            @endif    
                                                                            
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            @endif
                                                            <div id="fetch_month">
                                                            </div>
                                                        </div>

                                                    @endif


                                                    <h3 class="mt-4 text-uppercase">Upload PDF</h3>
                                                    <hr>
                                                    <div class="add-pdf">

                                                        <div class="row">
                                                            <div class="col-md-4 pb-3">
                                                                <div style="display: flex">
                                                                    <input type="file" name="pdf[]"
                                                                        class="form-control" value=""
                                                                        data-parsley-required="false"
                                                                        data-parsley-trigger="keyup"
                                                                        accept="application/pdf" id="">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <button type="button"
                                                                    class="btn btn-success add-pdf-button good-button"><i
                                                                        class="fas fa-plus"></i> Add PDF</button>
                                                            </div>
                                                        </div>
                                                        {{-- </br> --}}
                                                    </div>
                                                    {{-- add more functionality for milestone --}}
                                                    <div class="row" style="margin-top: 20px; float: left;">
                                                        <div class="col-sm-9">
                                                            <button type="submit"
                                                                class="btn px-5 submit-btn">Update</button>
                                                        </div>
                                                    </div>
                                                </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection

@push('scripts')
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
            html += '<div class="col-md-4 pb-3">';
            html += '<div style="display: flex">';
            html +=
                '<input type="text" name="milestone_name[]" class="form-control" value="" placeholder="Milestone name" id="" required data-parsley-trigger="keyup">';
            html += '</div>';
            html += '</div>';
            html += '<div class="col-md-4 pb-3">';
            html += '<div style="display: flex">';
            html +=
                '<input type="text" name="milestone_value[]" class="form-control" value="" placeholder="Milestone value" id="" required data-parsley-trigger="keyup" data-parsley-type="number" data-parsley-type-message="Please enter a valid number.">';
            html += '</div>';
            html += '</div>';
            html += '<div class="col-md-4 pb-3">';
            html += '<div style="display: flex">';
            html +=
                '<select name="payment_status[]" id="payment_status" class="form-control" required data-parsley-trigger="keyup"><option value="" disabled >Select Payment Status</option><option value="Paid">Paid</option><option value="Due" selected>Due</option></select>';
            html += '</div>';
            html += '</div>';
            html += '<div class="col-md-4 pb-3">';
            html += '<div style="display: flex">';
            html +=
                '<input type="date" name="payment_date[]" class="form-control" value="" id="" required data-parsley-trigger="keyup">';
            html += '</div>';
            html += '</div>';
            html += '<div class="col-md-4 pb-3">';
            html += '<div style="display: flex">';
            html +=
                '<textarea name="milestone_comment[]" class="form-control" placeholder="Milestone Comment" id="" cols="3" rows="2" ></textarea>';
            html += '</div>';
            html += '</div>';
            html += '<div class="col-md-4">';
            html +=
                '<button type="button" class="btn btn-danger remove"><i class="fas fa-minus"></i> Remove</button>';
            html += '</div>';
            html += '</div>';
            $('.add-milestone').append(html);
        });
        $(document).on('click', '.remove', function() {
            $(this).closest('.row').remove();
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
        html += '<div class="col-md-4 pb-3">';
        html += '<div style="display: flex">';
        html +=
            '<input type="file" name="pdf[]" class="form-control" value="" id="" data-parsley-required="false" data-parsley-trigger="keyup" accept="application/pdf">';
        html += '</div>';
        html += '</div>';
        html += '<div class="col-md-4">';
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
    $(document).ready(function() {
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
                html += '<div class="col-md-4 pb-3">';
                html += '<div style="display: flex">';
                html +=
                    '<input type="text" name="milestone_value[]" class="form-control" placeholder="Milestone value" id="" required data-parsley-trigger="keyup" data-parsley-type="number" data-parsley-type-message="Please enter a valid number.">';
                html += '</div>';
                html += '</div>';
                html += '<div class="col-md-4 pb-3">';
                html += '<div style="display: flex">';
                html +=
                    '<select name="payment_status[]" id="payment_status" class="form-control" required data-parsley-trigger="keyup"><option value="" disabled >Select Payment Status</option><option value="Paid">Paid</option><option value="Due" selected>Due</option></select>';
                html += '</div>';
                html += '</div>';
                html += '<div class="col-md-4 pb-3">';
                html += '<div style="display: flex">';
                html +=
                    '<input type="date" name="payment_date[]" class="form-control"  id="" required data-parsley-trigger="keyup" >';
                html += '</div>';
                html += '</div>';
                html += '<div class="col-md-4 pb-3">';
                html += '<div style="display: flex">';
                html +=
                    '<textarea name="milestone_comment[]" class="form-control" placeholder="Milestone Comment" id="" cols="3" rows="2" ></textarea>';
                html += '</div>';
                html += '</div>';
                html += '<div class="col-md-4">';
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
@endpush
