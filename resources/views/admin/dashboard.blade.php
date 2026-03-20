@extends('admin.layouts.master')
@section('title')
    Dashboard - {{ env('APP_NAME') }} admin
@endsection
@push('styles')
    <style>
        .dataTables_filter {
            margin-bottom: 10px !important;
        }

        #canvas {
            height: 20rem;
        }

        .chartjs-custom {
            position: relative;
            overflow: hidden;
            margin-right: auto;
            margin-left: auto
        }

        .hs-chartjs-tooltip-wrap {
            position: absolute;
            z-index: 3;
            transition: opacity .2s ease-in-out, left .2s ease, top .2s ease
        }

        .hs-chartjs-tooltip {
            position: relative;
            font-size: .75rem;
            background-color: #132144;
            border-radius: .3125rem;
            padding: .54688rem .875rem;
            transition: opacity .2s ease-in-out, left .2s ease, top .2s ease, top 0s
        }

        .hs-chartjs-tooltip::before {
            position: absolute;
            left: calc(50% - .5rem);
            bottom: -.4375rem;
            width: 1rem;
            height: .5rem;
            content: "";
            background-image: url("data:image/svg+xml,%3Csvg width='1rem' height='0.5rem' xmlns='http://www.w3.org/2000/svg' x='0px' y='0px' viewBox='0 0 50 22.49'%3E%3Cpath fill='%23132144' d='M0,0h50L31.87,19.65c-3.45,3.73-9.33,3.79-12.85,0.13L0,0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: center center;
            background-size: 1rem .5rem
        }

        .hs-chartjs-tooltip-left {
            left: -130%
        }

        .hs-chartjs-tooltip-left::before {
            top: 50%;
            -webkit-transform: translateY(-50%);
            transform: translateY(-50%);
            right: -.6875rem;
            left: auto;
            -webkit-transform: translateY(-50%) rotate(270deg);
            transform: translateY(-50%) rotate(270deg)
        }

        .hs-chartjs-tooltip-right {
            left: 30%
        }

        .hs-chartjs-tooltip-right::before {
            top: 50%;
            -webkit-transform: translateY(-50%);
            transform: translateY(-50%);
            left: -.6875rem;
            right: auto;
            -webkit-transform: translateY(-50%) rotate(90deg);
            transform: translateY(-50%) rotate(90deg)
        }

        .hs-chartjs-tooltip-header {
            color: rgba(255, 255, 255, .7);
            font-weight: 600;
            white-space: nowrap
        }

        .hs-chartjs-tooltip-body {
            color: #fff
        }

        .chartjs-doughnut-custom {
            position: relative
        }

        .chartjs-doughnut-custom-stat {
            position: absolute;
            top: 8rem;
            left: 50%;
            -webkit-transform: translateX(-50%);
            transform: translateX(-50%)
        }

        .chartjs-matrix-custom {
            position: relative
        }

        .hs-chartjs-matrix-legend {
            display: inline-block;
            position: relative;
            height: 2.5rem;
            list-style: none;
            padding-left: 0
        }

        .hs-chartjs-matrix-legend-item {
            width: .625rem;
            height: .625rem;
            display: inline-block
        }

        .hs-chartjs-matrix-legend-min {
            position: absolute;
            left: 0;
            bottom: 0
        }

        .hs-chartjs-matrix-legend-max {
            position: absolute;
            right: 0;
            bottom: 0
        }
    </style>
@endpush

@section('content')
    @php
        $totalProspects = $count['prospects'] == 0 ? 1 : $count['prospects'];
        $winProspects = $count['win'] == 0 ? 1 : $count['win'];
        $percentage['win'] = round(($winProspects / $totalProspects) * 100);
        $percentage['follow_up'] = round(($count['follow_up'] / $totalProspects) * 100);
        $percentage['sent_proposal'] = round(($count['sent_proposal'] / $totalProspects) * 100);
        $percentage['close'] = round(($count['close'] / $totalProspects) * 100);
    @endphp
    <style>
        /* New Premium Styles */
        .dash-card {
            border: none !important;
            border-radius: 12px !important;
            background: #fff;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05) !important;
            border-bottom: 3px solid transparent !important;
        }

        .dash-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1) !important;
        }

        .dash-card.card-primary:hover {
            border-bottom-color: #f37e20 !important;
        }

        .dash-card.card-success:hover {
            border-bottom-color: #28a745 !important;
        }

        .dash-card.card-info:hover {
            border-bottom-color: #17a2b8 !important;
        }

        .dash-card.card-purple:hover {
            border-bottom-color: #6f42c1 !important;
        }

        .dash-card.card-danger:hover {
            border-bottom-color: #dc3545 !important;
        }

        .dash-card.card-black:hover {
            border-bottom-color: #343a40 !important;
        }

        .dash-card-icon {
            font-size: 24px;
            opacity: 0.8;
        }

        .dash-card-link {
            text-decoration: none !important;
            color: inherit !important;
            display: block;
        }

        .dash-icon-box {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            margin-bottom: 0px;
        }

        .bg-pale-orange {
            background: rgba(243, 126, 32, 0.1);
            color: #f37e20;
        }

        .bg-pale-green {
            background: rgba(40, 167, 69, 0.1);
            color: #28a745;
        }

        .bg-pale-blue {
            background: rgba(23, 162, 184, 0.1);
            color: #17a2b8;
        }

        .bg-pale-purple {
            background: rgba(111, 66, 193, 0.1);
            color: #6f42c1;
        }

        .bg-pale-red {
            background: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }

        .bg-pale-black {
            background: rgba(52, 58, 64, 0.1);
            color: #343a40;
        }

        .dash-title {
            font-size: 0.85rem;
            color: #777;
            font-weight: 500;
            margin-bottom: 4px;
        }

        .dash-count {
            font-size: 1.4rem;
            font-weight: 700;
            color: #333;
        }

        @media (min-width: 992px) {
            .col-lg-20 {
                flex: 0 0 20%;
                max-width: 20%;
            }
        }
    </style>
    <section id="loading">
        <div id="loading-content"></div>
    </section>
    <div class="page-wrapper">

        <div class="content container-fluid">

            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">Welcome Admin Panel!</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="card mb-30">
                <div class="card-body" style="position: relative;">
                    <div class="row align-items-center mb-4">
                        <div class="col-lg-6">
                            <h5 class="card-title mb-0">Sales Analytics</h5>
                        </div>
                        <div class="col-lg-6 text-end">
                            <select class="custom-select w-auto" id="top-stats-filter">
                                <option value="overall">Overall statistics</option>
                                <option value="today">Todays Statistics</option>
                                <option value="this_month" selected>This Months Statistics</option>
                                <option value="this_week">This Weeks Statistics</option>
                                <option value="this_year">This Years Statistics</option>
                            </select>
                        </div>
                    </div>
                    <div id="dashboard-stats-container">
                        @include('admin.dashboard_stats_cards')
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-lg-12">
                    <div class="card mb-30">
                        <div class="card-body" style="position: relative;">
                            <div class="row justify-content-center">
                                <div class="col-lg-6">
                                    <h5 class="card-title">Statistics</h5>
                                </div>
                                <div class="col-lg-6 text-end">
                                    <ul class="option-select-btn">
                                        <li>
                                            <label class="basic-box-shadow">
                                                <input type="radio" name="statistics2" hidden="" checked="">
                                                <span data-earn-type="yearEarn" class="earningStatisticsUpdate">This
                                                    year</span>
                                            </label>
                                        </li>
                                        <li>
                                            <label class="basic-box-shadow">
                                                <input type="radio" name="statistics2" hidden="">
                                                <span data-earn-type="MonthEarn" class="earningStatisticsUpdate">This
                                                    month</span>
                                            </label>
                                        </li>
                                        <li>
                                            <label class="basic-box-shadow">
                                                <input type="radio" name="statistics2" hidden="">
                                                <span data-earn-type="WeekEarn" class="earningStatisticsUpdate">This
                                                    week</span>
                                            </label>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <div>
                                <div class="resize-triggers">
                                    <div class="expand-trigger chartjs-custom" id="statisticAjaxBarChart">

                                        {{-- <canvas id="canvas"></canvas> --}}
                                        @include('admin.statistic_ajax_bar_chart')
                                    </div>
                                    <div class="contract-trigger"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-4">
                    <div class="card mb-30">
                        <div class="card-body" style="position: relative;">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h5 class="card-title mb-0">Top Customer</h5>
                                <a href="{{ route('customers.index') }}" class="view_all">View All</a>
                            </div>
                            <div class="grid-card-wrap">
                                @foreach ($top_customers as $item)
                                    <div class="cursor-pointer">
                                        <div class="grid-card basic-box-shadow">
                                            <div class="text-center">
                                                <img class="avatar rounded-circle avatar-lg"
                                                    src="{{ asset('admin_assets/img/profiles/avatar-21.jpg') }}">
                                            </div>
                                            <h5 class="mb-0">{{ $item['customer_name'] }}</h5>
                                            <div class="orders-count d-flex gap-1">
                                                <div>Value : </div>
                                                <div>$ {{ $item->projects()->sum('project_value') ?? '' }}</div>
                                            </div>
                                            <div class="orders-count d-flex gap-1">
                                                <div>No Of Projects : </div>
                                                <div>{{ $item->projects()->count() ?? '' }}</div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card mb-30">
                        <div class="card-body" style="position: relative;">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h5 class="card-title mb-0">Latest Projects</h5>
                                <a href="{{ route('sales-projects.index') }}" class="view_all">View All</a>
                            </div>
                            <div class="table-responsive dashboard_mini_table">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Project Name</th>
                                            <th>Project Type</th>
                                            <th>Project Value</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (count($projects) == 0)
                                            <tr>
                                                <td colspan="3" class="text-center">No Project Found</td>
                                            </tr>
                                        @else
                                            @foreach ($projects as $key => $project)
                                                <tr>
                                                    <td>
                                                        {{ $project->business_name }}
                                                    </td>
                                                    <td>
                                                        @foreach ($project->projectTypes as $index => $projectType)
                                                            <span
                                                                class="">{{ Str::limit($projectType->type, 20) }}</span>
                                                            @if (!$loop->last)
                                                                <span>,</span>
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                    <td>
                                                        <div class="project_value">
                                                            <h5 class="shop-sell">$ {{ $project->project_value }}</h5>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card mb-30">
                        <div class="card-body" style="position: relative;">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h5 class="card-title mb-0">Top Performers</h5>
                                {{-- <a href="" class="view_all">View All</a> --}}
                                <select class="custom-select w-auto duration" name="duration">
                                    <option data-duration="Monthly" value="Monthly">Monthly</option>
                                    <option data-duration="Yearly" value="Yearly">Yearly</option>
                                </select>
                            </div>

                            <div class="table-responsive dashboard_mini_table" id="top-performer">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Employee Name</th>
                                            <th>Employee Designation</th>
                                            <th>Project Value</th>
                                        </tr>
                                    </thead>
                                    <tbody class="perfom-filter">

                                        @include('admin.dashboard_performer_table')
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row">

                <div class="col-lg-6">
                    <div class="card mb-30">
                        <div class="card-body" style="position: relative;">
                            <div class="">
                                <h5 class="card-title">Prospects Statistics</h5>
                            </div>

                            <div class="resize-triggers py-5">
                                <div class="expand-trigger">
                                    <canvas id="oilChart"></canvas>

                                </div>
                                <div class="contract-trigger"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card mb-30">
                        <div class="card-body" style="position: relative;">
                            <div class="">
                                <h5 class="card-title">Last 10 Prospects</h5>
                            </div>
                            <div class="row justify-content-end">
                                <div class="col-md-6">
                                    <div class="row g-1 justify-content-end">
                                        <div class="col-md-8 pr-0">
                                            <div class="search-field prod-search">
                                                <input type="text" name="search" id="search"
                                                    placeholder="search..." required class="form-control rounded_search">
                                                <a href="javascript:void(0)" class="prod-search-icon submit_search"><i
                                                        class="fa fa-search"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table id="myTable" class="dd table table-striped  table-hover" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th class="sorting" data-tippy-content="Sort by Sale Date"
                                                data-sorting_type="desc" data-column_name="sale_date"
                                                style="cursor: pointer"> Date <span id="date_icon"><span
                                                        class="fa fa-sort-down"></span></span></th>
                                            <th class="sorting" data-tippy-content="Sort by Business Name"
                                                data-sorting_type="asc" data-column_name="business_name"
                                                style="cursor: pointer">Business Name <span id="business_name_icon"><span
                                                        class="fa fa-sort-down"></span></span></th>
                                            <th class="sorting" data-tippy-content="Sort by Client Name"
                                                data-sorting_type="asc" data-column_name="client_name"
                                                style="cursor: pointer">Client Name <span id="client_name_icon"><span
                                                        class="fa fa-sort-down"></span></span>
                                            </th>
                                            <th class="sorting" data-tippy-content="Sort by Phone"
                                                data-sorting_type="asc" data-column_name="phone" style="cursor: pointer">
                                                Email <span id="email_icon"><span class="fa fa-sort-down"></span></span>
                                            </th>
                                            <th class="sorting" data-tippy-content="Sort by Phone"
                                                data-sorting_type="asc" data-column_name="phone" style="cursor: pointer">
                                                Phone <span id="phone_icon"><span class="fa fa-sort-down"></span></span>
                                            </th>
                                            <th data-tippy-content="Cant't sort by Transfer taken by"
                                                style="cursor: pointer">Transfer Taken By</th>
                                            <th>Status</th>
                                            <th data-tippy-content="Cant't sort by Service offer" style="cursor: pointer">
                                                Service Offered</th>
                                            <th class="sorting" data-tippy-content="Sort by Follow"
                                                data-sorting_type="asc" data-column_name="follow"
                                                style="cursor: pointer">Followup Date <span id="follow_icon"><span
                                                        class="fa fa-sort-down"></span></span></th>
                                            <th class="sorting" data-tippy-content="Sort by Price quoted"
                                                data-sorting_type="asc" data-column_name="price" style="cursor: pointer">
                                                Price Quoted <span id="price_quoted_icon"><span
                                                        class="fa fa-sort-down"></span></span></th>
                                        </tr>
                                    </thead>
                                    <tbody class="prospect-filter">
                                        @include('admin.dashboard_prospect_table')
                                        {{-- @foreach ($prospects as $key => $prospect)
                                <tr>
                                    <td>
                                        {{ date('d M, Y', strtotime($prospect->created_at)) }}
                                    </td>
                                    <td>
                                        {{ $prospect->business_name }}
                                    </td>
                                    <td>
                                        {{ $prospect->client_name }}
                                    </td>
                                    <td>
                                        {{ $prospect->client_email }}
                                    </td>
                                    <td>
                                        {{ $prospect->client_phone }}
                                    </td>
                                    <td>
                                        {{ $prospect->transferTakenBy->name ?? '' }}
                                    </td>
                                    <td>
                                        @if ($prospect->status == 'Win')
                                            <span>On Board</span>
                                        @elseif ($prospect->status == 'Follow Up')
                                            <span>Follow Up</span>
                                        @elseif ($prospect->status == 'Sent Proposal')
                                            <span>Sent Proposal</span>
                                        @elseif ($prospect->status == 'Close')
                                            <span>Cancel</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $prospect->offered_for }}
                                    </td>



                                    <td>
                                        {{ date('d M, Y', strtotime($prospect->followup_date)) }}
                                    </td>
                                    <td>
                                        {{ $prospect->price_quote }}
                                    </td>


                                </tr>
                            @endforeach --}}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    </div>


