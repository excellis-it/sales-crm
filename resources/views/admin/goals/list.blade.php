@extends('admin.layouts.master')
@section('title')
    All Goals Details - {{ env('APP_NAME') }}
@endsection
@push('styles')
    <style>
        .dataTables_filter {
            margin-bottom: 10px !important;
        }
        #goal-create .card {
            transition: all 0.3s ease;
        }
        #goal-create .card:hover {
            box-shadow: 0 8px 25px rgba(243, 126, 32, 0.15) !important;
        }
        #goal-create .form-select.border-primary,
        #goal-create .form-control.border-primary {
            border-color: #f37e20 !important;
        }
        #goal-create .form-select.border-primary:focus,
        #goal-create .form-control.border-primary:focus {
            border-color: #f37e20 !important;
            box-shadow: 0 0 0 0.2rem rgba(243, 126, 32, 0.25);
        }
        #se-distribution .card {
            transition: all 0.3s ease;
        }
        #se-distribution .card:hover {
            box-shadow: 0 8px 25px rgba(173, 30, 35, 0.12) !important;
        }
        .exec-amount {
            font-size: 14px;
            font-weight: 600;
            background-color: #fff9e6;
            border: 1px solid #f37e20;
            border-radius: 5px;
            padding: 8px;
            height: 42px;
        }
        .exec-amount:focus {
            border-color: #f37e20 !important;
            box-shadow: 0 0 0 0.2rem rgba(243, 126, 32, 0.25);
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
                        <h3 class="page-title">Goals</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('goals.index') }}">Goals</a></li>
                            <li class="breadcrumb-item active">List</li>
                        </ul>
                    </div>

                </div>
            </div>
            <div class="row">
                <div class="col-xl-12 mx-auto" id="goal-create">
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-4">
                            <h5 class="mb-4 text-uppercase">Goals Create</h5>
                            <hr class="mb-4">
                            <form action="{{ route('goals.store') }}" method="post" id="createGoals"
                                enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="id" id="id">
                                <div class="row align-items-end mb-4">
                                    <div class="col-md-3">
                                        <label for="inputEnterYourName" class="form-label fw-bold"> User Type
                                            <span style="color: red;">*</span></label>
                                        <select name="user_type" id="user_type" class="form-select border-primary" style="height: 45px; border-radius: 5px;">
                                            <option value="">Select a User Type</option>
                                            <option value="BUSINESS_DEVELOPMENT_MANAGER">BDM</option>
                                            <option value="SALES_MANAGER">Sales Manager</option>
                                            <option value="ACCOUNT_MANAGER">Account Manager</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="inputEnterYourName" class="form-label fw-bold"> Goal Assign For
                                            <span style="color: red;">*</span></label>
                                        <select name="user_id" id="user_id" class="form-select border-primary" style="height: 45px; border-radius: 5px;">
                                            <option value="">Select a User</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="inputEnterYourName" class="form-label fw-bold"> Target Amount </label>
                                        <input type="text" name="goals_amount" id="goals_amount" class="form-control border-primary"
                                            style="height: 45px; border-radius: 5px;" placeholder="Enter Target Amount">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="inputEnterYourName" class="form-label fw-bold"> Goal Month
                                            <span style="color: red;">*</span></label>
                                        <select name="goals_date" id="goals_date" class="form-select border-primary" style="height: 45px; border-radius: 5px;">
                                            <option value="">Select a month</option>
                                            <option value="{{ date('Y') }}-01-01">January</option>
                                            <option value="{{ date('Y') }}-02-01">February</option>
                                            <option value="{{ date('Y') }}-03-01">March</option>
                                            <option value="{{ date('Y') }}-04-01">April</option>
                                            <option value="{{ date('Y') }}-05-01">May</option>
                                            <option value="{{ date('Y') }}-06-01">June</option>
                                            <option value="{{ date('Y') }}-07-01">July</option>
                                            <option value="{{ date('Y') }}-08-01">August</option>
                                            <option value="{{ date('Y') }}-09-01">September</option>
                                            <option value="{{ date('Y') }}-10-01">October</option>
                                            <option value="{{ date('Y') }}-11-01">November</option>
                                            <option value="{{ date('Y') }}-12-01">December</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="d-flex w-100 justify-content-end">
                                    <button type="submit"
                                        class="btn px-5 submit-btn form-button" style="height: 45px; font-weight: 500;">Create</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sales Executive Distribution Section --}}
            <div class="row mt-3" id="se-distribution">
                <div class="col-xl-12 mx-auto">
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-4">
                            <h5 class="mb-4 text-uppercase">Distribute Goals to Sales Executives</h5>
                            <hr class="mb-4">
                            <div class="row align-items-end mb-4">
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Select Sales Manager <span style="color: red;">*</span></label>
                                    <select id="dist_sales_manager" class="form-select border-primary" style="height: 45px; border-radius: 5px;">
                                        <option value="">-- Select a Sales Manager --</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Goal Month <span style="color: red;">*</span></label>
                                    <select id="dist_goals_date" class="form-select border-primary" style="height: 45px; border-radius: 5px;">
                                        <option value="">Select a month</option>
                                        <option value="{{ date('Y') }}-01-01">January {{ date('Y') }}</option>
                                        <option value="{{ date('Y') }}-02-01">February {{ date('Y') }}</option>
                                        <option value="{{ date('Y') }}-03-01">March {{ date('Y') }}</option>
                                        <option value="{{ date('Y') }}-04-01">April {{ date('Y') }}</option>
                                        <option value="{{ date('Y') }}-05-01">May {{ date('Y') }}</option>
                                        <option value="{{ date('Y') }}-06-01">June {{ date('Y') }}</option>
                                        <option value="{{ date('Y') }}-07-01">July {{ date('Y') }}</option>
                                        <option value="{{ date('Y') }}-08-01">August {{ date('Y') }}</option>
                                        <option value="{{ date('Y') }}-09-01">September {{ date('Y') }}</option>
                                        <option value="{{ date('Y') }}-10-01">October {{ date('Y') }}</option>
                                        <option value="{{ date('Y') }}-11-01">November {{ date('Y') }}</option>
                                        <option value="{{ date('Y') }}-12-01">December {{ date('Y') }}</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <button type="button" class="btn px-5 submit-btn" id="fetchDistributionBtn" style="height: 45px; font-weight: 500;">
                                        <i class="fa fa-sitemap me-2"></i> Fetch Distribution
                                    </button>
                                </div>
                            </div>

                            <div id="distribution-section" style="display:none;">
                                <!-- AJAX loaded distribution form will appear here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h4 class="mb-0">Goals</h4>
                                    </div>
                                    <div class="col-md-6 text-end">
                                        <a href="javascript:void(0);" class="btn px-4 submit-btn me-2" id="se-dist-btn"><i
                                                class="fa fa-sitemap"></i> Distribute SE Goals</a>
                                        <a href="javascript:void(0);" class="btn px-4 submit-btn" id="add-btn"><i
                                                class="fa fa-plus"></i> Add Goals</a>
                                    </div>
                                </div>
                            </div>

                            <hr />
                            <div class="row justify-content-end align-items-center">
                                <div class="col-md-2 mb-2">
                                    <select id="filter_year" class="form-select border-primary" style="height: 45px; border-radius: 5px;">
                                        <option value="">Select Year</option>
                                        @for ($i = date('Y'); $i >= 2025; $i--)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-md-2 mb-2">
                                    <select id="filter_month" class="form-select border-primary" style="height: 45px; border-radius: 5px;">
                                        <option value="">Select Month</option>
                                        @for ($m = 1; $m <= 12; $m++)
                                            <option value="{{ sprintf('%02d', $m) }}">{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <div class="search-field">
                                        <input type="text" name="search" id="search"
                                            placeholder="search..." required class="form-control rounded_search">
                                        <button class="submit_search" id="search-button"> <span class=""><i
                                                    class="fa fa-search"></i></span></button>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive" id="project_goals_data">
                                @include('admin.goals.table')
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">

                </div>
            </div>

        </div>

    </div>
@endsection

@push('scripts')
    <script>
        $(document).on("click", ".edit-data", function() {
            var route = $(this).data('route');
            // add loader
            $('#loading').addClass('loading');
            $('#loading-content').addClass('loading-content');
            var role = $(this).data('role');
            if (role == 'SALES_MANAGER') {
                $('#goals_type').html(
                    '<option value="">Select a goal type</option><option value="1">Gross</option><option value="2">Net</option>'
                );
            } else {
                $('#goals_type').html(
                    '<option value="">Select a goal type</option><option value="2">Net</option>'
                );
            }
            $.ajax({
                url: route,
                type: "GET",
                data: {
                    role: role
                },
                success: function(resp) {
                    // space remove
                    role = role.replace(/\s/g, '');
                    if (role == 'SALES_MANAGER') {
                        // select user type
                        $('#user_type').val('SALES_MANAGER');
                        console.log('sales manager');
                    } else if (role == 'ACCOUNT_MANAGER') {
                        // select user type
                        $('#user_type').val('ACCOUNT_MANAGER');
                        console.log('account manager');
                    } else if (role == 'SALES_EXCUETIVE') {
                        // select user type
                        $('#user_type').val('SALES_EXCUETIVE');
                        console.log('sales excuetive');
                    } else if (role == 'BUSINESS_DEVELOPMENT_MANAGER') {
                        console.log('business development manager');
                        // select user type
                        $('#user_type').val('BUSINESS_DEVELOPMENT_MANAGER');
                    } else {
                        // select user type
                        $('#user_type').val('BUSINESS_DEVELOPMENT_EXCECUTIVE');
                        console.log('business development excuetive');
                    }

                    console.log(resp.users);
                    var html = '<option value="">Select a user</option>';
                    $.each(resp.users, function(key, value) {
                        html += '<option value="' + value.id + '">' + value
                            .name +
                            '</option>';
                    });

                    $('#user_id').html(html);
                    $('#se-distribution').hide();
                    $('#goal-create').show();
                    $('#id').val(resp.data.id);
                    $('#user_id').val(resp.data.user_id);
                    $('#goals_type').val(resp.data.goals_type);
                    $('#goals_amount').val(resp.data.goals_amount);
                    $('#goals_date').val(resp.data.goals_date);
                    $('.form-button').html('Update');
                    $('#loading').removeClass('loading');
                    $('#loading-content').removeClass('loading-content');
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            //Default data table
            $('#goal-create').hide();
            // toogle create goal
            $('#add-btn').click(function() {
                $('#id').val('');
                $('#user_type').val('');
                $('#user_id').val('');
                $('#goals_type').val('');
                $('#goals_amount').val('');
                $('#goals_date').val('');
                $('.form-button').html('Create');
                $('#se-distribution').hide(); // Hide distribution when opening create
                $('#goal-create').toggle();
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            //Default data table


            $('#createGoals').validate({ // initialize the plugin
                rules: {
                    user_id: {
                        required: true,
                    },
                    goals_amount: {
                        required: true,
                        number: true,
                    },
                    goals_date: {
                        required: true,
                    },
                    user_type: {
                        required: true,
                    },
                },
                messages: {
                    user_id: {
                        required: "Please select a user",
                    },
                    user_type: {
                        required: "Please select a user type",
                    },
                    goals_amount: {
                        required: "Please enter a target amount",
                        number: "Please enter a valid number",
                    },
                    goals_date: {
                        required: "Please select a goal month",
                    },
                }
            });

        });
    </script>
    <script>
        $(document).on('click', '#delete', function(e) {
            swal({
                    title: "Are you sure?",
                    text: "To delete this goal.",
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
            $('#user_id').on('change', function() {
                var user_id = $(this).val();
                // add loader
                $('#loading').addClass('loading');
                $('#loading-content').addClass('loading-content');
                $.ajax({
                    url: "{{ route('goals.get.user') }}",
                    type: "POST",
                    data: {
                        user_id: user_id,
                        _token: "{{ csrf_token() }}",
                    },
                    success: function(data) {
                        if (data.role == 'SALES_MANAGER') {
                            $('#goals_type').html(
                                '<option value="">Select a goal type</option><option value="1">Gross</option><option value="2">Net</option>'
                            );
                        } else {
                            $('#goals_type').html(
                                '<option value="">Select a goal type</option><option value="2">Net</option>'
                            );
                        }
                        $('#loading').removeClass('loading');
                        $('#loading-content').removeClass('loading-content');
                    }
                });
            });

            $('#user_type').on('change', function() {
                var user_type = $(this).val();
                $('#loading').addClass('loading');
                $('#loading-content').addClass('loading-content');
                $.ajax({
                    url: "{{ route('goals.get.user-by-type') }}",
                    type: "POST",
                    data: {
                        user_type: user_type,
                        _token: "{{ csrf_token() }}",
                    },
                    success: function(data) {
                        if (data.status == true) {
                            var html = '<option value="">Select a user</option>';
                            $.each(data.users, function(key, value) {
                                html += '<option value="' + value.id + '">' + value
                                    .name +
                                    '</option>';
                            });

                            $('#user_id').html(html);
                            $('#loading').removeClass('loading');
                            $('#loading-content').removeClass('loading-content');
                        } else {
                            $('#loading').removeClass('loading');
                            $('#loading-content').removeClass('loading-content');
                            toastr.error(data.message);
                        }
                    }
                });
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            function fetch_goals(page = 1) {
                var text = $('#search').val();
                var month = $('#filter_month').val();
                var year = $('#filter_year').val();
                var url = "{{ route('project-goals.search') }}" + "?page=" + page;

                $('#loading').addClass('loading');
                $('#loading-content').addClass('loading-content');

                $.ajax({
                    url: url,
                    type: 'GET',
                    data: {
                        text: text,
                        month: month,
                        year: year
                    },
                    success: function(response) {
                        $('#project_goals_data').html(response.view);
                        $('#loading').removeClass('loading');
                        $('#loading-content').removeClass('loading-content');
                    },
                    error: function() {
                        $('#loading').removeClass('loading');
                        $('#loading-content').removeClass('loading-content');
                    }
                });
            }

            $(document).on('click', '#project_goals_data .pagination a', function(event) {
                event.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                fetch_goals(page);
            });

            $('#search').on('keyup', function() {
                fetch_goals();
            });

            $('#filter_year, #filter_month').on('change', function() {
                fetch_goals();
            });
        });
    </script>

    {{-- SE Distribution Scripts --}}
    <script>
        $(document).ready(function() {
            // Hide distribution section by default
            $('#se-distribution').hide();

            // Load Sales Managers
            $.ajax({
                url: "{{ route('goals.get.sales-managers') }}",
                type: "GET",
                success: function(data) {
                    if (data.status) {
                        var html = '<option value="">-- Select a Sales Manager --</option>';
                        $.each(data.sales_managers, function(key, value) {
                            html += '<option value="' + value.id + '">' + value.name + '</option>';
                        });
                        $('#dist_sales_manager').html(html);
                    }
                }
            });

            // Toggle SE Distribution Section
            $('#se-dist-btn').click(function() {
                $('#dist_sales_manager').val('');
                $('#dist_goals_date').val('');
                $('#distribution-section').hide();
                $('#goal-create').hide(); // Hide create when opening distribution
                $('#se-distribution').toggle();
            });

            // Calculate remaining amount
            function calculateRemaining() {
                let totalStr = $('#total_sm_goal').text().replace(/,/g, '');
                let total = parseFloat(totalStr) || 0;
                let current = 0;
                $('.exec-amount').each(function() {
                    current += parseFloat($(this).val()) || 0;
                });
                let remaining = total - current;
                $('#remaining_goal').text(remaining.toFixed(2));

                if (remaining < -0.5) {
                    $('#allocation_label').text('Extra Allocation');
                    $('#remaining_goal').text(Math.abs(remaining).toFixed(2));
                    $('#remaining_goal').css('color', '#dc3545');
                    $('#submitDistribution').prop('disabled', false);
                } else {
                    $('#allocation_label').text('Remaining');
                    $('#remaining_goal').text(remaining.toFixed(2));
                    if (remaining > 0.5) {
                        $('#remaining_goal').css('color', '#f37e20');
                    } else {
                        $('#remaining_goal').css('color', '#28a745');
                    }
                    $('#submitDistribution').prop('disabled', false);
                }
            }

            // Fetch Distribution
            $('#fetchDistributionBtn').on('click', function() {
                var salesManagerId = $('#dist_sales_manager').val();
                var goalsDate = $('#dist_goals_date').val();

                if (!salesManagerId) {
                    toastr.error('Please select a Sales Manager.');
                    return;
                }
                if (!goalsDate) {
                    toastr.error('Please select a Goal Month.');
                    return;
                }

                $('#loading').addClass('loading');
                $('#loading-content').addClass('loading-content');

                $.ajax({
                    url: "{{ route('goals.get.distribution') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        sales_manager_id: salesManagerId,
                        goals_date: goalsDate
                    },
                    success: function(response) {
                        $('#loading').removeClass('loading');
                        $('#loading-content').removeClass('loading-content');

                        if (response.status) {
                            var html = '<form action="{{ route('goals.store.distribution') }}" method="post" id="distributionForm">';
                            html += '@csrf';
                            html += '<input type="hidden" name="sales_manager_id" value="' + salesManagerId + '">';
                            html += '<input type="hidden" name="goals_date" value="' + goalsDate + '">';
                            html += '<div class="d-flex justify-content-between p-3 mb-3 align-items-center rounded" style="background: linear-gradient(135deg, #ffdfc5, #fff3e8); border: 1px solid #f37e20;">';
                            html += '<div style="font-weight: bold; font-size: 16px; color: #ad1e23;">Total Goal: $<span id="total_sm_goal">' + response.total_amount + '</span></div>';
                            html += '<div style="font-weight: bold; font-size: 16px;"><span id="allocation_label">Remaining</span>: $<span id="remaining_goal">0.00</span></div>';
                            html += '</div>';
                            html += '<div class="table-responsive"><table class="table table-hover table-center mb-4">';
                            html += '<thead><tr style="background: #ffdfc5; color: #ac1e23;"><th>Sales Executive</th><th>Allocated Target Amount ($)</th></tr></thead>';
                            html += '<tbody>';

                            $.each(response.executives, function(key, exec) {
                                html += '<tr>';
                                html += '<td class="align-middle" style="font-weight: 500;">' + exec.name + '</td>';
                                html += '<td><input type="number" step="0.01" name="amount[' + exec.id + ']" class="form-control exec-amount" value="' + exec.amount + '" required></td>';
                                html += '</tr>';
                            });

                            html += '</tbody></table></div>';
                            html += '<div class="text-end"><button type="submit" class="btn px-5 submit-btn" id="submitDistribution" style="height: 45px; font-weight: 500;"><i class="fa fa-check-circle me-2"></i>Save Distribution</button></div>';
                            html += '</form>';

                            $('#distribution-section').hide().html(html).slideDown();
                            calculateRemaining();
                        } else {
                            $('#distribution-section').hide();
                            toastr.error(response.message);
                        }
                    },
                    error: function() {
                        $('#loading').removeClass('loading');
                        $('#loading-content').removeClass('loading-content');
                        toastr.error('Failed to fetch distribution data.');
                    }
                });
            });

            // On amount input change
            $(document).on('input', '.exec-amount', function() {
                calculateRemaining();
            });
        });
    </script>
@endpush
