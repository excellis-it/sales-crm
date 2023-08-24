@extends('sales_manager.layouts.master')
@section('title')
    All Prospect Details - {{ env('APP_NAME') }}
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
                        <h3 class="page-title">Prospects Information</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('sales-manager.prospects.index') }}">Prospects</a></li>
                            <li class="breadcrumb-item active">List</li>
                        </ul>
                    </div>
                    <div class="col-auto float-end ms-auto">
                        <a href="{{ route('sales-manager.prospects.create') }}" class="btn add-btn"><i class="fa fa-plus"></i> Add a
                            Prospect</a>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                        <div class="row">
                            <div class="col-md-6">
                                <h4 class="mb-0">Prospects Details</h4>
                            </div>

                        </div>
                    </div>

                    <hr />
                    <div class="table-responsive">
                        <table id="myTable" class="dd table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Business Name</th>
                                    <th>Business Address</th>
                                    <th>Transfer Taken By</th>
                                    <th>Website Link</th>
                                    <th>Offered For</th>
                                    <th>Price Quoted</th>
                                    <th>Contact Person</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Status</th>
                                    <th>Followup Date</th>
                                    <th>
                                        Followup Time
                                    </th>
                                    <th>Next Followup Date</th>
                                    <th>Comments</th>
                                    <th>Action</th>
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
                                            {{ $prospect->business_address }}
                                        </td>
                                        <td>
                                            {{ $prospect->transfer_token_by }}
                                        </td>
                                        <td>
                                            {{ $prospect->website }}
                                        </td>
                                        <td>
                                            {{ $prospect->offered_for }}
                                        </td>
                                        <td>
                                            {{ $prospect->price_quote }}
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
                                            {{ $prospect->status }}
                                        </td>
                                        <td>
                                            {{ date('d M, Y', strtotime($prospect->followup_date)) }}
                                        </td>
                                        <td>
                                            {{($prospect->followup_time) ? date('h:i A', strtotime($prospect->followup_time)) : ''}}
                                        </td>
                                        <td>
                                            {{ date('d M, Y', strtotime($prospect->next_followup_date)) }}
                                        </td>
                                        <td>
                                            {{ $prospect->comments }}
                                        </td>
                                        <td>
                                            <a title="Edit Prospect" data-route=""
                                                href="{{ route('sales-manager.prospects.edit', $prospect->id) }}"><i
                                                    class="fas fa-edit"></i></a> &nbsp;&nbsp;
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
    <script>
        $(document).ready(function() {
            //Default data table
            $('#myTable').DataTable({
                "aaSorting": [],
                "columnDefs": [{
                        "orderable": false,
                        "targets": [14]
                    },
                    {
                        "orderable": true,
                        "targets": [0, 1, 2, 5, 6, 7, 8, 9, 10, 11, 12, 13]
                    }
                ]
            });

        });
    </script>
    <script>
        $(document).on('click', '#delete', function(e) {
            swal({
                    title: "Are you sure?",
                    text: "To delete this prospect.",
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
    {{-- <script>
        $('.toggle-class').change(function() {
            var status = $(this).prop('checked') == true ? 1 : 0;
            var user_id = $(this).data('id');

            $.ajax({
                type: "GET",
                dataType: "json",
                url: '{{ route('prospects.change-status') }}',
                data: {
                    'status': status,
                    'user_id': user_id
                },
                success: function(resp) {
                    console.log(resp.success)
                }
            });
        });
    </script> --}}
@endpush
