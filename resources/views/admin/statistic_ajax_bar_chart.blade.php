
<script src="{{ asset('admin_assets/js/Chart.min.js') }}"></script>
<canvas id="canvas"></canvas>
@if ($type == 'YearEarn')
    <script>
        


        var chartOptions = {
            responsive: true,
            bezierCurve: false,
            maintainAspectRatio: false,
            scales: {
                xAxes: [{
                    gridLines: {
                        color: "rgba(180, 208, 224, 0.5)",
                        zeroLineColor: "rgba(180, 208, 224, 0.5)",
                    }
                }],
                yAxes: [{
                    gridLines: {
                        color: "rgba(180, 208, 224, 0.5)",
                        zeroLineColor: "rgba(180, 208, 224, 0.5)",
                        borderDash: [8, 4],
                    }
                }]
            },
            legend: {
                display: true,
                position: "top",
                labels: {
                    usePointStyle: true,
                    boxWidth: 6,
                    fontColor: "#758590",
                    fontSize: 14
                }
            },
            plugins: {
                datalabels: {
                    display: false
                }
            },
        };
       
        var barChartData = {
            labels: [
                @foreach ($labels as $label)
                    "{{ $label }}",
                @endforeach
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
                    data: [{{ $goal['prospect_january'] ?? 0 }},
                        {{ $goal['prospect_february'] ?? 0 }}, {{ $goal['prospect_march'] ?? 0 }},
                        {{ $goal['prospect_april'] ?? 0 }}, {{ $goal['prospect_may'] ?? 0 }},
                        {{ $goal['prospect_june'] ?? 0 }}, {{ $goal['prospect_july'] ?? 0 }},
                        {{ $goal['prospect_august'] ?? 0 }}, {{ $goal['prospect_september'] ?? 0 }},
                        {{ $goal['prospect_october'] ?? 0 }}, {{ $goal['prospect_november'] ?? 0 }},
                        {{ $goal['prospect_december'] ?? 0 }}
                    ]
                },
            ]
        };

        var ctx = document.getElementById("canvas").getContext("2d");
        window.myBar = new Chart(ctx, {
            type: "bar",
            data: barChartData,
            options: chartOptions
        });
    </script>
@elseif($type == 'MonthEarn')

    <script>
         var chartOptions = {
            responsive: true,
            bezierCurve: false,
            maintainAspectRatio: false,
            scales: {
                xAxes: [{
                    gridLines: {
                        color: "rgba(180, 208, 224, 0.5)",
                        zeroLineColor: "rgba(180, 208, 224, 0.5)",
                    }
                }],
                yAxes: [{
                    gridLines: {
                        color: "rgba(180, 208, 224, 0.5)",
                        zeroLineColor: "rgba(180, 208, 224, 0.5)",
                        borderDash: [8, 4],
                    }
                }]
            },
            legend: {
                display: true,
                position: "top",
                labels: {
                    usePointStyle: true,
                    boxWidth: 6,
                    fontColor: "#758590",
                    fontSize: 14
                }
            },
            plugins: {
                datalabels: {
                    display: false
                }
            },
        };

       

        var barChartData = {
            labels: [
                @foreach ($labels as $label)
                    "{{ $label }}",
                @endforeach
            ],
            datasets: [{
                    label: "Gross Sales",
                    backgroundColor: "#fa8d35",
                    borderColor: "#fa8d35",
                    borderWidth: 1,
                    data: [@foreach ($gross_goals as $item)
                        {{ $item }},
                    @endforeach
                    ]
                },
                {
                    label: "Revenue",
                    backgroundColor: "#ad1e23",
                    borderColor: "#ad1e23",
                    borderWidth: 1,
                    data: [@foreach ($net_goals as $item)
                        {{ $item }},
                    @endforeach
                    ]
                },
                {
                    label: "Prospect",
                    backgroundColor: "#6c757d",
                    borderColor: "#6c757d",
                    borderWidth: 1,
                    data: [@foreach ($prospects as $item)
                        {{ $item }},
                    @endforeach
                    ]
                },
            ]
        };

        var ctx = document.getElementById("canvas").getContext("2d");
        window.myBar = new Chart(ctx, {
            type: "bar",
            data: barChartData,
            options: chartOptions
        });
    </script>

@else
<script>
    var chartOptions = {
       responsive: true,
       bezierCurve: false,
       maintainAspectRatio: false,
       scales: {
           xAxes: [{
               gridLines: {
                   color: "rgba(180, 208, 224, 0.5)",
                   zeroLineColor: "rgba(180, 208, 224, 0.5)",
               }
           }],
           yAxes: [{
               gridLines: {
                   color: "rgba(180, 208, 224, 0.5)",
                   zeroLineColor: "rgba(180, 208, 224, 0.5)",
                   borderDash: [8, 4],
               }
           }]
       },
       legend: {
           display: true,
           position: "top",
           labels: {
               usePointStyle: true,
               boxWidth: 6,
               fontColor: "#758590",
               fontSize: 14
           }
       },
       plugins: {
           datalabels: {
               display: false
           }
       },
   };

  

   var barChartData = {
       labels: [
           @foreach ($labels as $label)
               "{{ $label }}",
           @endforeach
       ],
       datasets: [{
               label: "Gross Sales",
               backgroundColor: "#fa8d35",
               borderColor: "#fa8d35",
               borderWidth: 1,
               data: [@foreach ($gross_goals as $item)
                   {{ $item }},
               @endforeach
               ]
           },
           {
               label: "Revenue",
               backgroundColor: "#ad1e23",
               borderColor: "#ad1e23",
               borderWidth: 1,
               data: [@foreach ($net_goals as $item)
                   {{ $item }},
               @endforeach
               ]
           },
           {
               label: "Prospect",
               backgroundColor: "#6c757d",
               borderColor: "#6c757d",
               borderWidth: 1,
               data: [@foreach ($prospects as $item)
                   {{ $item }},
               @endforeach
               ]
           },
       ]
   };

   var ctx = document.getElementById("canvas").getContext("2d");
   window.myBar = new Chart(ctx, {
       type: "bar",
       data: barChartData,
       options: chartOptions
   });
</script>

@endif
