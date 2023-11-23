@if (count($projects) == 0)
    <tr>
        <td colspan="12" class="text-center">No Projects Found</td>
    </tr>
@else
    @foreach ($projects as $key => $project)
        <tr class="view-route" data-route="{{ route('sales-projects.show', $project->id) }}">
            <td>
                {{ $project->sale_date ? date('d-m-Y', strtotime($project->sale_date)) : '' }}
            </td>
            <td>
                {{ $project->business_name ?? '' }}
            </td>
            <td>
                {{ $project->client_name ?? '' }}
            </td>
            <td>
                {{ $project->client_phone ?? '' }}
            </td>
            <td>
                <span class="">{{ $project->projectTypes->type }}</span>
            </td>
            <td>
                {{ $project->project_value ?? '' }}
            </td>

            <td>
                {{ $project->project_upfront ?? '' }}
            </td>
            <td>
                {{ $project->currency ?? '' }}
            </td>
            <td>
                {{ $project->payment_mode ?? '' }}
            </td>
            <td>
                {{ (int) $project->project_value - (int) $project->project_upfront }}
            </td>
            <td>
                <a title="Edit Project" data-route="" href="{{ route('bdm.projects.edit', $project->id) }}" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a> &nbsp;&nbsp;
                <a title="Delete Project" class="btn btn-sm btn-danger" data-route="{{ route('bdm.projects.delete', $project->id) }}"
                    href="javascipt:void(0);" id="delete"><i class="fas fa-trash"></i></a>
            </td>
        </tr>
    @endforeach
@endif

<tr>
    <td colspan="12">
        <div class="d-flex justify-content-center">
            {!! $projects->links() !!}
        </div>
    </td>
</tr>

