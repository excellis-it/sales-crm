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
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($prospects as $key => $prospect)
            <tr>
                <td>
                    {{ ($prospect->sale_date) ?  date('d-m-Y', strtotime($prospect->sale_date) ) : '' }}
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

                <td>
                    @if ($prospect->status != 'Win')
                        <a title="Edit Prospect" data-route="" href="{{ route('sales-manager.prospects.edit', $prospect->id) }}"><i
                                class="fas fa-edit"></i></a> &nbsp;&nbsp;
                    @endif
                    {{-- @if ($prospect->status == 'Win' && $prospect->is_project == false)
                    <a title="Assign to project" data-route="" href="{{ route('sales-manager.prospects.assign-project', $prospect->id) }}"><i
                        class="fa fa-shield"></i></a> &nbsp;&nbsp;
                    @endif --}}

                    <a title="View Prospect" class="view-details-btn"
                        data-route="{{ route('sales-manager.prospects.show', $prospect->id) }}"
                        data-bs-toggle="modal" data-bs-target="#exampleModal"
                        href="javascript:void(0);"><i class="fas fa-eye"></i></a> &nbsp;&nbsp;
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
<script>
    $(document).ready(function() {
        //Default data table
        $('#myTable').DataTable({
            "order": [[ 0, "desc" ]],
            "aaSorting": [],
            "columnDefs": [{
                    "orderable": false,
                    "targets": [10]
                },
                {
                    "orderable": true,
                    "targets": [0, 1, 2, 5, 6, 7, 8, 9]
                }
            ]
        });

    });
    $(document).ready(function() {
           //how to place holder in "jquery datatable" search box
            $('#myTable_filter input').attr("placeholder", "Search");
        });
</script>
