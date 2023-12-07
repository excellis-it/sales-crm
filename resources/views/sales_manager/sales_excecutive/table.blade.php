<table id="myTable" class="dd table table-striped  table-hover" style="width:100%">
    <thead>
        <tr>
            <th> Name</th>
            <th> Email</th>
            <th> Phone</th>
            <th> Employee Id</th>
            <th> Date Of Joining</th>
            <th> No. of prospect</th>
            <th> Status</th>
            <th> Action</th>
        </tr>
    </thead>
    <tbody>
    @if (count($sales_excecutives) == 0)
        <tr>
            <td colspan="8" class="text-center">No sales executive found</td>
        </tr>
    @else
        @foreach ($sales_excecutives as $key => $sales_excecutive)
            <tr>
                <td class="edit-route" data-route="{{ route('sales-manager.sales-excecutive.edit', $sales_excecutive->id) }}">{{ $sales_excecutive->name }}</td>
                <td class="edit-route" data-route="{{ route('sales-manager.sales-excecutive.edit', $sales_excecutive->id) }}">{{ $sales_excecutive->email }}</td>
                <td class="edit-route" data-route="{{ route('sales-manager.sales-excecutive.edit', $sales_excecutive->id) }}">{{ $sales_excecutive->phone }}</td>
                <td class="edit-route" data-route="{{ route('sales-manager.sales-excecutive.edit', $sales_excecutive->id) }}">{{ $sales_excecutive->employee_id }}</td>
                <td class="edit-route" data-route="{{ route('sales-manager.sales-excecutive.edit', $sales_excecutive->id) }}">{{ $sales_excecutive->date_of_joining }}</td>
                <td class="edit-route" data-route="{{ route('sales-manager.sales-excecutive.edit', $sales_excecutive->id) }}">{{ $sales_excecutive->prospects->count() }}</td>
                <td>
                    <div class="button-switch">
                        <input type="checkbox" id="switch-orange" class="switch toggle-class"
                            data-id="{{ $sales_excecutive['id'] }}"
                            {{ $sales_excecutive['status'] ? 'checked' : '' }} />
                        <label for="switch-orange" class="lbl-off"></label>
                        <label for="switch-orange" class="lbl-on"></label>
                    </div>
                </td>
                <td>
                    <a title="Edit Sales excecutive" data-route=""
                        href="{{ route('sales-manager.sales-excecutive.edit', $sales_excecutive->id) }}"><i
                            class="fas fa-edit"></i></a> &nbsp;&nbsp;

                    <a title="Delete Sales excecutive"
                        data-route="{{ route('sales-manager.sales-excecutive.delete', $sales_excecutive->id) }}"
                        href="javascipt:void(0);" id="delete"><i class="fas fa-trash"></i></a>
                </td>
            </tr>
        @endforeach
    @endif
    </tbody>
</table>
