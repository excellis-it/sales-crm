<table id="myTable" class="dd table table-striped table-bordered" style="width:100%">
    <thead>
        <tr>
            <th>Date</th>
            <th>Prospect By</th>
            <th>Client Name</th>
            <th>Business Name</th>
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


    </tbody>
</table>



@push('scripts')
    {{-- @dd($_GET) --}}
    <script>
        $(document).ready(function() {
            var user_id = '{{ $_GET['user_id'] ?? '' }}'
            console.log(user_id);
            var table = $('#myTable').DataTable({
                "order": [[ 0, "desc" ]],
                processing: true,
                serverSide: true,
                destroy: true,
                ajax: "{{ route('prospect.ajax-list') }}?user_id=" + user_id,
                columns: [{
                        data: 'sale_date',
                        name: 'sale_date'
                    },
                    {
                        data: 'prospect_by',
                        name: 'prospect_by',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'client_name',
                        name: 'client_name'
                    },
                    {
                        data: 'business_name',
                        name: 'business_name'
                    },

                    {
                        data: 'client_email',
                        name: 'client_email'
                    },
                    {
                        data: 'client_phone',
                        name: 'client_phone'
                    },
                    {
                        data: 'transfer_by',
                        name: 'transfer_by',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'service_offered',
                        name: 'service_offered',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'followup_date',
                        name: 'followup_date'
                    },
                    {
                        data: 'price_quote',
                        name: 'price_quote'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });

        });
    </script>
@endpush
