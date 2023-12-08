<table id="myTable" class="dd table table-striped table-hover" style="width:100%">
    <thead>
        <tr>
            <th> Name</th>
            <th> Email</th>
            <th> Phone</th>
            <th> Address</th>
            <th> Project value</th>
            <th> Total Project</th>
            <th> Action</th>
        </tr>
    </thead>
    <tbody>
        @if (count($customers) == 0)
            <tr>
                <td colspan="7" class="text-center">No Customer found</td>
            </tr>
        @else
            @foreach ($customers as $key => $customer)
                <tr>
                    <td class="edit-route" data-route="{{ route('customers.edit', $customer['id']) }}">{{ $customer->customer_name }}</td>
                    <td class="edit-route" data-route="{{ route('customers.edit', $customer['id']) }}">{{ $customer->customer_email }}</td>
                    <td class="edit-route" data-route="{{ route('customers.edit', $customer['id']) }}">{{ $customer->customer_phone }}</td>
                    <td class="edit-route" data-route="{{ route('customers.edit', $customer['id']) }}">{{ $customer->customer_address }}</td>
                    <td>
                       ${{ $customer->projects()->sum('project_value') }}
                    </td>
                    <td> {{ $customer->projects->count() }}</td>


                    <td>
                        <a title="Delete Customer"
                            data-route="{{ route('customers.delete', ['id' => $customer->id]) }}"  class="delete_acma"
                            href="javascipt:void(0);" id="delete"><i
                                class="fas fa-trash"></i></a>
                    </td>
                </tr>
            @endforeach
            <tr>
                <td colspan="7">
                    <div class="d-flex justify-content-center">
                        {!! $customers->links() !!}
                    </div>
                </td>
            </tr>
        @endif

    </tbody>
</table>
