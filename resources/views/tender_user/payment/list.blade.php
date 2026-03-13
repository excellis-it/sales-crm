@extends('tender_user.layouts.master')
@section('title')
    Payment History - {{ env('APP_NAME') }}
@endsection
@push('styles')
    <style>
        .btn-tender {
            background: linear-gradient(135deg, #ff9b44 0%, #fc6075 100%) !important;
            border: none !important;
            color: #fff !important;
            font-weight: 600 !important;
            border-radius: 25px !important;
            padding: 10px 20px !important;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 15px rgba(255, 155, 68, 0.2) !important;
            transition: all 0.3s ease !important;
        }
        .btn-tender:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(255, 155, 68, 0.3) !important;
        }
        .custom-table thead tr {
            background-color: #fdf2e9 !important;
        }
        .custom-table thead th {
            font-weight: bold !important;
            color: #333 !important;
        }
        .badge-paid { background-color: #d4edda; color: #155724; }
        .badge-due { background-color: #fff3cd; color: #856404; }
    </style>
@endpush
@section('content')
    <section id="loading">
        <div id="loading-content"></div>
    </section>
    <div class="page-wrapper">
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Payment History</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('tender-user.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Payment History</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->

            <!-- Search Filter -->
            <form action="{{ route('tender-user.payments.index') }}" method="GET" id="search_form">
                <div class="row filter-row">
                    <div class="col-sm-6 col-md-4">
                        <div class="form-group form-focus">
                            <input type="text" name="search" class="form-control floating" value="{{ request('search') }}">
                            <label class="focus-label">Search Milestone / Tender</label>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <div class="form-group form-focus select-focus">
                            <select class="select floating" name="payment_status">
                                <option value="">Select Status</option>
                                <option value="Paid" {{ request('payment_status') == 'Paid' ? 'selected' : '' }}>Paid</option>
                                <option value="Due" {{ request('payment_status') == 'Due' ? 'selected' : '' }}>Due</option>
                            </select>
                            <label class="focus-label">Payment Status</label>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-2">
                        <button type="submit" class="btn btn-tender w-100"> SEARCH </button>
                    </div>
                    <div class="col-sm-6 col-md-2">
                        <button type="button" id="btn_refresh" class="btn btn-tender w-100" style="background: #333 !important; box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important;"> REFRESH </button>
                    </div>
                </div>
            </form>
            <!-- /Search Filter -->

            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive" id="payment_table_container">
                        @include('tender_user.payment.table')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            function fetchData(url) {
                $('#loading').addClass('loading');
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(response) {
                        $('#payment_table_container').html(response);
                        $('#loading').removeClass('loading');
                        window.history.pushState({}, '', url);
                    },
                    error: function(xhr) {
                        $('#loading').removeClass('loading');
                        toastr.error('Failed to load data.');
                    }
                });
            }

            $('#search_form').on('submit', function(e) {
                e.preventDefault();
                var url = $(this).attr('action') + '?' + $(this).serialize();
                fetchData(url);
            });

            $('#btn_refresh').click(function() {
                $('#search_form')[0].reset();
                $('.select').val('').trigger('change');
                fetchData("{{ route('tender-user.payments.index') }}");
            });

            $(document).on('click', '.pagination a', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                fetchData(url);
            });
        });
    </script>
@endpush
