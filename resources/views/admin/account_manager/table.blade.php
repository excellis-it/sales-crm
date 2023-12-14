<table id="myTable" class="dd table table-striped table-hover" style="width:100%">
    <thead>
        <tr>
            <th> Name</th>
            <th> Email</th>
            <th> Phone</th>
            <th>Employee Id</th>
            <th>Date Of Joining</th>
            <th>No. of Project</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @if (count($account_managers) == 0)
            <tr>
                <td colspan="8" class="text-center">No Account manager found</td>
            </tr>
        @else
            @foreach ($account_managers as $key => $account_manager)
                <tr>
                    <td class="edit-route" data-route="{{ route('account_managers.edit', $account_manager['id']) }}">{{ $account_manager->name }}</td>
                    <td class="edit-route" data-route="{{ route('account_managers.edit', $account_manager['id']) }}">{{ $account_manager->email }}</td>
                    <td class="edit-route" data-route="{{ route('account_managers.edit', $account_manager['id']) }}">{{ $account_manager->phone }}</td>
                    <td class="edit-route" data-route="{{ route('account_managers.edit', $account_manager['id']) }}">{{ $account_manager->employee_id }}</td>
                    <td class="edit-route" data-route="{{ route('account_managers.edit', $account_manager['id']) }}">{{ $account_manager->date_of_joining }}</td>
                    <td><a
                            href="{{ route('sales-projects.index', ['account_manager_id' => $account_manager->id]) }}">{{ $account_manager->accountManagerProjects->count() }}</a>
                    </td>
                    <td>
                        <div class="button-switch">
                            <input type="checkbox" id="switch-orange" class="switch toggle-class"
                                data-id="{{ $account_manager['id'] }}"
                                {{ $account_manager['status'] ? 'checked' : '' }} />
                            <label for="switch-orange" class="lbl-off"></label>
                            <label for="switch-orange" class="lbl-on"></label>
                        </div>
                        {{-- <span class="edit_active">
                            @if ($account_manager->status == 0)
                            <i class="fas fa-edit"></i> Inactive
                            @else
                            <i class="fas fa-edit"></i> Active

                            @endif

                        </span> --}}
                    </td>

                    <td>
                        {{-- <a title="Edit Account manager" data-route=""
                            href="{{ route('account_managers.edit', $account_manager->id) }}"
                            class="edit_acma"><i class="fas fa-edit"></i></a> &nbsp;&nbsp; --}}

                        <a title="Delete Account manager"
                            data-route="{{ route('account_managers.delete', $account_manager->id) }}  class="delete_acma""
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
    {!! $account_managers->links() !!}
</div>
