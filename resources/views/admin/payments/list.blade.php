@extends('admin.layouts.master')
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
                            <li class="breadcrumb-item"><a href="{{ route('admin.payments.list') }}">Payments</a>
                            </li>
                            <li class="breadcrumb-item active">List</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="row g-2 align-items-center mb-3">
                        <div class="col-md-4">
                            <h4 class="mb-0">Payments List</h4>
                        </div>
                        <div class="col-md-8">
                            <div class="row g-2 justify-content-end">
                                <div class="col-md-3">
                                    <select name="year" id="year" class="form-control rounded_search">
                                        <option value="">All Years</option>
                                        @for ($i = 2023; $i <= date('Y') + 1; $i++)
                                            <option value="{{ $i }}" {{ date('Y') == $i ? 'selected' : '' }}>
                                                {{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select name="month" id="month" class="form-control rounded_search">
                                        <option value="">All Months</option>
                                        @foreach (range(1, 12) as $m)
                                            <option value="{{ sprintf('%02d', $m) }}"
                                                {{ date('m') == $m ? 'selected' : '' }}>
                                                {{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
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
                                    <th>Source</th>
                                    <th>Milestone Name </th>
                                    <th>Milestone value</th>
                                    <th>Payment mode </th>
                                    <th>Payment date </th>
                                    <th style="cursor: pointer">
                                        Download Invoice</th>

                                </tr>
                            </thead>
                            <tbody>

                                @include('admin.payments.table')

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
        function fetch_data(page, query, year, month) {
            $.ajax({
                url: "{{ route('admin.payments.filter') }}",
                data: {
                    page: page,
                    query: query,
                    year: year,
                    month: month
                },
                beforeSend: function() {
                    $('#project-data').css('opacity', '0.5');
                },
                success: function(resp) {
                    $('#project-data').css('opacity', '1');
                    $('tbody').html(resp.data);
                }
            });
        }

        $(document).on('change', '#year, #month', function() {
            var query = $('#search').val();
            var year = $('#year').val();
            var month = $('#month').val();
            fetch_data(1, query, year, month);
        });

        $(document).on('click', '.pagination a', function(e) {
            e.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            var query = $('#search').val();
            var year = $('#year').val();
            var month = $('#month').val();
            fetch_data(page, query, year, month);
        });

        $(document).on('keyup', '#search', function(e) {
            e.preventDefault();
            var query = $(this).val();
            var year = $('#year').val();
            var month = $('#month').val();
            fetch_data(1, query, year, month);
        });
        
        // Initial fetch with default selected year/month
        var query = $('#search').val();
        var year = $('#year').val();
        var month = $('#month').val();
        fetch_data(1, query, year, month);
    });
</script>
@endpush
