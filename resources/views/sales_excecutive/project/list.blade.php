@extends('sales_excecutive.layouts.master')
@section('title')
    All Project Details - {{ env('APP_NAME') }}
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
                        <h3 class="page-title">Projects Information</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('sales-excecutive.projects.index') }}">Projects</a>
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
                                <h4 class="mb-0">Projects Details</h4>
                            </div>
                            <div class="col-md-6 text-end">
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
                                    <th class="sorting" data-tippy-content="Sort by Sale Date" data-sorting_type="desc"
                                    data-column_name="sale_date" style="cursor: pointer">Sale Date <span id="date_icon"><span class="fa fa-sort-down"></span></span></th>
                                    <th class="sorting" data-tippy-content="Sort by Business Name" data-sorting_type="desc"
                                    data-column_name="business_name" style="cursor: pointer"> Business Name <span id="business_name_icon"><span class="fa fa-sort-down"></span></span></th>
                                    <th class="sorting" data-tippy-content="Sort by Customer Name" data-sorting_type="desc"
                                    data-column_name="customer_name" style="cursor: pointer"> Customer Name <span id="customer_name_icon"><span class="fa fa-sort-down"></span></span></th>
                                    <th class="sorting" data-tippy-content="Sort by Phone Number" data-sorting_type="desc"
                                    data-column_name="phone_number" style="cursor: pointer"> Phone Number <span id="phone_number_icon"><span class="fa fa-sort-down"></span></span></th>
                                    <th class="sorting" data-tippy-content="Sort by Project Type" data-sorting_type="desc"
                                    data-column_name="project_type" style="cursor: pointer"> Project Type <span id="project_type_icon"><span class="fa fa-sort-down"></span></span></th>
                                    <th class="sorting" data-tippy-content="Sort by Project value" data-sorting_type="desc"
                                    data-column_name="project_value" style="cursor: pointer"> Project Value <span id="project_value_icon"><span class="fa fa-sort-down"></span></span></th>
                                    <th class="sorting" data-tippy-content="Sort by Project Upfront" data-sorting_type="desc"
                                    data-column_name="project_upfront" style="cursor: pointer"> Project Upfront <span id="project_upfront_icon"><span class="fa fa-sort-down"></span></span></th>
                                    <th class="sorting" data-tippy-content="Sort by Currency" data-sorting_type="desc"
                                    data-column_name="currency" style="cursor: pointer"> Currency <span id="currency_icon"><span class="fa fa-sort-down"></span></span></th>
                                    <th class="sorting" data-tippy-content="Sort by Payment Mode" data-sorting_type="desc"
                                    data-column_name="payment_mode" style="cursor: pointer"> Payment Mode <span id="payment_mode_icon"><span class="fa fa-sort-down"></span></span></th>
                                    <th data-tippy-content="Cant't sort by Due Amount" style="cursor: pointer"> Due Amount <span id="due_amount_icon"></span></th>
                                    <th> Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @include('sales_excecutive.project.table')

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
        function clear_icon() {
            $('#date_icon').html('');
            $('#project_name_icon').html('');
            $('#customer_name_icon').html('');
            $('#phone_number_icon').html('');
            $('#project_type_icon').html('');
            $('#project_value_icon').html('');
            $('#project_upfront_icon').html('');
            $('#currency_icon').html('');
            $('#payment_mode_icon').html('');
            $('#due_amount_icon').html('');
        }

        function fetch_data(page, sort_type, sort_by, query) {

            $.ajax({
                url: "{{ route('sales-excecutive.project.filter') }}",
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
        $(document).ready(function() {
            //how to place holder in "jquery datatable" search box
            $('#myTable_filter input').attr("placeholder", "Search");
        });
    </script>
@endpush
