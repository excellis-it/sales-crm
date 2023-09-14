@extends('admin.layouts.master')
@section('title')
    All Project Goals Details - {{ env('APP_NAME') }}
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
                        <h3 class="page-title">Project Goals</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('goals.index') }}">Project Goals</a></li>
                            <li class="breadcrumb-item active">List</li>
                        </ul>
                    </div>
                    <div class="col-auto float-end ms-auto">
                        <a href="javascript:void(0);" class="btn add-btn" id="add-btn"><i class="fa fa-plus"></i> Add a
                            Project Goals</a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12 mx-auto" id="goal-create">
                    <h6 class="mb-0 text-uppercase">Goals Create</h6>
                    <hr>
                    <div class="card border-0 border-4">
                        <div class="card-body">
                            <form action="{{ route('goals.store') }}" method="post" id="createGoals"
                                enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="id" id="id">
                                <div class="border p-4 rounded">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="inputEnterYourName" class="col-form-label"> Goal Assign For
                                                <span style="color: red;">*</span></label>
                                            <select name="user_id" id="user_id" class="form-control">
                                                <option value="">Select a User</option>
                                                @foreach ($users as $user)
                                                    <option value="{{ $user->id }}">{{ $user->name }}
                                                        ({{ $user->email }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="inputEnterYourName" class="col-form-label"> Goal Type
                                                <span style="color: red;">*</span></label>
                                            <select name="goals_type" id="goals_type" class="form-control">
                                                <option value="">Select a goal type</option>

                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="inputEnterYourName" class="col-form-label"> Target Amount </label>
                                            <input type="text" name="goals_amount" id="goals_amount" class="form-control"
                                                placeholder="Enter Target Amount">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="inputEnterYourName" class="col-form-label"> Goal Month
                                                <span style="color: red;">*</span></label>
                                            <select name="goals_date" id="goals_date" class="form-control">
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

                                        <div class="row" style="margin-top: 20px; float: left;">
                                            <div class="col-sm-9">
                                                <button type="submit"
                                                    class="btn px-5 submit-btn form-button">Create</button>
                                            </div>
                                        </div>
                                    </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h4 class="mb-0">Project Goals For Sales Manager</h4>
                                    </div>

                                </div>
                            </div>

                            <hr />
                            <div class="table-responsive">
                                <table id="myTable" class="dd table table-striped table-bordered" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Goals Date</th>
                                            <th> Goals Type</th>
                                            <th> Goal Assign For</th>
                                            <th> Target Amount </th>
                                            <th>Target Achieve</th>
                                            <th>
                                                Action
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($salesManagerGoals as $key => $goal)
                                            <tr>
                                                <td>
                                                    {{ date('M ,Y', strtotime($goal->goals_date)) }}
                                                </td>
                                                <td>
                                                    {{ $goal->goals_type == 1 ? 'Gross' : 'Net' }}
                                                </td>
                                                <td>
                                                    {{ $goal->user->name }}
                                                </td>
                                                <td>
                                                    {{ $goal->goals_amount }}
                                                </td>
                                                <td>
                                                    {{ $goal->goals_achieve ?? 0 }}
                                                </td>
                                                <td>
                                                    <a href="javascript:void(0);"
                                                        data-route="{{ route('goals.edit', $goal->id) }}"
                                                        data-role="SALES_MANAGER" class="edit-data"><i
                                                            class="fas fa-edit"></i> </a> &nbsp;
                                                    <a title="Delete Project"
                                                        data-route="{{ route('goals.delete', $goal->id) }}"
                                                        href="javascipt:void(0);" id="delete"><i
                                                            class="fas fa-trash"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h4 class="mb-0">Project Goals For Account Manager</h4>
                                    </div>

                                </div>
                            </div>

                            <hr />
                            <div class="table-responsive">
                                <table id="myTable1" class="dd table table-striped table-bordered" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Goals Date</th>
                                            <th> Goals Type</th>
                                            <th> Goal Assign For</th>
                                            <th> Target Amount </th>
                                            <th>Target Achieve</th>
                                            <th>
                                                Action
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($accountManagerGoals as $key => $goal)
                                            <tr>
                                                <td>
                                                    {{ date('M ,Y', strtotime($goal->goals_date)) }}
                                                </td>
                                                <td>
                                                    {{ $goal->goals_type == 1 ? 'Gross' : 'Net' }}
                                                </td>
                                                <td>
                                                    {{ $goal->user->name }}
                                                </td>
                                                <td>
                                                    {{ $goal->goals_amount }}
                                                </td>
                                                <td>
                                                    {{ $goal->goals_achieve ?? 0 }}
                                                </td>
                                                <td>
                                                    <a href="javascript:void(0);"
                                                        data-route="{{ route('goals.edit', $goal->id) }}"
                                                        data-role="ACCOUNT_MANAGER" class="edit-data"><i
                                                            class="fas fa-edit"></i> </a> &nbsp;
                                                    <a title="Delete Project"
                                                        data-route="{{ route('goals.delete', $goal->id) }}"
                                                        href="javascipt:void(0);" id="delete"><i
                                                            class="fas fa-trash"></i></a>
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
                        "targets": [2, 5]
                    },
                    {
                        "orderable": true,
                        "targets": [0, 1, 3, 4]
                    }
                ]
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
                $('#user_id').val('');
                $('#goals_type').val('');
                $('#goals_amount').val('');
                $('#goals_date').val('');
                $('.form-button').html('Create');
                $('#goal-create').toggle();
            });

            $('.edit-data').on('click', function() {
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
                    success: function(resp) {
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
        });
    </script>
    <script>
        $(document).ready(function() {
            //Default data table
            $('#myTable1').DataTable({
                "aaSorting": [],
                "columnDefs": [{
                        "orderable": false,
                        "targets": [2, 5]
                    },
                    {
                        "orderable": true,
                        "targets": [0, 1, 3, 4]
                    }
                ]
            });

            $('#createGoals').validate({ // initialize the plugin
                rules: {
                    user_id: {
                        required: true,
                    },
                    goals_type: {
                        required: true,
                    },
                    goals_amount: {
                        required: true,
                        number: true,
                    },
                    goals_date: {
                        required: true,
                    },
                },
                messages: {
                    user_id: {
                        required: "Please select a user",
                    },
                    goals_type: {
                        required: "Please select a goal type",
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
                    }
                });
            });
        });
    </script>
@endpush
