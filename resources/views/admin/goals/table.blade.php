<table id="myTable" class="dd table table-striped table-bordered table-hover" style="width:100%">
    <thead>
        <tr>
            <th> Goals Date</th>
            <th> Goals Type</th>
            <th> Goal Assign For</th>
            <th> Target Amount </th>
            <th> Target Achieve</th>
            <th> Action</th>
        </tr>
    </thead>
    <tbody>
        @if (count($goals) == 0)
        <tr>
            <td colspan="8" class="text-center">No Goals found</td>
        </tr>
    @else
        @foreach ($goals as $key => $goal)
            <tr>
                <td>
                    {{ $goal->goals_date}}
                </td>
                <td>
                    {{ $goal->goals_type == 1 ? 'Gross' : 'Net' }}
                </td>
                <td>
                    {{ $goal->user->name }}
                </td>
                <td>
                    {{ $goal->goals_amount }}
                </td>
                <td>
                    {{ $goal->goals_achieve ?? 0 }}
                </td>
                <td>
                    <a href="javascript:void(0);"
                        data-route="{{ route('goals.edit', $goal->id) }}"
                        data-role="ACCOUNT_MANAGER" class="edit-data"><i
                            class="fas fa-edit"></i> </a> &nbsp;
                    <a title="Delete Project"
                        data-route="{{ route('goals.delete', $goal->id) }}"
                        href="javascipt:void(0);" id="delete"><i
                            class="fas fa-trash"></i></a>
                </td>
            </tr>
        @endforeach
    @endif
    </tbody>
</table>

<div class="d-flex justify-content-center">
    {!! $goals->links() !!}
</div>