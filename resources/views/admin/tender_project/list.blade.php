@extends('admin.layouts.master')
@section('title')
    Tender Project Management - {{ env('APP_NAME') }}
@endsection
@push('styles')
    <style>
        .dataTables_filter {
            margin-bottom: 10px !important;
        }
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
                    <h3 class="page-title">Tender Projects</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Tender Projects</li>
                    </ul>
                </div>
                <div class="col-auto float-end ms-auto">
                    <a href="{{ route('admin.tender-projects.create') }}" class="btn add-btn"><i class="fa fa-plus"></i> Add Project</a>
                </div>
            </div>
        </div>
        <!-- /Page Header -->

        <!-- Search Filter -->
        <form action="{{ route('admin.tender-projects.index') }}" method="GET" id="search_form">
            <div class="row filter-row">
                <div class="col-sm-6 col-md-3">
                    <div class="form-group form-focus">
                        <input type="date" name="start_date" class="form-control floating" value="{{ $startDate }}">
                        <label class="focus-label">Start Date</label>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="form-group form-focus">
                        <input type="date" name="end_date" class="form-control floating" value="{{ $endDate }}">
                        <label class="focus-label">End Date</label>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="form-group form-focus">
                        <input type="text" name="search" class="form-control floating" value="{{ request('search') }}">
                        <label class="focus-label">Project Name / ID</label>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="form-group form-focus select-focus">
                        <select class="select floating" name="status">
                            <option value="">Select Status</option>
                            @foreach($statuses as $status)
                                <option value="{{ $status->id }}" {{ request('status') == $status->id ? 'selected' : '' }}>{{ $status->name }}</option>
                            @endforeach
                        </select>
                        <label class="focus-label">Status</label>
                    </div>
                </div>
                <div class="col-sm-12 col-md-12 text-end mb-3">
                    <button type="submit" class="btn btn-tender px-5"> SEARCH </button>
                    <button type="button" id="btn_refresh" class="btn btn-tender px-5" style="background: #333 !important; box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important;"> REFRESH </button>
                </div>
            </div>
        </form>
        <!-- /Search Filter -->

        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive" id="tender_project_table">
                    @include('admin.tender_project.table')
                </div>
            </div>
        </div>
    </div>

    <!-- Follow-up Modal -->
    <div id="followup_modal" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content overflow-hidden" style="border-radius: 15px; border: none;">
                <div class="modal-header" style="background: linear-gradient(135deg, #ff9b44 0%, #fc6075 100%); padding: 20px;">
                    <h5 class="modal-title text-white" style="font-weight: 600;"><i class="fas fa-comments me-2"></i> Remarks & Follow-ups History</h5>
                    <button type="button" class="close text-white" data-bs-dismiss="modal" aria-label="Close" style="opacity: 1; border: none; background: transparent; font-size: 24px;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-0">
                    <div id="followup_content" style="background: #fdfdfd;">
                        <!-- Content loaded by AJAX -->
                    </div>
                                        <div class="p-4 bg-light border-top">
                        <form id="add_followup_form">
                            @csrf
                            <input type="hidden" name="tender_project_id" id="followup_tender_project_id">
                            <div class="form-group mb-3">
                                <label class="form-label fw-bold"><i class="fas fa-pencil-alt me-1"></i> Add New Remark <span class="text-danger">*</span></label>
                                <textarea name="comment" class="form-control border-0 shadow-sm" rows="3" required placeholder="Type your follow-up note here..." style="border-radius: 10px; resize: none;"></textarea>
                            </div>
                            <div class="submit-section text-center mb-2">
                                <button type="submit" class="btn btn-tender px-5 py-2">Submit Follow-up</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Follow-up Modal -->

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Function to fetch data via AJAX
            function fetchData(url) {
                $('#loading').addClass('loading');
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(response) {
                        $('#tender_project_table').html(response);
                        $('#loading').removeClass('loading');
                        // Update URL without reloading page
                        window.history.pushState({}, '', url);
                    },
                    error: function(xhr) {
                        $('#loading').removeClass('loading');
                        toastr.error('Failed to load data.');
                    }
                });
            }

            // AJAX Search / Filter
            $('#search_form').on('submit', function(e) {
                e.preventDefault();
                var url = $(this).attr('action') + '?' + $(this).serialize();
                fetchData(url);
            });

            // AJAX Refresh
            $('#btn_refresh').click(function() {
                $('#search_form')[0].reset();
                $('select').val('').trigger('change'); // Clear select2 if used
                fetchData("{{ route('admin.tender-projects.index') }}");
            });

            // AJAX Pagination links
            $(document).on('click', '.pagination a', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                fetchData(url);
            });

            // View Follow-ups (Existing code)
            $(document).on('click', '.view-followups', function() {
                var id = $(this).data('id');
                $('#followup_tender_project_id').val(id);
                loadFollowups(id);
                $('#followup_modal').modal('show');
            });

            function loadFollowups(id) {
                $.ajax({
                    url: "{{ route('admin.tender-projects.followups', ':id') }}".replace(':id', id),
                    type: 'GET',
                    success: function(response) {
                        $('#followup_content').html(response.view);
                    }
                });
            }

            // Add Follow-up
            $('#add_followup_form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    url: "{{ route('admin.tender-projects.add-followup') }}",
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.success);
                            $('#add_followup_form')[0].reset();
                            loadFollowups($('#followup_tender_project_id').val());
                        }
                    },
                    error: function(xhr) {
                        toastr.error('Something went wrong');
                    }
                });
            });
        });
    </script>
@endpush