@endsection

@push('scripts')
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.min.js"></script> --}}
    <script>
        function updateChart(type) {
            $('#statisticAjaxBarChart').css('opacity', '0.5');
            $.ajax({
                url: "{{ route('admin.dashboard.earning-statistics') }}",
                data: {
                    type: type
                },
                success: function(resp) {
                    $('#statisticAjaxBarChart').html(resp.view).css('opacity', '1');
                },
                error: function() {
                    $('#statisticAjaxBarChart').css('opacity', '1');
                }
            });
        }

        $(document).ready(function() {
            $(document).on('click', '.earningStatisticsUpdate', function(e) {
                var type = $(this).data('earn-type');
                updateChart(type);
            });

            $(document).on('change', '#top-stats-filter', function() {
                var type = $(this).val();
                $('#dashboard-stats-container').css('opacity', '0.5');
                $.ajax({
                    url: "{{ route('admin.dashboard.top-stats') }}",
                    data: {
                        type: type
                    },
                    success: function(resp) {
                        $('#dashboard-stats-container').html(resp.html).css('opacity', '1');

                        // Update pie chart
                        updatePieChart(resp.count.win, resp.count.follow_up, resp.count.sent_proposal, resp.count.close);

                        // Sync chart if applicable
                        let chartType = '';
                        if (type === 'this_month') chartType = 'MonthEarn';
                        if (type === 'this_year') chartType = 'yearEarn';
                        if (type === 'this_week') chartType = 'WeekEarn';

                        if (chartType) {
                            $('.earningStatisticsUpdate').each(function() {
                                if ($(this).data('earn-type') === chartType) {
                                    $(this).closest('label').find('input').prop('checked', true);
                                }
                            });
                            updateChart(chartType);
                        }
                    },
                    error: function() {
                        $('#dashboard-stats-container').css('opacity', '1');
                    }
                });
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            //how to place holder in "jquery datatable" search box
            $('#myTable_filter input').attr("placeholder", "Search");
        });
    </script>
    <script>
        var pieChart;
        function updatePieChart(win, follow_up, sent_proposal, close) {
            var oilCanvas = document.getElementById("oilChart");
            if (pieChart) {
                pieChart.data.datasets[0].data = [win, follow_up, sent_proposal, close];
                pieChart.update();
            } else {
                var oilData = {
                    labels: ["On Board", "Follow Up", "Sent Proposal", "Close"],
                    datasets: [{
                        data: [win, follow_up, sent_proposal, close],
                        backgroundColor: ["#ad1e23", "#fa8d35", "#297dd7", "#6c757d"]
                    }]
                };
                pieChart = new Chart(oilCanvas, {
                    type: 'pie',
                    data: oilData,
                    options: {
                        legend: { display: true, position: 'bottom' }
                    }
                });
            }
        }

        $(document).ready(function() {
            updatePieChart({{ $count['win'] }}, {{ $count['follow_up'] }}, {{ $count['sent_proposal'] }}, {{ $count['close'] }});
        });
    </script>

    <script>
        $(document).ready(function() {
            function clear_icon() {
                $('#date_icon').html('');
                $('#business_name_icon').html('');
                $('#client_name_icon').html('');
                $('#email_icon').html('');
                $('#phone_icon').html('');
                $('#follow_icon').html('');
                $('#price_quoted_icon').html('');
                // $('#currency_icon').html('');
            }

            function fetch_data(page, sort_type, sort_by, query) {
                $.ajax({
                    url: "{{ route('admin.dashboard.prospect-fetch-data') }}",
                    data: {
                        page: page,
                        sortby: sort_by,
                        sorttype: sort_type,
                        query: query
                    },
                    success: function(data) {
                        $('.prospect-filter').html(data.data);
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
        $(document).on('change', '.duration', function() {
            var selectedOption = $('option:selected', this);
            var duration = selectedOption.data('duration');
            $.ajax({
                url: "{{ route('admin.dashboard.top-performer') }}",
                data: {
                    duration: duration
                },
                success: function(resp) {
                    $('.perfom-filter').html(resp.data);
                },
                error: function() {
                    console.log('alert');
                }
            });
        });
    </script>
@endpush
