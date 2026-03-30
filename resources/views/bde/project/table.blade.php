
@if (count($projects) == 0)
<tr>
    <td colspan="12" class="text-center">No Projects found</td>
</tr>
@else

    @foreach ($projects as $key => $project)
        <tr>
            <td>
                {{ ($project->sale_date) ?  date('d-m-Y', strtotime($project->sale_date)) : '' }}
            </td>
            <td>
                {{ $project->business_name }}
            </td>
            {{-- <td>
                {{ $project->client_name }}
            </td>
            <td>
                {{ $project->client_phone }}
            </td> --}}
            <td>
                @foreach ($project->projectTypes as $index => $projectType)
                <span class="">{{ Str::limit($projectType->type, 20) }}</span>
                @if (!$loop->last)
                    <span>,</span>
                @endif
                @endforeach
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
                {{ $project->projectMilestones->where('payment_status', 'Paid')->sum('milestone_value') }}
            </td>
            <td>
                {{ (int)$project->project_value - ((int)$project->project_upfront + (int)$project->projectMilestones->where('payment_status', 'Paid')->sum('milestone_value')) }}
            </td>
            <td>
                <a title="View Project" data-route=""
                    href="{{ route('bde-projects.show', $project->id) }}"><i
                        class="fas fa-eye"></i></a>
                <a title="View Follow-ups" class="view-followups" data-id="{{ $project->id }}" href="javascript:void(0);"><i class="fas fa-comments" style="color: #ff9b44;  margin-left: 10px;"></i></a>
            </td>
    </tr>
    @endforeach
@endif

<tr>
    <td colspan="13">
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
