@extends('sales_manager.layouts.master')
@section('title')
    All Payments List - {{ env('APP_NAME') }}
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
    <div class="page-wrapper">

        <div class="content container-fluid">

            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Project Payments Information</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('sales-manager.payments.list') }}">Payments</a>
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
                                <h4 class="mb-0">Payments List</h4>
                            </div>

                        </div>
                    </div>

                    <hr />
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

                    <div class="table-responsive">
                        <table id="myTable" class="dd table table-striped  table-hover" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Project Name </th>
                                    <th>Milestone Name </th>
                                    <th>Milestone value</th>
                                    <th>Payment mode</th>
                                    <th>Payment date</th>
                                    <th style="cursor: pointer">
                                        Download Invoice</th>

                                </tr>
                            </thead>
                            <tbody>

                                @include('sales_manager.payments.table')

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
    $(document).ready(function() {
        function fetch_data(page, query) {
            // console.log(status + ' ' + page);
            $.ajax({
                url: "{{ route('sales_manager.payments.filter') }}",
                data: {
                    page: page,
                    query: query,
                   
                },
                success: function(resp) {
                    $('tbody').html(resp.data);
                }
            });
        }

        $(document).on('click', '.desin-filter', function(e) {
            e.preventDefault();
            //add active class to clicked
            $(this).addClass('active-filter');
            var query = $('#search').val();
            fetch_data(1, query);
        });

        $(document).on('click', '.pagination a', function(e) {
            e.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            var query = $('#search').val();
            fetch_data(page,query);
        });

        $(document).on('keyup', '#search', function(e) {
            
            e.preventDefault();
            var query = $(this).val();
            fetch_data(1, query);
        });
    });
</script>
   
@endpush
