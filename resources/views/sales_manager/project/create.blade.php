@extends('sales_manager.layouts.master')
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
                            <li class="breadcrumb-item"><a href="{{ route('projects.index') }}">Projects</a></li>
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
                                <h6 class="mb-0 text-uppercase">Create A Project</h6>
                                <hr>
                                <div class="card border-0 border-4">
                                    <div class="card-body">
                                        <form action="{{ route('projects.store') }}" method="post"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="border p-4 rounded">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label for="inputEnterYourName" class="col-form-label"> Client Name
                                                            <span style="color: red;">*</span></label>
                                                        <input type="text" name="client_name" id="client_name"
                                                            class="form-control" value="{{ old('client_name') }}"
                                                            placeholder="Enter Client Name">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="inputEnterYourName" class="col-form-label">Business Name
                                                            <span style="color: red;">*</span></label>
                                                        <input type="text" name="business_name" id="business_name"
                                                            class="form-control" value="{{ old('business_name') }}"
                                                            placeholder="Enter Business Name">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="inputEnterYourName" class="col-form-label">Client Email
                                                            <span style="color: red;">*</span></label>
                                                        <input type="text" name="client_email" id="client_email"
                                                            class="form-control" value="{{ old('client_email') }}"
                                                            placeholder="Enter Client Email">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="inputEnterYourName" class="col-form-label">Client Phone
                                                            <span style="color: red;">*</span></label>
                                                        <input type="text" name="client_phone" id="client_phone"
                                                            class="form-control" value="{{ old('client_phone') }}"
                                                            placeholder="Enter Client Phone Number">
                                                    </div>

                                                    {{-- clinent address --}}
                                                    <div class="col-md-6">
                                                        <label for="inputEnterYourName" class="col-form-label">Client
                                                            Address <span style="color: red;">*</span></label>
                                                        <input type="text" name="client_address" id="client_address"
                                                            class="form-control" value="{{ old('client_address') }}"
                                                            placeholder="Enter Address">
                                                        @if ($errors->has('address'))
                                                            <div class="error" style="color:red;">
                                                                {{ $errors->first('address') }}</div>
                                                        @endif
                                                    </div>
                                                    <h6 class="mt-4 text-uppercase">Project Details</h6>
                                                    <hr>
                                                    {{-- project type in select2 box --}}
                                                    <div class="col-md-6">
                                                        <label for="inputEnterYourName" class="col-form-label">Project
                                                            Type <span style="color: red;">*</span></label>
                                                        <select name="project_type[]" id="project_type" class="form-control select2" multiple>
                                                            <option value="" disabled>Select Project Type</option>
                                                            <option value="Website Design & Development">Website Design & Development</option>
                                                            <option value="Mobile Application Development">Mobile Application Development</option>
                                                            <option value="Digital Marketing">Digital Marketing</option>
                                                            <option value="Logo Design">Logo Design</option>
                                                            <option value="SEO">SEO</option>
                                                            <option value="SMO">SMO</option>
                                                            <option value="Other">Other</option>
                                                        </select>
                                                    </div>
                                                    {{-- Project value --}}
                                                    <div class="col-md-6">
                                                        <label for="inputEnterYourName" class="col-form-label">Project
                                                            Value <span style="color: red;">*</span></label>
                                                        <input type="text" name="project_value" id="project_value"
                                                            class="form-control" value="{{ old('project_value') }}"
                                                            placeholder="Enter Project Value">
                                                    </div>
                                                    {{-- Project project_upfront --}}
                                                    <div class="col-md-6">
                                                        <label for="inputEnterYourName" class="col-form-label">Project
                                                            Upfront <span style="color: red;">*</span></label>
                                                        <input type="text" name="project_upfront" id="project_upfront"
                                                            class="form-control" value="{{ old('project_upfront') }}"
                                                            placeholder="Enter Project Upfront">
                                                    </div>
                                                    {{-- currency select box --}}
                                                    <div class="col-md-6">
                                                        <label for="inputEnterYourName" class="col-form-label">Currency
                                                            <span style="color: red;">*</span></label>
                                                        <select name="currency" id="currency" class="form-control">
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
                                                        <input type="text" name="project_payment_mode"
                                                            id="project_payment_mode" class="form-control"
                                                            value="{{ old('project_payment_mode') }}"
                                                            placeholder="Enter Project Payment Mode">
                                                    </div>
                                                    {{-- Project opener --}}
                                                    <div class="col-md-6">
                                                        <label for="inputEnterYourName" class="col-form-label">Project
                                                            Opener <span style="color: red;">*</span></label>
                                                        <input type="text" name="project_opener" id="project_opener"
                                                            class="form-control" value="{{ old('project_opener') }}"
                                                            placeholder="Enter Project Opener">
                                                    </div>
                                                    {{-- Project closer --}}
                                                    <div class="col-md-6">
                                                        <label for="inputEnterYourName" class="col-form-label">Project
                                                            Closer <span style="color: red;">*</span></label>
                                                        <input type="text" name="project_closer" id="project_closer"
                                                            class="form-control" value="{{ old('project_closer') }}"
                                                            placeholder="Enter Project Closer">
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
    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>
@endpush
