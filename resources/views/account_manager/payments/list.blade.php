@extends('account_manager.layouts.master')
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
                        <li class="breadcrumb-item"><a href="{{ route('account-manager.payments.list') }}">Payments</a>
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
                {{-- <div class="row justify-content-end">
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
                </div> --}}

                <div class="table-responsive">
                    <table id="myTable" class="dd table table-striped  table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th class="sorting" data-tippy-content="Sort by Project Name" data-sorting_type="desc"
                                data-column_name="project_name" style="cursor: pointer">Project Name <span id="project_name_icon"><span class="fa fa-sort-down"></span></span></th>
                                <th class="sorting" data-tippy-content="Sort by Milestone Name" data-sorting_type="desc"
                                data-column_name="milestone_name" style="cursor: pointer">Milestone Name <span id="milestone_name_icon"><span class="fa fa-sort-down"></span></span></th>
                                <th class="sorting" data-tippy-content="Sort by Milestone Value" data-sorting_type="desc"
                                data-column_name="milestone_value" style="cursor: pointer"> Milestone value<span id="milestone_value_icon"><span class="fa fa-sort-down"></span></span></th>
                                <th class="sorting" data-tippy-content="Sort by Payment Mode" data-sorting_type="desc"
                                data-column_name="payment_mode" style="cursor: pointer"> Payment mode <span id="payment_mode_icon"><span class="fa fa-sort-down"></span></span></th>
                                <th class="sorting" data-tippy-content="Sort by Project value" data-sorting_type="desc"
                                data-column_name="project_value" style="cursor: pointer">Payment date <span id="project_value_icon"><span class="fa fa-sort-down"></span></span></th>
                                <th data-tippy-content="Cant't sort by Download Invoice" style="cursor: pointer"> Download Invoice</th>
                                
                            </tr>
                        </thead>
                        <tbody>

                            @include('account_manager.payments.table')

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
{{-- <script>
    $(document).ready(function() {
        function clear_icon() {
            $('#project_name_icon').html('');
            $('#milestone_name_icon').html('');
            $('#milestone_value_icon').html('');
            $('#payment_mode_icon').html('');
            $('#project_value_icon').html('');
        }
           
        function fetch_data(page, sort_type, sort_by, query) {

            $.ajax({
                url: "{{ route('account-manager.payments.filter') }}",
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
</script> --}}
@endpush