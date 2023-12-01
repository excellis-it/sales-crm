@if (count($projects) == 0)
    <tr>
        <td colspan="12" class="text-center">No Projects Found</td>
    </tr>
@else
    @foreach ($projects as $key => $project)
        <tr>
            <td class="edit-route" data-route="{{ route('sales-projects.edit', $project->id) }}">
                {{ $project->sale_date ? date('d-m-Y', strtotime($project->sale_date)) : '' }}
            </td>
            <td class="edit-route" data-route="{{ route('sales-projects.edit', $project->id) }}">
                {{ $project->salesManager->name ?? '' }}
            </td>
            <td class="edit-route" data-route="{{ route('sales-projects.edit', $project->id) }}">
                {{ $project->business_name ?? '' }}
            </td>
            <td class="edit-route" data-route="{{ route('sales-projects.edit', $project->id) }}">
                {{ $project->client_name ?? '' }}
            </td>
            <td class="edit-route" data-route="{{ route('sales-projects.edit', $project->id) }}">
                {{ $project->client_phone ?? '' }}
            </td class="edit-route" data-route="{{ route('sales-projects.edit', $project->id) }}">
            <td class="edit-route" data-route="{{ route('sales-projects.edit', $project->id) }}">
                {{ $project->project_value ?? '' }}
            </td class="edit-route" data-route="{{ route('sales-projects.edit', $project->id) }}">

            <td class="edit-route" data-route="{{ route('sales-projects.edit', $project->id) }}">
                {{ $project->project_upfront ?? '' }}
            </td>
            <td class="edit-route" data-route="{{ route('sales-projects.edit', $project->id) }}">
                {{ $project->currency ?? '' }}
            </td>
            <td class="edit-route" data-route="{{ route('sales-projects.edit', $project->id) }}">
                {{ $project->payment_mode ?? '' }}
            </td>
            <td class="edit-route" data-route="{{ route('sales-projects.edit', $project->id) }}">
                {{ (int) $project->project_value - (int) $project->project_upfront }}
            </td>
            <td>
                @if ($project->assigned_to == null)
                    <span class="badge bg-danger">Not Assigned</span>
                @else
                    <span class="badge bg-success">Assigned</span>
                @endif
            </td>
            <td>
                <a title="View Project" data-route="" href="{{ route('sales-projects.show', $project->id) }}"><i
                        class="fas fa-eye"></i></a> &nbsp;&nbsp;


                <a title="Delete Project" data-route="{{ route('sales-projects.delete', $project->id) }}"
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

