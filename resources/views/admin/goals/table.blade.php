<table id="myTable" class="dd table table-striped  table-hover" style="width:100%">
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
                <td colspan="6" class="text-center">No Goals found</td>
            </tr>
        @else
            @foreach ($goals as $key => $goal)
                <tr>
                    <td>
                        {{ $goal->goals_date }}
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
                        @if ($goal->goals_type == 1)

                            <a title="Delete Project" data-route="{{ route('goals.delete', $goal->id) }}"
                                href="javascipt:void(0);" id="delete"><i class="fas fa-trash"></i></a>&nbsp;
                                <a href="javascript:void(0);" data-route="{{ route('goals.edit', $goal->id) }}"
                                    data-role="@if($goal->user->hasRole('SALES_MANAGER'))SALES_MANAGER @elseif($goal->user->hasRole('SALES_EXCUETIVE'))SALES_EXCUETIVE @elseif($goal->user->hasRole('BUSINESS_DEVELOPMENT_MANAGER'))BUSINESS_DEVELOPMENT_MANAGER @else BUSINESS_DEVELOPMENT_EXCECUTIVE @endif" data-toggle="modal" class="edit-data"> <i class="fas fa-edit"></i></a>
                        @else
                            <a title="Delete Project" data-route="{{ route('goals.delete', $goal->id) }}"
                                href="javascipt:void(0);" id="delete"><i class="fas fa-trash"></i></a>
                        @endif

                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>

<div class="d-flex justify-content-center">
    {!! $goals->links() !!}
</div>
