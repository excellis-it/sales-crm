@extends('admin.layouts.master')
@section('title')
    All Account manager Details - {{ env('APP_NAME') }}
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
                    <div class="col-md-8">
                        <h3 class="page-title">Account managers Information</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('account_managers.index') }}">Account managers</a>
                            </li>
                            <li class="breadcrumb-item active">List</li>
                        </ul>
                    </div>
                    {{-- <div class="col-md-4">
                        <div class="d-flex">
                            <select class="form-select w-50 rounded-0" aria-label="Default select example">
                              <option selected>All (29)</option>
                              <option value="1">Active (20)</option>
                              <option value="2">Inactive (9)</option>
                            </select>
                            <a href="{{ route('account_managers.create') }}" class="btn add-btn"> Add New
                                account manager</a>
                        </div>
                    </div> --}}
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                        <div class="row">
                            <div class="col-md-6">
                                <h4 class="mb-0">Account managers Details</h4>
                            </div>
                            <div class="col-md-6 text-end">
                                <a href="{{ route('account_managers.create') }}" class="btn px-5 submit-btn"><i
                                        class="fas fa-plus"></i> Add New
                                    account manager</a>
                            </div>
                        </div>
                    </div>

                    <hr />
                    <div class="row justify-content-end">
                        <div class="col-md-6">
                            <div class="row g-1 justify-content-end">
                                <div class="col-md-8 pr-0">
                                    <div class="search-field">
                                        <input type="text" name="search" id="search" placeholder="search..." required
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3 pl-0 ml-2">
                                    <button class="btn px-5 submit-btn" id="search-button"> <span class=""><i
                                                class="fa fa-search"></i></span> Search</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive" id="account_managers_data">
                        @include('admin.account_manager.table')
                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#search-button').on('click', function() {
            var text = $('#search').val();
            if (text == '') {
                alert('Please type something for search!');
                return false;
            }
            url = "{{ route('account_managers.search') }}"
            $('#loading').addClass('loading');
            $('#loading-content').addClass('loading-content');
            $.ajax({
                url: url,
                type: 'GET',
                data: {
                    text: text,
                },
                success: function(response) {
                    $('#account_managers_data').html(response.view);
                    $('#search').val('');
                    $('#loading').removeClass('loading');
                    $('#loading-content').removeClass('loading-content');
                }
            });
        });
    });
</script>
    <script>
        $(document).on('click', '#delete', function(e) {
            swal({
                    title: "Are you sure?",
                    text: "To delete this account_manager.",
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
        $('.toggle-class').change(function() {
            var status = $(this).prop('checked') == true ? 1 : 0;
            var user_id = $(this).data('id');

            $.ajax({
                type: "GET",
                dataType: "json",
                url: '{{ route('account_managers.change-status') }}',
                data: {
                    'status': status,
                    'user_id': user_id
                },
                success: function(resp) {
                    console.log(resp.success)
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            //how to place holder in "jquery datatable" search box
            $('#myTable_filter input').attr("placeholder", "Search");
        });
    </script>
@endpush
