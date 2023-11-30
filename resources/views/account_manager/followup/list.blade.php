@extends('account_manager.layouts.master')
@section('title')
    All Follow-Up Details - {{ env('APP_NAME') }}
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
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Follow-Up Details</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="show-details">
                    @include('account_manager.followup.show-details')
                </div>
            </div>
        </div>
    </div>
    <div class="page-wrapper">

        <div class="content container-fluid">

            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Follow-Up Information</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a
                                    href="{{ route('account-manager.followups.index') }}">Follow-Up</a>
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
                                <h4 class="mb-0">Follow-Up Details</h4>
                            </div>
                            <div class="col-md-6 text-end">
                                <a href="javascript:void(0);" class="btn px-5 submit-btn" data-bs-toggle="offcanvas"
                                    data-bs-target="#offcanvasRight" aria-controls="offcanvasRight"><i
                                        class="fa fa-plus"></i> Add Follow-Up</a>

                            </div>
                        </div>
                    </div>

                    <hr />

                    <div class="row justify-content-end">
                        <div class="col-md-6">
                            <div class="row g-1 justify-content-end">
                                <div class="col-md-8 pr-0">
                                    <div class="search-field prod-search">
                                        <input type="text" name="search" id="search" placeholder="search..." required
                                            class="form-control rounded_search">
                                        <a href="javascript:void(0)" class="prod-search-icon submit_search"><i
                                                class="fa fa-search"></i></a>
                                    </div>
                                </div>
                                {{-- <div class="col-md-3 pl-0 ml-2">
                                    <button class="btn btn-primary button-search" id="search-button"> <span class=""><i
                                                class="ph ph-magnifying-glass"></i></span> Search</button>
                                </div> --}}
                            </div>
                        </div>
                        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight"
                            aria-labelledby="offcanvasRightLabel">
                            <div class="offcanvas-header">
                                <button type="button" class="text-reset cls_btn_left" data-bs-dismiss="offcanvas"
                                    aria-label="Close">
                                    <i class="fa fa-chevron-right" aria-hidden="true"></i>
                                </button>
                                <h4 id="offcanvasEditLabel">Add Follow-Up</h4>
                            </div>
                            <div class="offcanvas-body">
                                <form action="{{ route('account-manager.followups.store') }}" method="POST"
                                    id="followup-form-create">
                                    @csrf
                                    <div class="row">

                                        <div class="form-group col-md-12 mb-3">
                                            <label>Project</label>
                                            <select class="form-control" name="project_id" id="project_id">
                                                <option value="">Select a project</option>
                                                @foreach ($data as $item)
                                                    <option value="{{ $item->id }}">{{ $item->business_name }}</option>
                                                @endforeach
                                            </select>
                                            <span class="text-danger project_id_error"></span>
                                        </div>

                                        <div class="form-group col-md-12 mb-3">
                                            <label>Follow-Up Type</label>
                                            <select class="form-control" name="followup_type" id="followup_type">
                                                <option value="">Select Type</option>
                                                <option value="call">Call</option>
                                                <option value="email">Email</option>
                                            </select>
                                            <span class="text-danger followup_type_error"></span>
                                        </div>
                                        <div class="form-group col-md-12 mb-3">
                                            <label>Follow-Up Description</label>
                                            <textarea class="form-control" rows="20" cols="20" name="followup_description" id="followup_description"></textarea>
                                            <span class="text-danger"></span>
                                        </div>
                                        <span class="text-danger followup_description_error"></span>
                                    </div>
                                    <div class="d-flex alin-items-center w-100 text-end">
                                        <button class="print_btn cancel_btn me-3" type="reset"><i
                                                class="far fa-times-circle"></i>
                                            Cancel</button>
                                        <button class="print_btn" type="submit"><i class="far fa-check-circle"></i>
                                            Update</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>


                    <div class="table-responsive">
                        <table id="myTable" class="dd table table-striped table-bordered table-hover" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="sorting" data-tippy-content="Sort by Business Name"
                                        data-sorting_type="desc" data-column_name="business_name"
                                        style="cursor: pointer"> Business Name <span id="business_name_icon"><span
                                                class="fa fa-sort-down"></span></span></th>
                                    <th class="sorting" data-tippy-content="Sort by Customer Name"
                                        data-sorting_type="desc" data-column_name="client_name" style="cursor: pointer">
                                        Customer Name <span id="client_name_icon"></span></th>
                                    <th class="sorting" data-tippy-content="Sort by Phone Number"
                                        data-sorting_type="desc" data-column_name="client_phone" style="cursor: pointer">
                                        Phone Number <span id="client_phone_icon"></span></th>
                                        <th data-tippy-content="Can't Sort by Last Follow-Up Date" data-sorting_type="desc"
                                        style="cursor: pointer">
                                        Last Follow-Up Date </th>
                                    <th data-tippy-content="Can't Sort by Follow-Up Type" data-sorting_type="desc"
                                        style="cursor: pointer">
                                        Follow-Up Type</th>



                                </tr>
                            </thead>
                            <tbody>

                                @include('account_manager.followup.table')

                            </tbody>
                        </table>
                        <input type="hidden" name="hidden_page" id="hidden_page" value="1" />
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
        $(document).ready(function() {
            $('#followup-form-create').submit(function(e) {
                e.preventDefault();

                var formData = $(this).serialize();

                $.ajax({
                    url: $(this).attr('action'),
                    type: $(this).attr('method'),
                    data: formData,
                    success: function(response) {
                        //windows load with toastr message
                        window.location.reload();
                        toastr.success('Follow-Up details added successfully');
                    },
                    error: function(xhr) {
                        // Handle errors (e.g., display validation errors)
                        //clear any old errors
                        $('.text-danger').html('');
                        // Handle errors (e.g., display validation errors)
                        var errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            // Assuming you have a span with class "text-danger" next to each input
                            $('.' + key + '_error').html(value[0]);
                        });
                    }
                });
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            function clear_icon() {
                $('#date_icon').html('');
                $('#business_name_icon').html('');
                $('#client_name_icon').html('');
                $('#client_phone_icon').html('');

            }

            function fetch_data(page, sort_type, sort_by, query) {

                $.ajax({
                    url: "{{ route('account-manager.followups.filter') }}",
                    data: {
                        page: page,
                        sortby: sort_by,
                        sorttype: sort_type,
                        query: query
                    },
                    success: function(data) {
                        $('tbody').html(data.data);
                    }
                });
            }

            $(document).on('keyup', '#search', function() {
                var query = $('#search').val();
                var column_name = $('#hidden_column_name').val();
                var sort_type = $('#hidden_sort_type').val();
                var page = $('#hidden_page').val();
                fetch_data(page, sort_type, column_name, query);
            });

            $(document).on('click', '.sorting', function() {
                var column_name = $(this).data('column_name');
                var order_type = $(this).data('sorting_type');
                var reverse_order = '';
                if (order_type == 'asc') {
                    $(this).data('sorting_type', 'desc');
                    reverse_order = 'desc';
                    clear_icon();
                    $('#' + column_name + '_icon').html(
                        '<span class="fa fa-sort-down"></span>');
                }
                if (order_type == 'desc') {
                    $(this).data('sorting_type', 'asc');
                    reverse_order = 'asc';
                    clear_icon();
                    $('#' + column_name + '_icon').html(
                        '<span class="fa fa-sort-up"></span>');
                }
                $('#hidden_column_name').val(column_name);
                $('#hidden_sort_type').val(reverse_order);
                var page = $('#hidden_page').val();
                var query = $('#search').val();
                fetch_data(page, reverse_order, column_name, query);
            });

            $(document).on('click', '.pagination a', function(event) {
                event.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                $('#hidden_page').val(page);
                var column_name = $('#hidden_column_name').val();
                var sort_type = $('#hidden_sort_type').val();

                var query = $('#search').val();

                $('li').removeClass('active');
                $(this).parent().addClass('active');
                fetch_data(page, sort_type, column_name, query);
            });

        });
    </script>



    <script>
        $(document).on('click', '#delete', function(e) {
            swal({
                    title: "Are you sure?",
                    text: "To delete this project.",
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
    <script>
        $(document).on('click', '.view-details-btn', function(e) {
            e.preventDefault();
            var route = $(this).data('route');
            // load data from remote url
            $('#loading').addClass('loading');
            $('#loading-content').addClass('loading-content');
            $.ajax({
                type: "GET",
                dataType: "json",
                url: route,
                success: function(resp) {
                    // console.log(resp.view);
                    //  open modal
                    $('#loading').removeClass('loading');
                    $('#loading-content').removeClass('loading-content');
                    $('#show-details').html(resp.view);
                }
            });
        });
    </script>
@endpush
