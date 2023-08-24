@extends('admin.layouts.master')
@section('title')
    {{ env('APP_NAME') }} | Create Project
@endsection
@push('styles')
@endpush

@section('content')
    <div class="page-wrapper">

        <div class="content container-fluid">

            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Create</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('sales-projects.index') }}">Projects</a></li>
                            <li class="breadcrumb-item active">Create Project</li>
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
                                <h3 class="mb-0 text-uppercase">Create A Project</h3>
                                <hr>
                                <div class="card border-0 border-4">
                                    <div class="card-body">
                                        <form action="{{ route('sales-projects.store') }}" method="post" data-parsley-validate=""
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="border p-4 rounded">
                                                <div class="row">
                                                    {{-- salemangers select option --}}
                                                    <div class="col-md-6">
                                                        <label for="inputEnterYourName" class="col-form-label"> Sales Manager
                                                            <span style="color: red;">*</span></label>
                                                        <select name="user_id" id="user_id" required data-parsley-trigger="keyup" class="form-control" >
                                                            <option value="">Select a sales manager</option>
                                                            @foreach ($sales_managers as $sales_manager)
                                                                <option value="{{ $sales_manager->id }}">{{ $sales_manager->name }} ({{ $sales_manager->email }})</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="inputEnterYourName" class="col-form-label"> Client Name
                                                            <span style="color: red;">*</span></label>
                                                        <input type="text" name="client_name" id="client_name" required data-parsley-trigger="keyup"
                                                            class="form-control" value="{{ old('client_name') }}"
                                                            placeholder="Enter Client Name">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="inputEnterYourName" class="col-form-label">Business Name
                                                            <span style="color: red;">*</span></label>
                                                        <input type="text" name="business_name" id="business_name" required data-parsley-trigger="keyup"
                                                            class="form-control" value="{{ old('business_name') }}"
                                                            placeholder="Enter Business Name">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="inputEnterYourName" class="col-form-label">Client Email
                                                            <span style="color: red;">*</span></label>
                                                        <input type="text" name="client_email" id="client_email" required data-parsley-trigger="keyup" data-parsley-type="email" data-parsley-type-message="Please enter a valid email address." 
                                                            class="form-control" value="{{ old('client_email') }}"
                                                            placeholder="Enter Client Email">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="inputEnterYourName" class="col-form-label">Client Phone
                                                            <span style="color: red;">*</span></label>
                                                        <input type="text" name="client_phone" id="client_phone" required data-parsley-trigger="keyup" data-parsley-type="number" data-parsley-type-message="Please enter a valid phone number."
                                                            class="form-control" value="{{ old('client_phone') }}"
                                                            placeholder="Enter Client Phone Number">
                                                    </div>

                                                    {{-- clinent address --}}
                                                    <div class="col-md-6">
                                                        <label for="inputEnterYourName" class="col-form-label">Client
                                                            Address <span style="color: red;">*</span></label>
                                                        <input type="text" name="client_address" id="client_address" required data-parsley-trigger="keyup" 
                                                            class="form-control" value="{{ old('client_address') }}"
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
                                                        <select name="project_type[]" id="project_type" required data-parsley-trigger="keyup"
                                                            class="form-control select2" multiple>
                                                            <option value="" disabled>Select Project Type</option>
                                                            <option value="Website Design & Development">Website Design &
                                                                Development</option>
                                                            <option value="Mobile Application Development">Mobile
                                                                Application Development</option>
                                                            <option value="Digital Marketing">Digital Marketing</option>
                                                            <option value="Logo Design">Logo Design</option>
                                                            <option value="SEO">SEO</option>
                                                            <option value="SMO">SMO</option>
                                                            <option value="Other">Other</option>
                                                        </select>
                                                    </div>
                                                    <div id="other-value" class="col-md-6">

                                                    </div>
                                                    {{-- Project value --}}
                                                    <div class="col-md-6">
                                                        <label for="inputEnterYourName" class="col-form-label">Project
                                                            Value <span style="color: red;">*</span></label>
                                                        <input type="text" name="project_value" id="project_value" required data-parsley-trigger="keyup" data-parsley-type="number" data-parsley-type-message="Please enter a valid number."
                                                            class="form-control" value="{{ old('project_value') }}"
                                                            placeholder="Enter Project Value">
                                                    </div>
                                                    {{-- Project project_upfront --}}
                                                    <div class="col-md-6">
                                                        <label for="inputEnterYourName" class="col-form-label">Project
                                                            Upfront <span style="color: red;">*</span></label>
                                                        <input type="text" name="project_upfront" id="project_upfront" required data-parsley-trigger="keyup" data-parsley-type="number" data-parsley-type-message="Please enter a valid number."
                                                            class="form-control" value="{{ old('project_upfront') }}"
                                                            placeholder="Enter Project Upfront">
                                                    </div>
                                                    {{-- currency select box --}}
                                                    <div class="col-md-6">
                                                        <label for="inputEnterYourName" class="col-form-label">Currency
                                                            <span style="color: red;">*</span></label>
                                                        <select name="currency" id="currency" class="form-control" required data-parsley-trigger="keyup">
                                                            <option value="" disabled>Select Currency</option>
                                                            <option value="INR">INR</option>
                                                            <option value="USD">USD</option>
                                                            <option value="EUR">EUR</option>
                                                            <option value="GBP">GBP</option>
                                                            <option value="AUD">AUD</option>
                                                            <option value="CAD">CAD</option>
                                                        </select>
                                                    </div>

                                                    {{-- Project payment_mode --}}
                                                    <div class="col-md-6">
                                                        <label for="inputEnterYourName" class="col-form-label">Project
                                                            Payment Mode <span style="color: red;">*</span></label>
                                                        <input type="text" name="payment_mode" required data-parsley-trigger="keyup"
                                                            id="payment_mode" class="form-control"
                                                            value="{{ old('payment_mode') }}"
                                                            placeholder="Enter Project Payment Mode">
                                                    </div>
                                                    {{-- Project opener --}}
                                                    <div class="col-md-6">
                                                        <label for="inputEnterYourName" class="col-form-label">Project
                                                            Opener <span style="color: red;">*</span></label>
                                                        <input type="text" name="project_opener" id="project_opener" required data-parsley-trigger="keyup"
                                                            class="form-control" value="{{ old('project_opener') }}"
                                                            placeholder="Enter Project Opener">
                                                    </div>
                                                    {{-- Project closer --}}
                                                    <div class="col-md-6">
                                                        <label for="inputEnterYourName" class="col-form-label">Project
                                                            Closer <span style="color: red;">*</span></label>
                                                        <input type="text" name="project_closer" id="project_closer" required data-parsley-trigger="keyup"
                                                            class="form-control" value="{{ old('project_closer') }}"
                                                            placeholder="Enter Project Closer">
                                                    </div>
                                                    {{-- sale date --}}
                                                    <div class="col-md-6">
                                                        <label for="inputEnterYourName" class="col-form-label">Sale Date
                                                            <span style="color: red;">*</span></label>
                                                        <input type="date" name="sale_date" id="sale_date" required data-parsley-trigger="keyup" max="{{ date('Y-m-d') }}"
                                                            class="form-control" value="{{ old('sale_date') }}"
                                                            placeholder="Enter Sale Date">
                                                    </div>
                                                    {{-- website --}}
                                                    <div class="col-md-6">
                                                        <label for="inputEnterYourName" class="col-form-label">Website</label>
                                                        <input type="text" name="website" id="website" data-parsley-required="false" data-parsley-trigger="keyup" data-parsley-type="url" data-parsley-type-message="Please enter a valid url."
                                                            class="form-control" value="{{ old('website') }}"
                                                            placeholder="Enter Website">
                                                    </div>
                                                    <h3 class="mt-4 text-uppercase">Milestone</h3>
                                                    <hr>
                                                    {{-- add more functionality for milestone --}}
                                                    <div class="add-milestone">

                                                        <div class="row">
                                                            <div class="col-md-4 pb-3">
                                                                <div style="display: flex">
                                                                    <input type="text" name="milestone_name[]" 
                                                                        class="form-control" value="" required  data-parsley-trigger="keyup"
                                                                        placeholder="Milestone name"
                                                                        id="" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4 pb-3">
                                                                <div style="display: flex">
                                                                    <input type="text" name="milestone_value[]"
                                                                        class="form-control" value=""
                                                                        placeholder="Milestone value" data-parsley-trigger="keyup" data-parsley-type="number" data-parsley-type-message="Please enter a valid number."
                                                                        id="" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <button type="button"
                                                                    class="btn btn-success add good-button"><i
                                                                        class="fas fa-plus"></i> Add Milestone</button>
                                                            </div>
                                                        </div>
                                                        {{-- </br> --}}
                                                    </div>
                                                    <div class="row" style="margin-top: 20px; float: left;">
                                                        <div class="col-sm-9">
                                                            <button type="submit"
                                                                class="btn px-5 submit-btn">Create</button>
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
        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>
    <script>
        // add more functionality for milestone
        $(document).ready(function() {
            $('.add').click(function() {
                var html = '';
                html += '<div class="row">';
                html += '<div class="col-md-4 pb-3">';
                html += '<div style="display: flex">';
                html += '<input type="text" name="milestone_name[]" class="form-control" value="" placeholder="Milestone name" id="" required data-parsley-trigger="keyup">';
                html += '</div>';
                html += '</div>';
                html += '<div class="col-md-4 pb-3">';
                html += '<div style="display: flex">';
                html += '<input type="text" name="milestone_value[]" class="form-control" value="" placeholder="Milestone value" id="" required data-parsley-trigger="keyup" data-parsley-type="number" data-parsley-type-message="Please enter a valid number.">';
                html += '</div>';
                html += '</div>';
                html += '<div class="col-md-4">';
                html += '<button type="button" class="btn btn-danger remove"><i class="fas fa-minus"></i> Remove</button>';
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
                    html += '<label for="inputEnterYourName" class="col-form-label">Other Value <span style="color: red;">*</span></label>';
                    html += '<input type="text" name="other_value" id="other_value" class="form-control" value="{{ old('other_value') }}" placeholder="Enter Other Value" required data-parsley-trigger="keyup">';
                    $('#other-value').html(html);
                } else {
                    $('#other-value').html('');
                } 
            });
        });
    </script>
@endpush
