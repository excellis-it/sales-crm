<table class="dd table table-striped table-bordered" style="width:100%">
    <thead>
        <tr>
            <th> Name</th>
            <th> Email</th>
            <th> Phone</th>
            <th>Employee Id</th>
            <th>Date Of Joining</th>
            <th>No. of sales</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @if (count($sales_managers) > 0)
            @foreach ($sales_managers as $key => $sales_manager)
                <tr>
                    <td>{{ $sales_manager->name }}</td>
                    <td>{{ $sales_manager->email }}</td>
                    <td>{{ $sales_manager->phone }}</td>
                    <td>{{ $sales_manager->employee_id }}</td>
                    <td>{{ $sales_manager->date_of_joining }}</td>
                    <td><a
                            href="{{ route('sales-projects.index', ['sales_manager_id' => $sales_manager->id]) }}">{{ $sales_manager->projects->count() }}</a>
                    </td>
                    <td>
                        <div class="button-switch">
                            <input type="checkbox" id="switch-orange" class="switch toggle-class"
                                data-id="{{ $sales_manager['id'] }}"
                                {{ $sales_manager['status'] ? 'checked' : '' }} />
                            <label for="switch-orange" class="lbl-off"></label>
                            <label for="switch-orange" class="lbl-on"></label>
                        </div>
                    </td>
                    <td>
                        <a title="Edit Sales manager" data-route=""
                            href="{{ route('sales_managers.edit', $sales_manager->id) }}"><i
                                class="fas fa-edit"></i></a> &nbsp;&nbsp;

                        <a title="Delete Sales manager"
                            data-route="{{ route('sales_managers.delete', $sales_manager->id) }}"
                            href="javascipt:void(0);" id="delete"><i
                                class="fas fa-trash"></i></a>
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="8" class="text-center">No Sales manager found</td>
            </tr>
        @endif

    </tbody>
</table>
{{-- pagination --}}
<div class="d-flex justify-content-center">
    {!! $sales_managers->links() !!}
</div>
