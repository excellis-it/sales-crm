@if (count($projects) == 0)
<tr>
    <td colspan="10" class="text-center">No Project found</td>
</tr>
@else
    @foreach ($projects as $key => $project)
        <tr>
            <td class="edit-route" data-route="{{ route('account-manager.projects.edit', $project->id) }}">
                {{ ($project->sale_date) ? date('d-m-Y', strtotime($project->sale_date)) : '' }}
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
                <span class="">{{ $project->projectTypes->type ?? '' }}</span>
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
                {{ (int)$project->project_value - (int)$project->project_upfront }}
            </td>
            {{-- <td>
                <a title="View Project" data-route=""
                    href="{{ route('account-manager.projects.show', $project->id) }}"><i
                        class="fas fa-eye"></i></a>
            </td> --}}
        </tr>
    @endforeach

    <tr>
        <td colspan="10">
            <div class="d-flex justify-content-center">
                {!! $projects->links() !!}
            </div>
        </td>
    </tr>
@endif

