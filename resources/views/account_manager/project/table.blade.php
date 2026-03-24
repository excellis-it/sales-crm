@if (count($projects) == 0)
    <tr>
        <td colspan="12" class="text-center">No Project found</td>
    </tr>
@else
    @foreach ($projects as $key => $project)
        <tr>
            <td class="edit-route" data-route="{{ route('account-manager.projects.edit', $project->id) }}">
                {{ $project->sale_date ? date('d-m-Y', strtotime($project->sale_date)) : '' }}
            </td>
            <td class="edit-route" data-route="{{ route('account-manager.projects.edit', $project->id) }}">
                {{ $project->business_name }}
            </td>
            <td class="edit-route" data-route="{{ route('account-manager.projects.edit', $project->id) }}">
                {{ $project->client_name }}
            </td>
            <td class="edit-route" data-route="{{ route('account-manager.projects.edit', $project->id) }}">
                {{ $project->client_phone }}
            </td>
            <td class="edit-route" data-route="{{ route('account-manager.projects.edit', $project->id) }}">
                @foreach ($project->projectTypes as $index => $projectType)
                    <span class="">{{ Str::limit($projectType->type, 20) }}</span>
                    @if (!$loop->last)
                        <span>,</span>
                    @endif
                @endforeach
            </td>
            <td class="edit-route" data-route="{{ route('account-manager.projects.edit', $project->id) }}">
                {{ $project->project_value }}
            </td>

            <td class="edit-route" data-route="{{ route('account-manager.projects.edit', $project->id) }}">
                {{ $project->project_upfront }}
            </td>
            <td class="edit-route" data-route="{{ route('account-manager.projects.edit', $project->id) }}">
                {{ $project->currency }}
            </td>
            <td class="edit-route" data-route="{{ route('account-manager.projects.edit', $project->id) }}">
                {{ $project->payment_mode }}
            </td>
            <td class="edit-route" data-route="{{ route('account-manager.projects.edit', $project->id) }}">
                {{ $project->projectMilestones->where('payment_status', 'Paid')->sum('milestone_value') }}
            </td>
            <td class="edit-route" data-route="{{ route('account-manager.projects.edit', $project->id) }}">
                {{ (int) $project->project_value - ((int) $project->project_upfront + (int) $project->projectMilestones->where('payment_status', 'Paid')->sum('milestone_value')) }}
            </td>
            <td>
                <a title="View Follow-ups" class="view-followups" data-id="{{ $project->id }}"
                    href="javascript:void(0);"><i class="fas fa-comments text-primary"></i></a>
            </td>
        </tr>
    @endforeach

    <tr>
        <td colspan="12">
            <div class="d-flex justify-content-between align-items-center">
                <div class="">
                    {!! $projects->links() !!}
                </div>
                <div class="d-flex justify-content-center">
                    (Showing {{ $projects->firstItem() }} – {{ $projects->lastItem() }} Projects of
                    {{ $projects->count() }} Projects)

                </div>


                {{-- page no of page --}}

            </div>

        </td>


    </tr>
@endif
