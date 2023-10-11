@extends('sales_excecutive.layouts.master')
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
                    @include('sales_excecutive.prospect.show-details')
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
                            <li class="breadcrumb-item"><a href="{{ route('prospects.index') }}">Prospects</a></li>
                            <li class="breadcrumb-item active">List</li>
                        </ul>
                    </div>

                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h4 class="mb-0">Prospects Details</h4>
                            </div>
                            <div class="col-md-6 text-end">
                                <a href="{{ route('prospects.create') }}" class="btn px-5 submit-btn"><i
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
                    <div class="table-responsive" id="show-prospect">
                        @include('sales_excecutive.prospect.table')
                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            //Default data table
            $('#myTable').DataTable({
                "aaSorting": [],
                "columnDefs": [{
                        "orderable": false,
                        "targets": [10]
                    },
                    {
                        "orderable": true,
                        "targets": [0, 1, 2, 5, 6, 7, 8, 9]
                    }
                ]
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
    <script></script>
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
            var url = "{{ route('prospects.filter') }}";
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

@endpush
