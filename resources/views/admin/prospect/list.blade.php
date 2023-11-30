@extends('admin.layouts.master')
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
                    @include('admin.prospect.show-details')
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
                            <li class="breadcrumb-item"><a href="{{ route('admin.prospects.index') }}">Prospects</a></li>
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
                                <h4 class="mb-0">Prospects Details</h4>
                            </div>
                            <div class="col-md-6 text-end">
                                <a href="{{ route('admin.prospects.create') }}" class="btn px-5 submit-btn"><i
                                        class="fa fa-plus"></i> Add a
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
                                    <h5>{{ $count['prospect'] }}</h5>
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
                    </div>
                    <div class="table-responsive" id="show-prospect">

                        <table id="myTable" class="dd table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Prospect By</th>
                                    <th>Client Name</th>
                                    <th>Business Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Transfer Taken By</th>
                                    <th>Status</th>
                                    <th>Service Offered</th>
                                    <th>Followup Date</th>
                                    <th>Price Quoted</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @include('admin.prospect.table')

                            </tbody>
                        </table>

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
            function fetch_data(page, status, query) {
                console.log(status + ' ' + page);
                $.ajax({
                    url: "{{ route('admin.prospects.filter') }}",
                    data: {
                        status: status,
                        page: page,
                        query: query
                    },
                    success: function(resp) {
                        $('tbody').html(resp.data);
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
                fetch_data(1, status, query);
                });

            $(document).on('click', '.pagination a', function(e) {
                e.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                var status = $('.active-filter').data('value');
                var query = $('#search').val();
                fetch_data(page, status, query);
            });

            $(document).on('keyup', '#search', function(e) {
                e.preventDefault();
                var query = $(this).val();
                var status = $('.active-filter').data('value');
                fetch_data(1, status, query);
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            //how to place holder in "jquery datatable" search box
            $('#myTable_filter input').attr("placeholder", "Search");
        });
    </script>
@endpush
