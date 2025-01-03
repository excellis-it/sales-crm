@extends('sales_manager.layouts.master')
@section('title')
    All Prospect Details - {{ env('APP_NAME') }}
@endsection
@push('styles')
    <style>
        .dataTables_filter {
            margin-bottom: 10px !important;
        }
    </style>
@endpush

@section('content')
    <section id="loading">
        <div id="loading-content"></div>
    </section>
    <div class="modal modal_view fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Business Details</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="show-details">
                    @include('sales_manager.prospect.show-details')
                </div>
            </div>
        </div>
    </div>
    <div class="page-wrapper">

        <div class="content container-fluid">

            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Prospects Information</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('sales-manager.prospects.index') }}">Prospects</a>
                            </li>
                            <li class="breadcrumb-item active">List</li>
                        </ul>
                    </div>

                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                        <div class="row">
                            <div class="col-md-6">
                                <h4 class="mb-0">Prospects List</h4>
                            </div>
                            <div class="col-md-6 text-end">
                                <a href="javascript:void(0);" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight"
                                    aria-controls="offcanvasRight" class="btn px-5 submit-btn"><i class="fa fa-plus"></i>
                                    Add a
                                    Prospect</a>
                            </div>
                        </div>
                    </div>

                    <hr />
                    <div class="card-title">
                        <div class="row filter-gap align-items-center">
                            <div class="col">
                                <a href="javascript:void(0);" data-value="All" class="desin-filter active-filter">
                                    <p>All</p>
                                    <h5>{{ $count['total'] }}</h5>
                                </a>
                            </div>
                            <div class="col">
                                <a href="javascript:void(0);" data-value="Win" class="desin-filter">
                                    <p>On Board</p>
                                    <h5>{{ $count['win'] }}</h5>
                                </a>
                            </div>
                            <div class="col">
                                <a href="javascript:void(0);" data-value="Follow Up" class="desin-filter">
                                    <p>Follow Up</p>
                                    <h5>{{ $count['follow_up'] }}</h5>
                                </a>
                            </div>
                            <div class="col">
                                <a href="javascript:void(0);" data-value="Sent Proposal" class="desin-filter">
                                    <p>Sent Proposal</p>
                                    <h5>{{ $count['sent_proposal'] }}</h5>
                                </a>
                            </div>
                            <div class="col">
                                <a href="javascript:void(0);" data-value="Close" class="desin-filter">
                                    <p>Cancel</p>
                                    <h5>{{ $count['close'] }}</h5>
                                </a>
                            </div>
                        </div>
                    </div>


                    <div class="row justify-content-end">
                        <div class="col-md-6">
                            <div class="row g-1 justify-content-end">
                                <div class="col-md-8 pr-0">
                                    <div class="search-field">
                                        <input type="text" name="search" id="search" placeholder="search..." required
                                            class="form-control rounded_search">
                                        <button class="submit_search" id="search-button"> <span class=""><i
                                                    class="fa fa-search"></i></span></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight"
                            aria-labelledby="offcanvasRightLabel">
                            <div class="offcanvas-header">
                                <button type="button" class="text-reset cls_btn_left" data-bs-dismiss="offcanvas"
                                    aria-label="Close">
                                    <i class="fa fa-chevron-right" aria-hidden="true"></i>
                                </button>
                                <h4 id="offcanvasEditLabel">Add Prospect Details</h4>
                            </div>
                            <div class="offcanvas-body">
                                <form action="{{ route('sales-manager.prospects.store') }}" method="post"
                                    data-parsley-validate="" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="sales_executive_id"
                                        value="{{ request()->sales_executive_id }}" />
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label for="inputEnterYourName" class="col-form-label"> Sales
                                                Executive
                                                <span style="color: red;">*</span></label>
                                            <select name="user_id" id="user_id" class="form-control" required
                                                data-parsley-trigger="keyup">
                                                <option value="">Select sales executive</option>
                                                @foreach ($sales_executives as $sales_executive)
                                                    <option value="{{ $sales_executive->id }}">
                                                        {{ $sales_executive->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label for="inputEnterYourName" class="col-form-label"> Client Name
                                                <span style="color: red;">*</span></label>
                                            <input type="text" name="client_name" id="client_name" required
                                                data-parsley-trigger="keyup" class="form-control"
                                                value="{{ old('client_name') }}" placeholder="Enter Client Name">
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label for="inputEnterYourName" class="col-form-label">Business Name
                                                <span style="color: red;">*</span></label>
                                            <input type="text" name="business_name" id="business_name" required
                                                data-parsley-trigger="keyup" class="form-control"
                                                value="{{ old('business_name') }}" placeholder="Enter Business Name">
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label for="inputEnterYourName" class="col-form-label">Client Email
                                                <span style="color: red;">*</span></label>
                                            <input type="text" name="client_email" id="client_email" required
                                                data-parsley-trigger="keyup" data-parsley-type="email"
                                                data-parsley-type-message="Please enter a valid email address."
                                                class="form-control" value="{{ old('client_email') }}"
                                                placeholder="Enter Client Email">
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label for="inputEnterYourName" class="col-form-label">Client Phone
                                                <span style="color: red;">*</span></label>
                                            <input type="text" name="client_phone" id="client_phone" required
                                                data-parsley-trigger="keyup" data-parsley-type="number"
                                                data-parsley-type-message="Please enter a valid phone number."
                                                class="form-control" value="{{ old('client_phone') }}"
                                                placeholder="Enter Client Phone Number">
                                        </div>

                                        {{-- clinent address --}}
                                        <div class="col-md-12 mb-3">
                                            <label for="inputEnterYourName" class="col-form-label">Business
                                                Address <span style="color: red;">*</span></label>
                                            <input type="text" name="business_address" id="business_address" required
                                                data-parsley-trigger="keyup" class="form-control"
                                                value="{{ old('business_address') }}" placeholder="Enter Address">
                                        </div>

                                        {{-- website --}}
                                        <div class="col-md-12 mb-3">
                                            <label for="inputEnterYourName" class="col-form-label">Website
                                                Link</label>
                                            <input type="text" name="website" id="website"
                                                data-parsley-required="false" data-parsley-trigger="keyup"
                                                data-parsley-type="url"
                                                data-parsley-type-message="Please enter a valid url." class="form-control"
                                                value="{{ old('website') }}" placeholder="Enter Website">
                                        </div>
                                        {{-- offer for --}}
                                        <div class="col-md-12 mb-3">
                                            <label for="inputEnterYourName" class="col-form-label">Service
                                                Offered <span style="color: red;">*</span></label>
                                            <select name="offered_for" id="project_type" required
                                                data-parsley-trigger="keyup" class="form-control">
                                                <option value="">Select Project Type</option>
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
                                        <div id="other-value" class="col-md-12 mb-3">

                                        </div>
                                        {{-- price_quote --}}
                                        <div class="col-md-12 mb-3">
                                            <label for="inputEnterYourName" class="col-form-label">Price Quote
                                                <span style="color: red;">*</span></label>
                                            <input type="text" name="price_quote" id="price_quote" required
                                                data-parsley-trigger="keyup" data-parsley-type="number"
                                                data-parsley-type-message="Please enter a valid number."
                                                class="form-control" value="{{ old('price_quote') }}"
                                                placeholder="Enter Price Quote">
                                        </div>

                                        {{-- transfer_token_by --}}
                                        <div class="col-md-12 mb-3">
                                            <label for="inputEnterYourName" class="col-form-label">Transfer
                                                Taken By <span style="color: red;">*</span>
                                            </label>
                                            <select name="transfer_token_by" id="transfer_token_by"
                                                class="form-control select2" required data-parsley-trigger="keyup">
                                                <option value="">Select Transfer Token By
                                                </option>
                                                @foreach ($users as $user)
                                                    <option value="{{ $user->id }}">{{ $user->name }}
                                                        ({{ $user->email }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        {{-- followup_date --}}
                                        <div class="col-md-12 mb-3">
                                            <label for="inputEnterYourName" class="col-form-label">Followup
                                                Date <span style="color: red;">*</span></label>
                                            <input type="date" name="followup_date" id="followup_date" required
                                                class="form-control picker" placeholder="Enter Followup Date">
                                        </div>
                                        {{-- followup_time --}}
                                        <div class="col-md-12 mb-3">
                                            <label for="inputEnterYourName" class="col-form-label">Followup
                                                Time</label>
                                            <input type="time" name="followup_time" id="followup_time"
                                                class="form-control" placeholder="Enter Followup Time">
                                        </div>
                                        {{-- status --}}
                                        <div class="col-md-12 mb-3">
                                            <label for="inputEnterYourName" class="col-form-label">Status
                                                <span style="color: red;">*</span></label>
                                            <select name="status" id="status" class="form-control" required
                                                data-parsley-trigger="keyup">
                                                <option value="">Select Status</option>
                                                <option value="Win">On board</option>
                                                <option value="Follow Up">Follow Up</option>
                                                <option value="Sent Proposal">Sent Proposal</option>
                                                <option value="Close">Cancel</option>
                                            </select>
                                        </div>


                                    </div>
                                    {{-- upfront_value --}}
                                    <div class="row" id="upfront_value_show">
                                    </div>
                                    {{-- comments --}}
                                    <div class="col-md-12 mb-3">
                                        <label for="inputEnterYourName" class="col-form-label">Comments</label>
                                        <textarea name="comments" id="comments" cols="30" rows="10" class="form-control"
                                            placeholder="Enter Comments">{{ old('comments') }}</textarea>
                                    </div>
                                    <div class="d-flex alin-items-center w-100 text-end">
                                        <button class="print_btn cancel_btn me-3" type="reset"><i
                                                class="far fa-times-circle"></i>
                                            Cancel</button>
                                        <button class="print_btn" type="submit"><i class="far fa-check-circle"></i>
                                            Create</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div id="edit-prospect-model">
                            @include('sales_manager.prospect.edit')
                        </div>
                    </div>


                    <div class="table-responsive" id="show-prospect">
                        <table id="myTable" class="dd table table-striped table-hover" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Created Date</th>
                                    <th>Created By</th>
                                    <th>Business Name</th>
                                    <th>Client Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Transfer Taken By</th>
                                    <th>Status</th>
                                    <th>Service Offered</th>
                                    <th>Followup Date <input type="text" class="datepicker" id="followup_date_filter"
                                            style="width: 0; padding:0; border:none" />
                                        <label for="followup_date_filter" class="datepik" style="font-size: 22px"><i
                                                class="las la-calendar"></i></label>
                                    </th>
                                    <th>Price Quoted</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="prospect-body">
                                @include('sales_manager.prospect.table')
                            </tbody>
                        </table>
                        @if (Session::get('update_success_check') == true)
                            <input type="hidden" name="hidden_page" id="hidden_page"
                                value="@php echo Session::get('page_no') ?? '1' @endphp" />
                        @else
                            <input type="hidden" name="hidden_page" id="hidden_page" value="1" />
                        @endif
                        <input type="hidden" name="hidden_column_name" id="hidden_column_name" value="id" />
                        <input type="hidden" name="hidden_sort_type" id="hidden_sort_type" value="desc" />
                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection

@push('scripts')
    <script>
        $(document).on('click', '#delete', function(e) {
            swal({
                    title: "Are you sure?",
                    text: "To delete this prospect.",
                    type: "warning",
                    confirmButtonText: "Yes",
                    showCancelButton: true
                })
                .then((result) => {
                    if (result.value) {
                        window.location = $(this).data('route');
                    } else if (result.dismiss === 'cancel') {
                        swal(
                            'Cancelled',
                            'Your stay here :)',
                            'error'
                        )
                    }
                })
        });
    </script>
    {{-- <script>
    $('.toggle-class').change(function() {
        var status = $(this).prop('checked') == true ? 1 : 0;
        var user_id = $(this).data('id');

        $.ajax({
            type: "GET",
            dataType: "json",
            url: '{{ route('prospects.change-status') }}',
            data: {
                'status': status,
                'user_id': user_id
            },
            success: function(resp) {
                console.log(resp.success)
            }
        });
    });
</script> --}}
    <script>
        $(document).on('click', '.view-details-btn', function(e) {
            e.preventDefault();
            var route = $(this).data('route');
            $.ajax({
                type: "GET",
                dataType: "json",
                url: route,
                success: function(resp) {
                    // console.log(resp.view);
                    $('#show-details').html(resp.view);
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('.datepicker').datepicker({
                dateFormat: 'dd-mm-yy',

            });
            @if (Session::get('update_success_check') == true)
                var query = $('#search').val();
                var followup_date = $('#followup_date_filter').val();
                var status = $('.active-filter').data('value');
                var page = @php echo Session::get('page_no') ?? '1' @endphp;

                fetch_data(page, status, query, followup_date);
            @endif

            function fetch_data(page, status, query, followup_date) {
                // console.log(status + ' ' + page);

                var user_id = {{ request()->sales_executive_id ?? 0 }};
                $.ajax({
                    url: "{{ route('sales-manager.prospects.status-filter') }}",
                    data: {
                        status: status,
                        page: page,
                        query: query,
                        user_id: user_id,
                        followup_date: followup_date
                    },
                    success: function(resp) {
                        $('#loading').removeClass('loading');
                        $('#loading-content').removeClass('loading-content');
                        $('#prospect-body').html(resp.data);
                    }
                });
            }

            $(document).on('click', '.desin-filter', function(e) {
                e.preventDefault();
                var status = $(this).data('value');
                //remove active class from all
                $('.desin-filter').removeClass('active-filter');
                //add active class to clicked
                $(this).addClass('active-filter');
                var query = $('#search').val();
                var followup_date = $('#followup_date_filter').val();
                fetch_data(1, status, query, followup_date);
            });

            $(document).on('click', '.pagination a', function(e) {
                e.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                $('#hidden_page').val(page);
                var status = $('.active-filter').data('value');
                var query = $('#search').val();
                var followup_date = $('#followup_date_filter').val();
                fetch_data(page, status, query, followup_date);
            });

            $(document).on('keyup', '#search', function(e) {
                e.preventDefault();
                var query = $(this).val();
                var status = $('.active-filter').data('value');
                var followup_date = $('#followup_date_filter').val();
                fetch_data(1, status, query, followup_date);
            });

            $(document).on('change', '#followup_date_filter', function(e) {
                e.preventDefault();
                $('#loading').addClass('loading');
                $('#loading-content').addClass('loading-content');
                var query = $('#search').val();
                var status = $('.active-filter').data('value');
                var followup_date = $('#followup_date_filter').val();
                // alert(followup_date);
                fetch_data(1, status, query, followup_date);
            });
        });
        // $(document).ready(function() {
        //     function fetch_data(page, status, query) {
        //         console.log(status + ' ' + page);
        //         var user_id = "{{ request()->sales_executive_id ?? 0 }}";
        //         $.ajax({
        //             url: "{{ route('sales-manager.prospects.status-filter') }}",
        //             data: {
        //                 user_id: user_id,
        //                 status: status,
        //                 page: page,
        //                 query: query
        //             },
        //             success: function(resp) {
        //                 $('tbody').html(resp.data);
        //             }
        //         });
        //     }

        //     $(document).on('click', '.desin-filter', function(e) {
        //         e.preventDefault();
        //         var status = $(this).data('value');
        //         //remove active class from all
        //         $('.desin-filter').removeClass('active-filter');
        //         //add active class to clicked
        //         $(this).addClass('active-filter');
        //         var query = $('#search').val();
        //         fetch_data(1, status, query);
        //     });

        //     $(document).on('click', '.pagination a', function(e) {
        //         e.preventDefault();
        //         var page = $(this).attr('href').split('page=')[1];
        //         var status = $('.active-filter').data('value');
        //         var query = $('#search').val();
        //         fetch_data(page, status, query);
        //     });

        //     $(document).on('keyup', '#search', function(e) {
        //         e.preventDefault();
        //         var query = $(this).val();
        //         var status = $('.active-filter').data('value');
        //         fetch_data(1, status, query);
        //     });
        // });
    </script>
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
            $('.select2').each(function() {
                $(this).select2({
                    dropdownParent: $(this).parent()
                });
            })

            $('#project_type').on('change', function() {
                //    select 2 value get and seo,other value check
                var project_type = $(this).val();
                if (project_type.includes('Other')) {
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
            $('#status').on('change', function() {

                // get value win show the upfront value
                var status = $(this).val();
                if (status.includes('Win')) {
                    $('#upfront_value_show').html(
                        '<div class="col-md-12 mb-3"><label for="inputEnterYourName" data-parsley-type="number" class="col-form-label">Upfront Value <span style="color: red;">*</span></label><input type="text" name="upfront_value" id="upfront_value"  required data-parsley-trigger="keyup" data-parsley-type="number" data-parsley-type-message="Please enter a valid number." class="form-control" value="{{ old('upfront_value') }}" placeholder="Enter Upfront Value"></div><div class="col-md-12 mb-3"> <label for = "inputEnterYourName" class="col-form-label"> Sale Date <span style="color: red;">*</span></label></label> <input type="date" name ="sale_date" id ="sale_date" class="form-control picker"></div><h3 class="mt-4 text-uppercase">Milestone</h3><hr><div class="row"><div class="col-md-12 mb-3 pb-3"><div style="display: flex"><input type="text" name="milestone_name[]" class="form-control" value="" placeholder="Milestone name" id="" required data-parsley-trigger="keyup"></div></div><div class="col-md-12 mb-3 pb-3"><div style="display: flex"><input type="text" name="milestone_value[]" class="form-control" value="" placeholder="Milestone value" id="" required data-parsley-trigger="keyup" data-parsley-type="number" data-parsley-type-message="Please enter a valid number."></div></div><div class="col-md-12 mb-3 pb-3"><div style="display: flex"><textarea name="milestone_comment[]" class="form-control" placeholder="Milestone Comment" id="" cols="3" rows="2" ></textarea></div></div></div><div class="col-md-12 mb-3"><button type="button" class="btn btn-primary milestone-print"><i class="fas fa-plus"></i> Add Milestone</button></div><div class="add-milestone"></div></div>'
                    );
                } else {
                    $('#upfront_value_show').html('');
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            // Handle the click event for the edit-route button
            $(document).on('click', '.edit-route', function() {
                var route = $(this).data('route');
                // Make an AJAX request to fetch the priceRequest details
                $('#loading').addClass('loading');
                $('#loading-content').addClass('loading-content');
                var page = $('#hidden_page').val();
                // alert(page);
                $.ajax({
                    url: route,
                    type: 'GET',
                    success: function(response) {
                        console.log(response.view);
                        $('#edit-prospect-model').html(response.view);
                        $('#loading').removeClass('loading');
                        $('#loading-content').removeClass('loading-content');
                        $('#offcanvasEdit').offcanvas('show');

                        $('#edit-page-no').val(page);
                    },
                    error: function(xhr) {
                        // Handle errors
                        $('#loading').removeClass('loading');
                        $('#loading-content').removeClass('loading-content');
                        console.log(xhr);
                    }
                });
            });
        });
    </script>

    <script>
        $(document).on('click', '.milestone-print', function() {

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
            $('.add-milestone').append(html);
        });

        $(document).on('click', '.remove', function() {
            $(this).closest('.row').remove();
        });
    </script>
@endpush
