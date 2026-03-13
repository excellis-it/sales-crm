<table class="table table-striped custom-table mb-0 ">
    <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Status</th>
            <th class="text-end">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($tender_statuses as $key => $status)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $status->name }}</td>
                <td>
                    <div class="status-toggle">
                        <input type="checkbox" id="status_{{ $status->id }}" class="check toggle-class"
                            data-id="{{ $status->id }}" {{ $status->status ? 'checked' : '' }}>
                        <label for="status_{{ $status->id }}" class="checktoggle">checkbox</label>
                    </div>
                </td>
                <td class="text-end">
                    <div class="dropdown dropdown-action">
                        <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown"
                            aria-expanded="false"><i class="material-icons">more_vert</i></a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item edit-route" href="javascript:void(0);"
                                data-route="{{ route('tender-user.tender-statuses.edit', $status->id) }}"><i
                                    class="fa fa-pencil m-r-5"></i> Edit</a>
                            <a class="dropdown-item delete-status" href="javascript:void(0);"
                                data-route="{{ route('tender-user.tender-statuses.delete', $status->id) }}"><i
                                    class="fa fa-trash-o m-r-5"></i> Delete</a>
                        </div>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="text-center">No Data Found</td>
            </tr>
        @endforelse
    </tbody>
</table>
<div class="d-flex justify-content-center">
    {!! $tender_statuses->links() !!}
</div>
