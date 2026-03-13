<table class="dd table table-striped  table-hover" style="width:100%">
    <thead>
        <tr>
            <th> Name</th>
            <th> Email</th>
            <th> Phone</th>
            <th> Employee Id</th>
            <th> Date Of Joining</th>
            {{-- <th> No. of sales</th> --}}
            <th> Status</th>
            <th> Action</th>
        </tr>
    </thead>
    <tbody>
        @if (count($tender_users) == 0)
            <tr>
                <td colspan="8" class="text-center">No Tender User found</td>
            </tr>
        @else
            @foreach ($tender_users as $key => $tender_user)
                <tr>
                    <td class="edit-route" data-route="{{ route('tender-users.edit', $tender_user['id']) }}">{{ $tender_user->name }}</td>
                    <td class="edit-route" data-route="{{ route('tender-users.edit', $tender_user['id']) }}">{{ $tender_user->email }}</td>
                    <td class="edit-route" data-route="{{ route('tender-users.edit', $tender_user['id']) }}">{{ $tender_user->phone }}</td>
                    <td class="edit-route" data-route="{{ route('tender-users.edit', $tender_user['id']) }}">{{ $tender_user->employee_id }}</td>
                    <td class="edit-route" data-route="{{ route('tender-users.edit', $tender_user['id']) }}">{{ $tender_user->date_of_joining }}</td>
                    {{-- <td><a
                            href="{{ route('sales-projects.index', ['tender_user_id' => $tender_user->id]) }}">{{ $tender_user->projects->count() }}</a>
                    </td> --}}
                    <td>
                        <div class="button-switch">
                            <input type="checkbox" id="switch-orange" class="switch toggle-class"
                                data-id="{{ $tender_user['id'] }}"
                                {{ $tender_user['status'] ? 'checked' : '' }} />
                            <label for="switch-orange" class="lbl-off"></label>
                            <label for="switch-orange" class="lbl-on"></label>
                        </div>
                    </td>
                
                    <td>
                        <a title="Delete Tender User"
                            data-route="{{ route('tender-users.delete', $tender_user->id) }}"
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
    {!! $tender_users->links() !!}
</div>
