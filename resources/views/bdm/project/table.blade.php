@if (count($projects) == 0)
    <tr>
        <td colspan="11" class="text-center">No Projects Found</td>
    </tr>
@else
    @foreach ($projects as $key => $project)
        <tr class="view-route" data-route="{{ route('bdm.projects.show', $project->id) }}">
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
                @foreach ($project->projectTypes as $index => $projectType)
                <span class="">{{ Str::limit($projectType->type, 20) }}</span>
                @if (!$loop->last)
                    <span>,</span>
                @endif
                @endforeach
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
                {{ $project->projectMilestones->where('payment_status', 'Paid')->sum('milestone_value') }}
            </td>
            <td class="edit-route" data-route="{{ route('bdm.projects.edit', $project->id) }}">
                {{ (int) $project->project_value - ((int) $project->project_upfront + (int) $project->projectMilestones->where('payment_status', 'Paid')->sum('milestone_value')) }}
            </td>
            <td>
                <a title="View Follow-ups" class="view-followups btn btn-sm " data-id="{{ $project->id }}" href="javascript:void(0);"><i class="fas fa-comments" style="color: #ff9b44;"></i></a>
            </td>
        </tr>
    @endforeach
@endif

<tr>
    
    <td colspan="11">
        <div class="d-flex justify-content-between align-items-center">
            <div class="">
                (Showing {{ $projects->firstItem() }} – {{ $projects->lastItem() }} Projects of
                {{ $projects->count() }} Projects)
            </div>
            <div class="d-flex justify-content-center">
                {!! $projects->links() !!}
            </div>
        </div>
    </td>
</tr>

