<table class="table table-striped custom-table mb-0">
    <thead>
        <tr>
            <th>Tender ID</th>
            <th>Department</th>
            <th>Category</th>
            <th>Value (Lakhs)</th>
            <th>Status</th>
            <th class="text-end">Action</th>
        </tr>
    </thead>
    <tbody>
        @if(count($tender_projects) > 0)
            @foreach($tender_projects as $key => $tender)
                <tr>
                    <td>
                        <strong>{{ $tender->tender_id_ref_no }}</strong><br>
                        <small>{{ $tender->tender_name }}</small>
                    </td>
                    <td>{{ $tender->department_org }}</td>
                    <td>
                        {{ $tender->category }}
                        @if($tender->category_title)
                            <br><small class="text-muted">({{ $tender->category_title }})</small>
                        @endif
                    </td>
                    <td>{{ $tender->tender_value_lakhs }}</td>
                    <td>
                        <span class="badge bg-inverse-{{ $tender->tenderStatus->status ? 'success' : 'danger' }}">
                            {{ $tender->tenderStatus->name }}
                        </span>
                    </td>
                    <td class="text-end">
                        <div class="dropdown dropdown-action">
                            <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown"
                                aria-expanded="false"><i class="material-icons">more_vert</i></a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item view-followups" href="javascript:void(0);"
                                    data-id="{{ $tender->id }}"><i class="fa fa-eye m-r-5"></i> Remarks</a>
                                <a class="dropdown-item" href="{{ route('tender-user.tender-projects.edit', $tender->id) }}"><i
                                        class="fa fa-pencil m-r-5"></i> Edit</a>
                                <form action="{{ route('tender-user.tender-projects.destroy', $tender->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="dropdown-item delete-tender" onclick="return confirm('Are you sure you want to delete this project?')"><i
                                            class="fa fa-trash-o m-r-5"></i> Delete</button>
                                </form>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="6" class="text-center">No Data Found</td>
            </tr>
        @endif
    </tbody>
</table>
<div class="d-flex justify-content-center mt-3">
    {!! $tender_projects->appends(request()->input())->links() !!}
</div>
