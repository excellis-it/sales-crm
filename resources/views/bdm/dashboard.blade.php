@extends('bdm.layouts.master')
@section('title')
    Dashboard - {{ env('APP_NAME') }}
@endsection
@push('styles')
    <style>
        .dataTables_filter {
            margin-bottom: 10px !important;
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
    <div class="page-wrapper">

        <div class="content container-fluid">

            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">Welcome BDM Panel!</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row">
                @if ($goal['gross_goals'])
                    <div class="col-lg-3 col-sm-6">
                        <div class="stats-card-one mb-30">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="mb-10 line-height-1">Gross Sales</p>
                                    <h3 class="mb-0 fs-25">
                                        {{ $goal['gross_goals']['goals_amount'] ? '$' . $goal['gross_goals']['goals_amount'] : 'N/A' }}
                                        / ${{ $goal['gross_goals']['goals_achieve'] ?? 0 }} </h3>
                                </div>
                                <?php
                                $target = $goal['gross_goals']['goals_amount'] ?? 0;
                                $achieve = $goal['gross_goals']['goals_achieve'] ?? 0;
                                // round percentage
                                $percentage['gross_goals'] = round(($achieve / $target) * 100, 0);
                                ?>
                                <span class="badge badge-cyan fs-12">
                                    <i class="icofont-swoosh-up"></i>
                                    <span class="fw-600 m-l-5">{{ $percentage['gross_goals'] ?? 0 }}%</span>
                                </span>
                            </div>

                            <div class="mt-15">
                                <div class="d-flex justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <span>Monthly Goal</span>
                                    </div>
                                    <span class="fw-600">{{ $percentage['gross_goals'] ?? 0 }}%</span>
                                </div>

                                <div class="progress progress-sm mt-1">
                                    <div class="progress-bar bg-primary"
                                        style="width: {{ $percentage['gross_goals'] ?? 0 }}%">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($goal['net_goals'])
                    <div class="col-lg-3 col-sm-6">
                        <div class="stats-card-one mb-30">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="mb-10 line-height-1">Revenue</p>
                                    <h3 class="mb-0 fs-25">
                                        {{ $goal['net_goals']['goals_amount'] ? '$' . $goal['net_goals']['goals_amount'] : 'N/A' }}
                                        / ${{ $goal['net_goals']['goals_achieve'] ?? 0 }} </h3>
                                </div>
                                <?php
                                $target = $goal['net_goals']['goals_amount'] ?? 0;
                                $achieve = $goal['net_goals']['goals_achieve'] ?? 0;
                                // round percentage
                                $percentage['net_goals'] = round(($achieve / $target) * 100, 0);
                                ?>
                                <span class="badge badge-cyan font-size-12">
                                    <i class="icofont-swoosh-up"></i>
                                    <span class="fw-600 m-l-5">{{ $percentage['net_goals'] ?? 0 }}%</span>
                                </span>
                            </div>

                            <div class="mt-15">
                                <div class="d-flex justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <span>Monthly Goal</span>
                                    </div>
                                    <span class="fw-600">{{ $percentage['net_goals'] ?? 0 }}%</span>
                                </div>

                                <div class="progress progress-sm mt-1">
                                    <div class="progress-bar bg-danger"
                                        style="width: {{ $percentage['net_goals'] ?? 0 }}%">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="col-lg-3 col-sm-6">
                    <div class="stats-card-one mb-30">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="mb-10 line-height-1">New Customers</p>
                                <h3 class="mb-0 fs-25">{{ $count['win'] }}</h3>
                            </div>

                            <span class="badge badge-red font-size-12">
                                <i class="icofont-swoosh-down"></i>
                                <span class="fw-600 m-l-5">{{ $percentage['win'] }}%</span>
                            </span>
                        </div>

                        <div class="mt-15">
                            <div class="d-flex justify-content-between">
                                <div class="d-flex align-items-center">
                                    <span>Monthly Goal</span>
                                </div>
                                <span class="fw-600">{{ $percentage['win'] }}%</span>
                            </div>

                            <div class="progress progress-sm mt-1">
                                <div class="progress-bar bg-purple" style="width: {{ $percentage['win'] }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="stats-card-one mb-30">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="mb-10 line-height-1">Total Projects</p>
                                <h3 class="mb-0 fs-25">{{ $count['projects'] }}</h3>
                            </div>

                            <span class="badge badge-red font-size-12">
                                <i class="icofont-swoosh-down"></i>
                                <span class="fw-600 m-l-5">100%</span>
                            </span>
                        </div>

                        <div class="mt-15">
                            <div class="d-flex justify-content-between">
                                <div class="d-flex align-items-center">
                                    <span>Monthly Goal</span>
                                </div>
                                <span class="fw-600">100%</span>
                            </div>

                            <div class="progress progress-sm mt-1">
                                <div class="progress-bar bg-black" style="width: 100%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="card mb-30">
                        <div class="card-body" style="position: relative;">
                            <div class="card-header">
                                <h5 class="card-title">Monthly Revenue</h5>
                            </div>


                            <div class="resize-triggers">
                                <div class="expand-trigger">
                                    <canvas id="canvas"></canvas>
                                </div>
                                <div class="contract-trigger"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card mb-30">
                        <div class="card-body" style="position: relative;">
                            <div class="card-header">
                                <h5 class="card-title">Prospects Statistics</h5>
                            </div>


                            <div class="resize-triggers">
                                <div class="expand-trigger">
                                    <canvas id="oilChart"></canvas>

                                </div>
                                <div class="contract-trigger"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3">
                    <div class="card mb-30">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div class="d-flex align-items-center">
                                    <span>Total Prospects</span>
                                </div>

                                <span class="fw-600">{{ $count['prospects'] }}</span>
                            </div>

                            <div class="progress progress-sm mt-2">
                                <div class="progress-bar bg-primary" style="width: 100%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3">
                    <div class="card mb-30">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div class="d-flex align-items-center">
                                    <span>Follow Up Prospects</span>
                                </div>
                                <span class="fw-600">{{ $count['follow_up'] }}</span>
                            </div>

                            <div class="progress progress-sm mt-2">
                                <div class="progress-bar bg-danger" style="width: {{ $percentage['follow_up'] }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3">
                    <div class="card mb-30">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div class="d-flex align-items-center">
                                    <span>Sent Proposal Prospects</span>
                                </div>
                                <span class="fw-600">{{ $count['sent_proposal'] }}</span>
                            </div>

                            <div class="progress progress-sm mt-2">
                                <div class="progress-bar bg-danger" style="width: {{ $percentage['sent_proposal'] }}%">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3">
                    <div class="card mb-30">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div class="d-flex align-items-center">
                                    <span>Close Prospects</span>
                                </div>
                                <span class="fw-600">{{ $count['close'] }}</span>
                            </div>

                            <div class="progress progress-sm mt-2">
                                <div class="progress-bar bg-danger" style="width: {{ $percentage['close'] }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <table id="myTable" class="dd table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Business Name</th>
                                <th>Client Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Transfer Taken By</th>
                                <th>Status</th>
                                <th>Service Offered</th>
                                <th>Followup Date</th>
                                <th>Price Quoted</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($prospects as $key => $prospect)
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
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.min.js"></script>
    <script>
        var barChartData = {
            labels: [
                "Jan",
                "Febr",
                "Mar",
                "Apr",
                "May",
                "Jun",
                "Jul",
                'Aug',
                'Sept',
                'Oct',
                'Nov',
                'Dec'
            ],
            datasets: [{
                    label: "Gross Sales",
                    backgroundColor: "#fa8d35",
                    borderColor: "#fa8d35",
                    borderWidth: 1,
                    data: [{{ $goal['gross_goals_january'] ?? 0 }}, {{ $goal['gross_goals_february'] ?? 0 }},
                        {{ $goal['gross_goals_march'] ?? 0 }}, {{ $goal['gross_goals_april'] ?? 0 }},
                        {{ $goal['gross_goals_may'] ?? 0 }}, {{ $goal['gross_goals_june'] ?? 0 }},
                        {{ $goal['gross_goals_july'] ?? 0 }}, {{ $goal['gross_goals_august'] ?? 0 }},
                        {{ $goal['gross_goals_september'] ?? 0 }}, {{ $goal['gross_goals_october'] ?? 0 }},
                        {{ $goal['gross_goals_november'] ?? 0 }}, {{ $goal['gross_goals_december'] ?? 0 }}
                    ]
                },
                {
                    label: "Revenue",
                    backgroundColor: "#ad1e23",
                    borderColor: "#ad1e23",
                    borderWidth: 1,
                    data: [{{ $goal['net_goals_january'] ?? 0 }}, {{ $goal['net_goals_february'] ?? 0 }},
                        {{ $goal['net_goals_march'] ?? 0 }}, {{ $goal['net_goals_april'] ?? 0 }},
                        {{ $goal['net_goals_may'] ?? 0 }}, {{ $goal['net_goals_june'] ?? 0 }},
                        {{ $goal['net_goals_july'] ?? 0 }}, {{ $goal['net_goals_august'] ?? 0 }},
                        {{ $goal['net_goals_september'] ?? 0 }}, {{ $goal['net_goals_october'] ?? 0 }},
                        {{ $goal['net_goals_november'] ?? 0 }}, {{ $goal['net_goals_december'] ?? 0 }}
                    ]
                },
                {
                    label: "Prospect",
                    backgroundColor: "#6c757d",
                    borderColor: "#6c757d",
                    borderWidth: 1,
                    data: [{{ $goal['prospect_december'] ?? 0 }}, {{ $goal['prospect_january'] ?? 0 }},
                        {{ $goal['prospect_february'] ?? 0 }}, {{ $goal['prospect_march'] ?? 0 }},
                        {{ $goal['prospect_april'] ?? 0 }}, {{ $goal['prospect_may'] ?? 0 }},
                        {{ $goal['prospect_june'] ?? 0 }}, {{ $goal['prospect_july'] ?? 0 }},
                        {{ $goal['prospect_august'] ?? 0 }}, {{ $goal['prospect_september'] ?? 0 }},
                        {{ $goal['prospect_october'] ?? 0 }}, {{ $goal['prospect_november'] ?? 0 }}
                    ]
                },
            ]
        };

        var chartOptions = {
            responsive: true,
            legend: {
                position: "top"
            },
            title: {
                display: true,
                text: ""
            },
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }

        window.onload = function() {
            var ctx = document.getElementById("canvas").getContext("2d");
            window.myBar = new Chart(ctx, {
                type: "bar",
                data: barChartData,
                options: chartOptions
            });
        };
    </script>
    <script>
        $(document).ready(function() {
            //Default data table
            $('#myTable').DataTable({
                "aaSorting": [],
                "columnDefs": [{
                        "orderable": false,
                        "targets": []
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
        $(document).ready(function() {
            //how to place holder in "jquery datatable" search box
            $('#myTable_filter input').attr("placeholder", "Search");
        });
    </script>
    <script>
        var oilCanvas = document.getElementById("oilChart");

        Chart.defaults.global.defaultFontFamily = "Lato";
        Chart.defaults.global.defaultFontSize = 18;

        var oilData = {
            labels: [
                "On Board",
                "Follow Up",
                "Sent Proposal",
                "Close",
            ],
            datasets: [{
                data: [{{ $count['win'] }}, {{ $count['follow_up'] }}, {{ $count['sent_proposal'] }},
                    {{ $count['close'] }}
                ],
                backgroundColor: [
                    "#ad1e23",
                    "#fa8d35",
                    "#297dd7",
                    "#6c757d",
                ]
            }]
        };

        var pieChart = new Chart(oilCanvas, {
            type: 'pie',
            data: oilData
        });
    </script>
@endpush
