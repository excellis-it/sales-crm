@if (count($projects) == 0)
<tr>
    <td colspan="11" class="text-center">No Project found</td>
</tr>
@else
    @foreach ($projects as $key => $project)
        <tr>
            <td>
                {{ ($project->sale_date) ? $project->sale_date : '' }}
            </td>
            <td>
                {{ $project->business_name }}
            </td>
            <td>
                {{ $project->client_name }}
            </td>
            <td>
                {{ $project->client_phone }}
            </td>
            <td>
                <span class="">{{ $project->projectTypes->type }}</span>
            </td>
            <td>
                {{ $project->project_value }}
            </td>

            <td>
                {{ $project->project_upfront }}
            </td>
            <td>
                {{ $project->currency }}
            </td>
            <td>
                {{ $project->payment_mode }}
            </td>
            <td>
                {{ (int)$project->project_value - (int)$project->project_upfront }}
            </td>
            <td>
                <a title="View Project" data-route=""
                    href="{{ route('account-manager.projects.show', $project->id) }}"><i
                        class="fas fa-eye"></i></a> &nbsp;&nbsp;
                <a title="Edit Project" data-route=""
                    href="{{ route('account-manager.projects.edit', $project->id) }}"><i
                        class="fas fa-edit"></i></a> &nbsp;&nbsp;
            </td>
        </tr>
    @endforeach

    <tr>
        <td colspan="11">
            <div class="d-flex justify-content-center">
                {!! $projects->links() !!}
            </div>
        </td>
    </tr>
@endif

