<table id="myTable" class="dd table table-striped table-bordered" style="width:100%">
    <thead>
        <tr>
            <th>
                Prospect By
            </th>
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
                    <a
                        href="{{ route('sales-excecutive.index', ['id' => $prospect->user->id]) }}">{{ $prospect->user->name }}</a>
                </td>
                <td>
                    {{ date('d M, Y', strtotime($prospect->created_at)) }}
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
                    {{ $prospect->status }}
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
                    <a title="Edit Prospect" data-route="" href="{{ route('admin.prospects.edit', $prospect->id) }}"><i
                            class="fas fa-edit"></i></a> &nbsp;&nbsp;
                    <a title="View Prospect" class="view-details-btn"
                        data-route="{{ route('admin.prospects.show', $prospect->id) }}" data-bs-toggle="modal"
                        data-bs-target="#exampleModal" href="javascript:void(0);"><i class="fas fa-eye"></i></a>
                    &nbsp;&nbsp;
                    <a title="Delete Account manager" data-route="{{ route('admin.prospects.delete', $prospect->id) }}"
                        href="javascipt:void(0);" id="delete"><i class="fas fa-trash"></i></a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
<script>
    $(document).ready(function() {
        //Default data table
        $('#myTable').DataTable({
            "aaSorting": [],
            "columnDefs": [{
                    "orderable": false,
                    "targets": [11]
                },
                {
                    "orderable": true,
                    "targets": [0, 1, 2, 5, 6, 7, 8, 9, 10]
                }
            ]
        });

    });
</script>
