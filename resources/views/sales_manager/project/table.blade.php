@if (count($projects) == 0)
    <tr>
        <td colspan="11" class="text-center">No Projects Found</td>
    </tr>
@else
    @foreach ($projects as $key => $project)
        <tr>
            <td class="edit-route" data-route="{{ route('projects.edit', $project->id) }}">
                {{ $project->sale_date ? date('d-m-Y', strtotime($project->sale_date)) : '' }}
            </td>
            <td class="edit-route" data-route="{{ route('projects.edit', $project->id) }}">
                {{ $project->business_name }}
            </td>
            <td class="edit-route" data-route="{{ route('projects.edit', $project->id) }}">
                {{ $project->client_name }}
            </td>
            <td class="edit-route" data-route="{{ route('projects.edit', $project->id) }}">
                {{ $project->client_phone }}
            </td>
            <td class="edit-route" data-route="{{ route('projects.edit', $project->id) }}">
                @foreach ($project->projectTypes as $index => $projectType)
                <span class="">{{ Str::limit($projectType->type, 20) }}</span>
                @if (!$loop->last)
                    <span>,</span>
                @endif
            @endforeach
            </td>
            <td class="edit-route" data-route="{{ route('projects.edit', $project->id) }}">
                {{ $project->project_value }}
            </td>

            <td class="edit-route" data-route="{{ route('projects.edit', $project->id) }}">
                {{ $project->project_upfront }}
            </td>
            <td class="edit-route" data-route="{{ route('projects.edit', $project->id) }}">
                {{ $project->currency }}
            </td>
            <td class="edit-route" data-route="{{ route('projects.edit', $project->id) }}">
                {{ $project->payment_mode }}
            </td>
            <td class="edit-route" data-route="{{ route('projects.edit', $project->id) }}">
                {{ (int) $project->project_value - (int) $project->project_upfront }}
            </td>

            {{-- <td>

        <a title="View Project" data-route=""
            href="{{ route('projects.show', $project->id) }}"><i
                class="fas fa-eye"></i></a> &nbsp;&nbsp;


        <a title="Delete Project"
            data-route="{{ route('projects.delete', $project->id) }}"
            href="javascipt:void(0);" id="delete"><i class="fas fa-trash"></i></a>
    </td> --}}
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



