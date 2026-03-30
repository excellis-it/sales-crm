@extends('admin.layouts.master')
@section('title')
    User Activity - {{ env('APP_NAME') }}
@endsection
@push('styles')
    <style>
        .filter-card {
            border: none;
            border-radius: 0.75rem;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
            margin-bottom: 20px;
        }
        .filter-card .card-body {
            padding: 1.25rem;
        }
        .activity-table-card {
            border: none;
            border-radius: 0.75rem;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }
        .activity-table-card .card-header {
            background: #fff;
            border-bottom: 1px solid #f0f0f0;
            padding: 1rem 1.25rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .activity-table-card .card-header h5 {
            margin: 0;
            font-weight: 600;
            font-size: 15px;
            color: #333;
        }
        .activity-table .table th {
            background: #f8f9fa;
            border-top: none;
            font-size: 12px;
            font-weight: 600;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            white-space: nowrap;
        }
        .activity-table .table td {
            vertical-align: middle;
            font-size: 13px;
            padding: 0.65rem 0.75rem;
        }
        .activity-table .table tbody tr {
            transition: background 0.2s;
        }
        .activity-table .table tbody tr:hover {
            background: #f0f7ff;
        }
        .badge-source-tele {
            background: #e8f4fd;
            color: #0077b6;
            font-size: 11px;
            padding: 3px 8px;
            border-radius: 4px;
            font-weight: 600;
        }
        .badge-source-bdm {
            background: #f3e8fd;
            color: #6f42c1;
            font-size: 11px;
            padding: 3px 8px;
            border-radius: 4px;
            font-weight: 600;
        }
        .badge-type-project {
            background: #d4edda;
            color: #155724;
            font-size: 11px;
            padding: 2px 6px;
            border-radius: 4px;
        }
        .badge-type-prospect {
            background: #fff3cd;
            color: #856404;
            font-size: 11px;
            padding: 2px 6px;
            border-radius: 4px;
        }
        .description-cell {
            max-width: 250px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .total-count-badge {
            background: linear-gradient(135deg, #ff9b44 0%, #fc6075 100%);
            color: #fff;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
        }
        .filter-btn {
            background: linear-gradient(135deg, #ff9b44 0%, #fc6075 100%);
            border: none;
            border-radius: 8px;
            color: #fff;
            padding: 8px 24px;
            font-weight: 600;
            font-size: 13px;
        }
        .filter-btn:hover {
            opacity: 0.9;
            color: #fff;
        }
        .reset-btn {
            border-radius: 8px;
            padding: 8px 20px;
            font-size: 13px;
            font-weight: 600;
        }
        #activity-loader {
            display: none;
            text-align: center;
            padding: 40px;
        }
    </style>
@endpush

@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">

            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">User Activity</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">User Activity</li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Filters --}}
            <div class="card filter-card">
                <div class="card-body">
                    <form id="activity-filter-form">
                        <div class="row align-items-end">
                            <div class="col-md-3 mb-2">
                                <label class="form-label fw-bold" style="font-size: 12px;">User</label>
                                <select name="user_id" id="filter-user" class="form-control select2">
                                    <option value="">All Users</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}
                                            ({{ $user->roles->first()->name ?? '' }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 mb-2">
                                <label class="form-label fw-bold" style="font-size: 12px;">Start Date</label>
                                <input type="date" name="start_date" id="filter-start-date" class="form-control"
                                    value="{{ date('Y-m-01') }}">
                            </div>
                            <div class="col-md-2 mb-2">
                                <label class="form-label fw-bold" style="font-size: 12px;">End Date</label>
                                <input type="date" name="end_date" id="filter-end-date" class="form-control"
                                    value="{{ date('Y-m-d') }}">
                            </div>
                            <div class="col-md-2 mb-2">
                                <label class="form-label fw-bold" style="font-size: 12px;">Source</label>
                                <select name="source" id="filter-source" class="form-control">
                                    <option value="all">All Sources</option>
                                    <option value="telesales">Tele Sales</option>
                                    <option value="bdm">BDM</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-2">
                                <button type="submit" class="btn filter-btn mr-2"><i class="la la-search"></i>
                                    Filter</button>
                                <button type="button" class="btn btn-outline-secondary reset-btn" id="reset-filter"><i
                                        class="la la-redo"></i> Reset</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Activity Table --}}
            <div class="card activity-table-card">
                <div class="card-header">
                    <h5><i class="la la-history mr-1"></i> Follow-up Activities</h5>
                    <span class="total-count-badge" id="total-count">0 Activities</span>
                </div>
                <div class="activity-table">
                    <div id="activity-loader">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <p class="mt-2 text-muted">Loading activities...</p>
                    </div>
                    <div id="activity-table-content">
                        {{-- AJAX content loads here --}}
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Initialize select2
            if ($.fn.select2) {
                $('#filter-user').select2({
                    placeholder: 'Select User',
                    allowClear: true,
                    width: '100%'
                });
            }

            // Load on page load
            fetchActivities();

            // Filter form submit
            $('#activity-filter-form').on('submit', function(e) {
                e.preventDefault();
                fetchActivities();
            });

            // Reset filter
            $('#reset-filter').on('click', function() {
                $('#filter-user').val('').trigger('change');
                $('#filter-start-date').val('{{ date('Y-m-01') }}');
                $('#filter-end-date').val('{{ date('Y-m-d') }}');
                $('#filter-source').val('all');
                fetchActivities();
            });

            function fetchActivities() {
                var data = {
                    user_id: $('#filter-user').val(),
                    start_date: $('#filter-start-date').val(),
                    end_date: $('#filter-end-date').val(),
                    source: $('#filter-source').val()
                };

                $('#activity-loader').show();
                $('#activity-table-content').hide();

                $.ajax({
                    url: "{{ route('admin.user-activity.filter') }}",
                    type: 'GET',
                    data: data,
                    success: function(response) {
                        $('#activity-loader').hide();
                        $('#activity-table-content').html(response.view).show();
                        var count = response.total || 0;
                        $('#total-count').text(count + ' Activit' + (count === 1 ? 'y' : 'ies'));
                    },
                    error: function(xhr) {
                        $('#activity-loader').hide();
                        $('#activity-table-content').html(
                            '<div class="text-center text-danger py-4">Failed to load activities.</div>'
                        ).show();
                    }
                });
            }
        });
    </script>
@endpush
