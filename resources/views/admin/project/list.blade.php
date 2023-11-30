@extends('admin.layouts.master')
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
                            <li class="breadcrumb-item"><a href="{{ route('sales-projects.index') }}">Projects</a></li>
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
                                <a href="{{ route('sales-projects.create') }}" class="btn px-5 submit-btn"><i
                                        class="fa fa-plus"></i> Add a
                                    Project</a>
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
                    </div>
                    <div class="table-responsive" id="project-data">
                        <table class="dd table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="sorting" data-tippy-content="Sort by Sale Date" data-sorting_type="desc"
                                    data-column_name="sale_date" style="cursor: pointer"> Date <span id="date_icon"></span></th>
                                    <th data-tippy-content="Cant't sort by Sale By" style="cursor: pointer"> Sale By</th>
                                    <th class="sorting" data-tippy-content="Sort by Project Name" data-sorting_type="asc"
                                    data-column_name="project_name" style="cursor: pointer"> Project Name <span id="project_name_icon"></span></th>
                                    <th class="sorting" data-tippy-content="Sort by Client Name" data-sorting_type="asc"
                                    data-column_name="client_name" style="cursor: pointer"> Client Name <span id="client_name_icon"></span></th>
                                    <th class="sorting" data-tippy-content="Sort by Phone" data-sorting_type="asc"
                                    data-column_name="phone" style="cursor: pointer"> Phone <span id="phone_icon"></span></th>
                                    <th class="sorting" data-tippy-content="Sort by Project Value" data-sorting_type="asc"
                                    data-column_name="project_value" style="cursor: pointer"> Project Value <span id="project_value_icon"></span></th>
                                    <th class="sorting" data-tippy-content="Sort by Project Upfront" data-sorting_type="asc"
                                    data-column_name="project_upfront" style="cursor: pointer"> Project Upfront <span id="project_upfront_icon"></span></th>
                                    {{-- <th> </th> --}}
                                    <th class="sorting" data-tippy-content="Sort by Currency" data-sorting_type="asc"
                                    data-column_name="currency" style="cursor: pointer"> Currency <span id="currency_icon"></span></th>
                                    <th data-tippy-content="Cant't sort by Payment Mode" style="cursor: pointer"> Payment Mode</th>
                                    <th data-tippy-content="Cant't sort by Due Amount" style="cursor: pointer"> Due Amount</th>
                                    <th> Status</th>
                                    <th> Action </th>
                                </tr>
                            </thead>
                            <tbody>
                                @include('admin.project.table')

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
    $(document).on('click', '.view-route', function() {
        window.location.href = $(this).data('route');
    });
</script>
 {{-- trippy cdn link --}}
 <script src="https://unpkg.com/popper.js@1"></script>
 <script src="https://unpkg.com/tippy.js@5"></script>
 {{-- trippy --}}
 <script>
     tippy('[data-tippy-content]', {
         allowHTML: true,
         placement: 'bottom',
         theme: 'light-theme',
     });
 </script>
<script>
    $(document).ready(function() {

        function clear_icon() {
            $('#date_icon').html('');
            $('#project_name_icon').html('');
            $('#client_name_icon').html('');
            $('#phone_icon').html('');
            $('#project_value_icon').html('');
            $('#project_upfront_icon').html('');
            $('#currency_icon').html('');
        }

        function fetch_data(page, sort_type, sort_by, query) {
            $.ajax({
                url: "{{ route('sales-projects.fetch-data') }}",
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
    {{-- <script>
        $('.toggle-class').change(function() {
            var status = $(this).prop('checked') == true ? 1 : 0;
            var user_id = $(this).data('id');

            $.ajax({
                type: "GET",
                dataType: "json",
                url: '{{ route('sales-projects.change-status') }}',
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
        $(document).ready(function() {
            //how to place holder in "jquery datatable" search box
            $('#myTable_filter input').attr("placeholder", "Search");
        });
    </script>
@endpush
