@extends('bdm.layouts.master')
@section('title')
    All BDE Details - {{ env('APP_NAME') }}
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
                        <h3 class="page-title">BDE Information</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('bde.index') }}">BDE</a></li>
                            <li class="breadcrumb-item active">List</li>
                        </ul>
                    </div>

                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                        <div class="row">
                            <div class="col-md-6">
                                <h4 class="mb-0">BDE Details</h4>
                            </div>
                            <div class="col-md-6 text-end">
                                <a href="{{ route('bde.create') }}" class="btn px-5 submit-btn"><i class="fa fa-plus"></i> Add a
                                    BDE</a>
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
                                            class="form-control rounded_search">
                                        <button class="submit_search" id="search-button"> <span class=""><i
                                                    class="fa fa-search"></i></span></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive" id="business_development_executive_data">
                        
                                @include('bdm.business_development_excecutive.table')
                            
                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection

@push('scripts')
   
    <script>
        $(document).on('click', '#delete', function(e) {
            swal({
                    title: "Are you sure?",
                    text: "To delete this business_development_excecutive.",
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
                url: '{{ route("bde.change-status") }}',
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

    
<script>
    $(document).ready(function() {
        $('#search-button').on('click', function() {
            var text = $('#search').val();
            if (text == '') {
                alert('Please type something for search!');
                return false;
            }
            url = "{{ route('bdm.business-development-executive.search') }}"
            $('#loading').addClass('loading');
            $('#loading-content').addClass('loading-content');
            $.ajax({
                url: url,
                type: 'GET',
                data: {
                    text: text,
                },
                success: function(response) {
                   
                    $('#business_development_executive_data').html(response.view);
                    $('#search').val('');
                    $('#loading').removeClass('loading');
                    $('#loading-content').removeClass('loading-content');
                }
            });
        });
    });
</script>
@endpush
