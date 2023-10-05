@extends('sales_manager.layouts.master')
@section('title')
    {{ env('APP_NAME') }} | Edit Sales excecutive Details
@endsection
@push('styles')
@endpush

@section('content')
    <div class="page-wrapper">

        <div class="content container-fluid">

            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Edit Details</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('sales-manager.sales-excecutive.index') }}">Sales excecutives</a>
                            </li>
                            <li class="breadcrumb-item active">Edit Sales excecutive Details</li>
                        </ul>
                    </div>
                    <div class="col-auto float-end ms-auto">
                        {{-- <a href="#" class="btn add-btn" data-bs-toggle="modal" data-bs-target="#add_group"><i
                            class="fa fa-plus"></i> Add Sales excecutive</a> --}}
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                        <div class="row">
                            <div class="col-xl-12 mx-auto">
                                <h6 class="mb-0 text-uppercase">Edit A Sales excecutive</h6>
                                <hr>
                                <div class="card border-0 border-4">
                                    <div class="card-body">
                                        <form action="{{ route('sales-manager.sales-excecutive.update', $sales_excecutive->id) }}"
                                            method="POST" enctype="multipart/form-data">
                                            @method('PUT')
                                            @csrf
                                            <div class="border p-4 rounded">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label for="inputEnterYourName" class="col-form-label"> Name <span
                                                                style="color: red;">*</span></label>
                                                        <input type="text" name="name" id=""
                                                            class="form-control" value="{{ $sales_excecutive['name'] }}"
                                                            placeholder="Enter Sales excecutive Name">
                                                        @if ($errors->has('name'))
                                                            <div class="error" style="color:red;">
                                                                {{ $errors->first('name') }}</div>
                                                        @endif
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="inputEnterYourName" class="col-form-label"> Employee
                                                            Id</label>
                                                        <input type="text" name="employee_id" id=""
                                                            class="form-control"
                                                            value="{{ $sales_excecutive['employee_id'] }}"
                                                            placeholder="Enter Employee Id">
                                                        @if ($errors->has('employee_id'))
                                                            <div class="error" style="color:red;">
                                                                {{ $errors->first('employee_id') }}</div>
                                                        @endif
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="inputEnterYourName" class="col-form-label"> Date Of
                                                            Joining </label>
                                                        <input type="date" name="date_of_joining" id=""
                                                            max="{{ date('Y-m-d') }}" class="form-control"
                                                            value="{{ $sales_excecutive['date_of_joining'] }}">
                                                        @if ($errors->has('date_of_joining'))
                                                            <div class="error" style="color:red;">
                                                                {{ $errors->first('date_of_joining') }}</div>
                                                        @endif
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="inputEnterYourName" class="col-form-label"> Email <span
                                                                style="color: red;">*</span></label>
                                                        <input type="text" name="email" id=""
                                                            class="form-control" value="{{ $sales_excecutive['email'] }}"
                                                            placeholder="Enter Sales excecutive Email">
                                                        @if ($errors->has('email'))
                                                            <div class="error" style="color:red;">
                                                                {{ $errors->first('email') }}</div>
                                                        @endif
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="inputEnterYourName" class="col-form-label"> Phone <span
                                                                style="color: red;">*</span></label>
                                                        <input type="text" name="phone" id=""
                                                            class="form-control" value="{{ $sales_excecutive['phone'] }}"
                                                            placeholder="Enter Phone Number">
                                                        @if ($errors->has('phone'))
                                                            <div class="error" style="color:red;">
                                                                {{ $errors->first('phone') }}</div>
                                                        @endif
                                                    </div>


                                                    <div class="col-md-6">
                                                        <label for="inputEnterYourName" class="col-form-label"> Password
                                                        </label>
                                                        <input type="password" name="password" id=""
                                                            class="form-control" placeholder="Enter pasword">
                                                        @if ($errors->has('password'))
                                                            <div class="error" style="color:red;">
                                                                {{ $errors->first('password') }}</div>
                                                        @endif
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="inputEnterYourName" class="col-form-label"> Confirm
                                                            Password </label>
                                                        <input type="password" name="confirm_password" id=""
                                                            class="form-control"
                                                            value="{{ $sales_excecutive['confirm_password'] }}">
                                                        @if ($errors->has('confirm_password'))
                                                            <div class="error" style="color:red;">
                                                                {{ $errors->first('confirm_password') }}</div>
                                                        @endif
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="inputEnterYourName" class="col-form-label"> Status
                                                            <span style="color: red;">*</span></label>
                                                        <select name="status" id="" class="form-control">
                                                            <option value="">Select a Status</option>
                                                            <option value="1"
                                                                @if ($sales_excecutive['status'] == 1) selected="" @endif>Active
                                                            </option>
                                                            <option value="0"
                                                                @if ($sales_excecutive['status'] == 0) selected="" @endif>
                                                                Inactive</option>
                                                        </select>
                                                        @if ($errors->has('status'))
                                                            <div class="error" style="color:red;">
                                                                {{ $errors->first('status') }}</div>
                                                        @endif
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="inputEnterYourName" class="col-form-label"> Profile
                                                            Picture </label>
                                                        <input type="file" name="profile_picture" id=""
                                                            class="form-control"
                                                            value="{{ $sales_excecutive['profile_picture'] }}">
                                                        @if ($errors->has('profile_picture'))
                                                            <div class="error" style="color:red;">
                                                                {{ $errors->first('profile_picture') }}</div>
                                                        @endif
                                                    </div>
                                                    @if ($sales_excecutive['profile_picture'])
                                                        <div class="col-md-6">
                                                            <label for="inputEnterYourName" class="col-form-label">View
                                                                Profile Picture </label>
                                                            <br>
                                                            <img src="{{ Storage::url($sales_excecutive['profile_picture']) }}"
                                                                alt="" class="img-design">
                                                        </div>
                                                    @endif
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
@endpush
