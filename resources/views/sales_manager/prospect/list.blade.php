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
                        <li class="breadcrumb-item"><a href="{{ route('sales-manager.prospects.index') }}">Prospects</a></li>
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
                            <a href="{{ route('sales-manager.prospects.create') }}" class="btn px-5 submit-btn"><i class="fa fa-plus"></i> Add a
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
                                <h5>{{ count($prospects) }}</h5>
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
                                        class="form-control">
                                    <a href="javascript:void(0)" class="prod-search-icon"><i
                                            class="ph ph-magnifying-glass"></i></a>
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
                   
                    @include('sales_manager.prospect.table')
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
            url: "{{ route('sales-manager.prospects.filter') }}",
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
    $(document).on('click', '.desin-filter', function(e) {
        var status = $(this).data('value');
        //remove active class from all
        $('.desin-filter').removeClass('active-filter');
        //add active class to clicked
        $(this).addClass('active-filter');
        var url = "{{ route('sales-manager.prospects.status-filter') }}";
        $.ajax({
            type: "GET",
            dataType: "json",
            url: url,
            data: {
                'status': status,
            },
            success: function(resp) {
                $('#show-prospect').html(resp.view);

            }
        });
    });
</script>
{{-- <script>
    $(document).ready(function() {
       //how to place holder in "jquery datatable" search box
        $('#myTable_filter input').attr("placeholder", "Search");
    });


</script> --}}
@endpush
