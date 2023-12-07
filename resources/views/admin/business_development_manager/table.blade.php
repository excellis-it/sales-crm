<table class="dd table table-striped  table-hover" style="width:100%">
    <thead>
        <tr>
            <th> Name</th>
            <th> Email</th>
            <th> Phone</th>
            <th> Employee Id</th>
            <th> Date Of Joining</th>
            <th> No. of sales</th>
            <th> Status</th>
            <th> Action</th>
        </tr>
    </thead>
    <tbody>
        @if (count($business_development_managers) == 0)
            <tr>
                <td colspan="8" class="text-center">No BDM found</td>
            </tr>
        @else
            @foreach ($business_development_managers as $key => $business_development_manager)
                <tr>
                    <td class="edit-route" data-route="{{ route('business-development-managers.edit', $business_development_manager['id']) }}">{{ $business_development_manager->name }}</td>
                    <td class="edit-route" data-route="{{ route('business-development-managers.edit', $business_development_manager['id']) }}">{{ $business_development_manager->email }}</td>
                    <td class="edit-route" data-route="{{ route('business-development-managers.edit', $business_development_manager['id']) }}">{{ $business_development_manager->phone }}</td>
                    <td class="edit-route" data-route="{{ route('business-development-managers.edit', $business_development_manager['id']) }}">{{ $business_development_manager->employee_id }}</td>
                    <td class="edit-route" data-route="{{ route('business-development-managers.edit', $business_development_manager['id']) }}">{{ $business_development_manager->date_of_joining }}</td>
                    <td><a
                            href="{{ route('sales-projects.index', ['sales_manager_id' => $business_development_manager->id]) }}">{{ $business_development_manager->projects->count() }}</a>
                    </td>
                    <td>
                        <div class="button-switch">
                            <input type="checkbox" id="switch-orange" class="switch toggle-class"
                                data-id="{{ $business_development_manager['id'] }}"
                                {{ $business_development_manager['status'] ? 'checked' : '' }} />
                            <label for="switch-orange" class="lbl-off"></label>
                            <label for="switch-orange" class="lbl-on"></label>
                        </div>
                    </td>
                    <td>
                        <a title="Edit BDM" data-route=""
                            href="{{ route('business-development-managers.edit', $business_development_manager->id) }}"><i
                                class="fas fa-edit"></i></a> &nbsp;&nbsp;

                        <a title="Delete BDM"
                            data-route="{{ route('business-development-managers.delete', $business_development_manager->id) }}"
                            href="javascipt:void(0);" id="delete"><i
                                class="fas fa-trash"></i></a>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
{{-- pagination --}}
<div class="d-flex justify-content-center">
    {!! $business_development_managers->links() !!}
</div>
