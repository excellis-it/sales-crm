@extends('sales_manager.layouts.master')
@section('title')
    All Project Goals Details - {{ env('APP_NAME') }}
@endsection
@push('styles')
    <style>
        .dataTables_filter {
            margin-bottom: 10px !important;
        }
        .exec-amount {
            font-size: 16px;
            font-weight: bold;
            background-color: #fff9e6;
            border: 1px solid #007bff;
            border-radius: 5px;
            padding: 8px;
        }
        #remaining_goal {
            font-size: 18px;
            font-weight: bold;
        }
        #total_sm_goal {
            font-size: 18px;
            font-weight: bold;
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
                        <h3 class="page-title">Project Goals</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('sales-manager.goals.index') }}">Project Goals</a></li>
                            <li class="breadcrumb-item active">List</li>
                        </ul>
                    </div>

                </div>
            </div>
            <div class="row">
                <div class="col-xl-12 mx-auto" id="goal-create">
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-4">
                            <h5 class="mb-4 text-uppercase">DISTRIBUTE GOALS</h5>
                            <hr class="mb-4">
                            <div class="row align-items-end mb-4">
                                <div class="col-md-5">
                                    <label for="goals_date" class="form-label fw-bold">Goal Month <span class="text-danger">*</span></label>
                                    <select name="goals_date" id="goals_date" class="form-select border-primary" style="height: 45px; border-radius: 5px;">
                                        <option value="">-- Select a month with assigned goals --</option>
                                        @foreach($available_months as $month)
                                            <option value="{{ $month->goals_date }}" data-gross="{{ $month->goals_amount }}">
                                                {{ date('F Y', strtotime($month->goals_date)) }} - Goal: ${{ number_format($month->goals_amount) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <button type="button" class="btn px-5 submit-btn" id="fetchDistributionBtn" style="height: 45px; border-radius: 5px; font-weight: 500;">Fetch Distribution</button>
                                </div>
                                <div class="col-md-4">
                                    <!-- Empty space for layout balance if needed -->
                                </div>
                            </div>

                            <div id="distribution-section" style="display:none;">
                                <!-- AJAX loaded form will appear here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card mt-4">
                        <div class="card-body">
                            <div class="card-title">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h4 class="mb-0">Team Goals</h4>
                                    </div>
                                    <div class="col-md-6 text-end">
                                        <a href="javascript:void(0);" class="btn px-5 submit-btn" id="add-btn"><i
                                                class="fa fa-sitemap me-2"></i> Manage Executive Goals</a>
                                    </div>
                                </div>
                            </div>

                            <hr />
                            <div class="row justify-content-end mb-3">
                                <div class="col-md-6">
                                    <div class="row g-1 justify-content-end">
                                        <div class="col-md-8 pr-0">
                                            <div class="input-group">
                                                <input type="text" name="search" id="search"
                                                    placeholder="Search execs, amounts..." class="form-control rounded_search border-primary">
                                                <span class="input-group-text bg-primary text-white" id="search-button"><i class="fa fa-search"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive" id="project_goals_data">
                                @include('sales_manager.goals.table')
                            </div>
                        </div>
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
            $('#goal-create').hide();
            // toogle create goal
            $('#add-btn').click(function() {
                $('#goals_date').val('');
                $('#distribution-section').hide();
                $('#goal-create').toggle();
            });

            // Calculate remaining amount function
            function calculateRemaining() {
                let totalStr = $('#total_sm_goal').text().replace(/,/g, '');
                let total = parseFloat(totalStr) || 0;
                let current = 0;
                $('.exec-amount').each(function(){
                    current += parseFloat($(this).val()) || 0;
                });
                let remaining = total - current;
                $('#remaining_goal').text(remaining.toFixed(2));

                if (remaining < -0.5) { // Allowing small float imprecision
                    $('#remaining_goal').parent().addClass('text-danger').removeClass('text-success');
                    $('#submitDistribution').prop('disabled', true);
                } else if (remaining > 0.5) {
                    $('#remaining_goal').parent().addClass('text-warning').removeClass('text-danger text-success');
                    $('#submitDistribution').prop('disabled', false);
                } else {
                    $('#remaining_goal').parent().addClass('text-success').removeClass('text-danger text-warning');
                    $('#submitDistribution').prop('disabled', false);
                }
            }

            // Fetch distribution
            $('#fetchDistributionBtn, #goals_date').on('change click', function(e) {
                if (e.type === 'click' && e.target.id !== 'fetchDistributionBtn') return;

                var goals_date = $('#goals_date').val();
                if (!goals_date) {
                    $('#distribution-section').slideUp();
                    return;
                }

                $('#loading').addClass('loading');
                $('#loading-content').addClass('loading-content');

                $.ajax({
                    url: "{{ route('sales-manager.goals.get-distribution') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        goals_date: goals_date
                    },
                    success: function(response) {
                        $('#loading').removeClass('loading');
                        $('#loading-content').removeClass('loading-content');

                        if (response.status) {
                            $('#distribution-section').hide().html(response.html).slideDown();
                            calculateRemaining();
                        } else {
                            $('#distribution-section').hide();
                            toastr.error(response.message);
                        }
                    },
                    error: function() {
                        $('#loading').removeClass('loading');
                        $('#loading-content').removeClass('loading-content');
                        toastr.error("Failed to fetch data.");
                    }
                });
            });

            // On input change
            $(document).on('input', '.exec-amount', function(){
                calculateRemaining();
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#search').on('keyup', function() {
                var text = $(this).val();
                url = "{{ route('sales-manager.goals.search') }}"
                $('#loading').addClass('loading');
                $('#loading-content').addClass('loading-content');
                $.ajax({
                    url: url,
                    type: 'GET',
                    data: {
                        text: text,
                    },
                    success: function(response) {
                        $('#project_goals_data').html(response.view);
                        $('#loading').removeClass('loading');
                        $('#loading-content').removeClass('loading-content');
                    }
                });
            });
        });
    </script>
@endpush
