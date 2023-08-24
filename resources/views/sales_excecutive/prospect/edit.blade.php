@extends('sales_excecutive.layouts.master')
@section('title')
    {{ env('APP_NAME') }} | Edit Prospect
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
                            <li class="breadcrumb-item"><a href="{{ route('prospects.index') }}">Prospects</a></li>
                            <li class="breadcrumb-item active">Edit Prospect</li>
                        </ul>
                    </div>
                    <div class="col-auto float-end ms-auto">
                        {{-- <a href="#" class="btn add-btn" data-bs-toggle="modal" data-bs-target="#add_group"><i
                            class="fa fa-plus"></i> Add Prospect</a> --}}
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                        <div class="row">
                            <div class="col-xl-12 mx-auto">
                                <h3 class="mb-0 text-uppercase">Edit A Prospect</h3>
                                <hr>
                                <div class="card border-0 border-4">
                                    <div class="card-body">
                                        <form action="{{ route('prospects.update', $prospect->id) }}"
                                            method="POST" enctype="multipart/form-data">
                                            @method('PUT')
                                            @csrf   
                                            <div class="border p-4 rounded">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label for="inputEnterYourName" class="col-form-label"> Client Name
                                                            <span style="color: red;">*</span></label>
                                                        <input type="text" name="client_name" id="client_name" required data-parsley-trigger="keyup"
                                                            class="form-control" value="{{ $prospect->client_name }}"
                                                            placeholder="Enter Client Name">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="inputEnterYourName" class="col-form-label">Business Name
                                                            <span style="color: red;">*</span></label>
                                                        <input type="text" name="business_name" id="business_name" required data-parsley-trigger="keyup"
                                                            class="form-control" value="{{ $prospect->business_name }}"
                                                            placeholder="Enter Business Name">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="inputEnterYourName" class="col-form-label">Client Email
                                                            <span style="color: red;">*</span></label>
                                                        <input type="text" name="client_email" id="client_email" required data-parsley-trigger="keyup" data-parsley-type="email" data-parsley-type-message="Please enter a valid email address." 
                                                            class="form-control" value="{{ $prospect->client_email }}"
                                                            placeholder="Enter Client Email">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="inputEnterYourName" class="col-form-label">Client Phone
                                                            <span style="color: red;">*</span></label>
                                                        <input type="text" name="client_phone" id="client_phone" required data-parsley-trigger="keyup" data-parsley-type="number" data-parsley-type-message="Please enter a valid phone number."
                                                            class="form-control" value="{{ $prospect->client_phone }}"
                                                            placeholder="Enter Client Phone Number">
                                                    </div>

                                                    {{-- clinent address --}}
                                                    <div class="col-md-6">
                                                        <label for="inputEnterYourName" class="col-form-label">Business
                                                            Address <span style="color: red;">*</span></label>
                                                        <input type="text" name="business_address" id="business_address" required data-parsley-trigger="keyup" 
                                                            class="form-control" value="{{ $prospect->business_address }}"
                                                            placeholder="Enter Address">
                                                    </div>
                                                    
                                                     {{-- website --}}
                                                     <div class="col-md-6">
                                                        <label for="inputEnterYourName" class="col-form-label">Website Link</label>
                                                        <input type="text" name="website" id="website" data-parsley-required="false" data-parsley-trigger="keyup" data-parsley-type="url" data-parsley-type-message="Please enter a valid url."
                                                            class="form-control" value="{{ $prospect->website }}"
                                                            placeholder="Enter Website">
                                                    </div>
                                                    {{-- offer for --}}
                                                    <div class="col-md-6">
                                                        <label for="inputEnterYourName" class="col-form-label">Offer For
                                                            <span style="color: red;">*</span></label>
                                                        <input type="text" name="offered_for" id="offered_for" required data-parsley-trigger="keyup"
                                                            class="form-control" value="{{ $prospect->offered_for }}"
                                                            placeholder="Enter Offer For">
                                                    </div>
                                                    {{--  price_quote --}}
                                                    <div class="col-md-6">
                                                        <label for="inputEnterYourName" class="col-form-label">Price Quote
                                                            <span style="color: red;">*</span></label>
                                                        <input type="text" name="price_quote" id="price_quote" required data-parsley-trigger="keyup" data-parsley-type="number" data-parsley-type-message="Please enter a valid number."
                                                            class="form-control" value="{{ $prospect->price_quote }}"
                                                            placeholder="Enter Price Quote">
                                                    </div>
                                                  {{-- status --}}
                                                    <div class="col-md-6">
                                                        <label for="inputEnterYourName" class="col-form-label">Status
                                                            <span style="color: red;">*</span></label>
                                                       <select name="status" id="status" class="form-control" required data-parsley-trigger="keyup">
                                                           <option value="">Select Status</option>
                                                           <option value="Win" {{ $prospect->status == 'Win' ? 'selected' : '' }}>Win</option>
                                                           <option value="Follow Up" {{ $prospect->status == 'Follow Up' ? 'selected' : '' }}>Follow Up</option>
                                                              <option value="Sent Proposal" {{ $prospect->status == 'Sent Proposal' ? 'selected' : '' }}>Sent Proposal</option>
                                                              <option value="Close" {{ $prospect->status == 'Close' ? 'selected' : '' }}>Close</option>
                                                         </select>
                                                    </div>
                                                    {{-- transfer_token_by --}}
                                                    <div class="col-md-6">
                                                        <label for="inputEnterYourName" class="col-form-label">Transfer Token By</label>
                                                        <input type="text" name="transfer_token_by" id="transfer_token_by" 
                                                            class="form-control"  value="{{ $prospect->transfer_token_by }}"
                                                            placeholder="Transfer Token By">
                                                    </div>
                                                    {{-- followup_date --}}
                                                    <div class="col-md-6">
                                                        <label for="inputEnterYourName" class="col-form-label">Followup Date</label>
                                                        <input type="date" name="followup_date" id="followup_date" 
                                                            class="form-control" value="{{ $prospect->followup_date }}"
                                                            placeholder="Enter Followup Date">
                                                    </div>
                                                    {{-- followup_time --}}
                                                    <div class="col-md-6">
                                                        <label for="inputEnterYourName" class="col-form-label">Followup Time</label>
                                                        <input type="time" name="followup_time" id="followup_time" 
                                                            class="form-control" value="{{ $prospect->followup_time }}"
                                                            placeholder="Enter Followup Time">
                                                    </div>
                                                    {{-- next_followup_date --}}
                                                    <div class="col-md-6">
                                                        <label for="inputEnterYourName" class="col-form-label">Next Followup Date</label>
                                                        <input type="date" name="next_followup_date" id="next_followup_date" 
                                                            class="form-control"  value="{{ $prospect->next_followup_date }}"
                                                            placeholder="Enter Next Followup Date">
                                                    </div>
                                                    {{-- comments --}}
                                                    <div class="col-md-6">
                                                        <label for="inputEnterYourName" class="col-form-label">Comments</label>
                                                        <textarea name="comments" id="comments" cols="30" rows="10" 
                                                            class="form-control" 
                                                            placeholder="Enter Comments"> {{ $prospect['comments'] }} </textarea>
                                                    </div>
                                                    <div class="row" style="margin-top: 20px; float: left;">
                                                        <div class="col-sm-9">
                                                            <button type="submit"
                                                                class="btn px-5 submit-btn">Edit</button>
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

@endpush
