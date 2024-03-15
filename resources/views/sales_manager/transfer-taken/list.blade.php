@extends('sales_manager.layouts.master')
@section('title')
    All Prospect Taken Details - {{ env('APP_NAME') }}
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
                    @include('sales_manager.transfer-taken.show-details')
                </div>
            </div>
        </div>
    </div>
    <div class="page-wrapper">

        <div class="content container-fluid">

            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Prospects Taken Information</h3>
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
                                <h4 class="mb-0">Total Prospect Taken ({{$total_prospect}})</h4>
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
                    </div>


                    <div class="table-responsive" id="show-transfer-taken">
                        <table id="myTable" class="dd table table-striped table-hover"
                            style="width:100%">
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
                                    <th>Followup Date</th>
                                    <th>Price Quoted</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @include('sales_manager.transfer-taken.table')
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
            function fetch_data(page, query) {
                console.log(status + ' ' + page);
                var user_id = "{{ request()->user_id ?? 0 }}";
                $.ajax({
                    url: "{{ route('sales-manager.transfer-taken.filter') }}",
                    data: {
                        user_id: user_id,
                        page: page,
                        query: query
                    },
                    success: function(resp) {
                        $('tbody').html(resp.data);
                    }
                });
            }

            $(document).on('click', '.pagination a', function(e) {
                e.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                var query = $('#search').val();
                fetch_data(page, query);
            });

            $(document).on('keyup', '#search', function(e) {
                e.preventDefault();
                var query = $(this).val();
                fetch_data(1, query);
            });
        });
    </script>
@endpush
