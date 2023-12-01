@if (count($projects) == 0)
    <tr>
        <td colspan="12" class="text-center">No Projects Found</td>
    </tr>
@else
    @foreach ($projects as $key => $project)
        <tr class="view-route" data-route="{{ route('sales-projects.show', $project->id) }}">
            <td class="edit-route" data-route="{{ route('bdm.projects.edit', $project->id) }}">
                {{ $project->sale_date ? date('d-m-Y', strtotime($project->sale_date)) : '' }}
            </td>
            <td class="edit-route" data-route="{{ route('bdm.projects.edit', $project->id) }}">
                {{ $project->business_name ?? '' }}
            </td>
            <td class="edit-route" data-route="{{ route('bdm.projects.edit', $project->id) }}">
                {{ $project->client_name ?? '' }}
            </td>
            <td class="edit-route" data-route="{{ route('bdm.projects.edit', $project->id) }}">
                {{ $project->client_phone ?? '' }}
            </td>
            <td class="edit-route" data-route="{{ route('bdm.projects.edit', $project->id) }}">
                <span class="">{{ $project->projectTypes->type }}</span>
            </td>
            <td class="edit-route" data-route="{{ route('bdm.projects.edit', $project->id) }}">
                {{ $project->project_value ?? '' }}
            </td>

            <td class="edit-route" data-route="{{ route('bdm.projects.edit', $project->id) }}">
                {{ $project->project_upfront ?? '' }}
            </td>
            <td class="edit-route" data-route="{{ route('bdm.projects.edit', $project->id) }}">
                {{ $project->currency ?? '' }}
            </td>
            <td class="edit-route" data-route="{{ route('bdm.projects.edit', $project->id) }}">
                {{ $project->payment_mode ?? '' }}
            </td>
            <td class="edit-route" data-route="{{ route('bdm.projects.edit', $project->id) }}">
                {{ (int) $project->project_value - (int) $project->project_upfront }}
            </td>
            <td>
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

