@foreach ($projects as $key => $project)
<tr>
    <td>
        {{ ($project->sale_date) ?  date('d-m-Y', strtotime($project->sale_date) ) : '' }}
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
        <span>{{$project->projectTypes->type ?? '' }}</span>
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
        <a title="Edit Project" data-route=""
            href="{{ route('projects.edit', $project->id) }}"><i
                class="fas fa-edit"></i></a> &nbsp;&nbsp;

        <a title="View Project" data-route=""
            href="{{ route('projects.show', $project->id) }}"><i
                class="fas fa-eye"></i></a> &nbsp;&nbsp;


        <a title="Delete Project"
            data-route="{{ route('projects.delete', $project->id) }}"
            href="javascipt:void(0);" id="delete"><i class="fas fa-trash"></i></a>
    </td>
</tr>
@endforeach

<tr>
    <td colspan="12">
        <div class="d-flex justify-content-center">
            {!! $projects->links() !!}
        </div>
    </td>
</tr>