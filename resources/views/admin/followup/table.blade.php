@if (count($projects) == 0)
<tr>
    <td colspan="6" class="text-center">No follow_up found</td>
</tr>
@else
    @foreach ($projects as $key => $project)

        <tr>
            <td class="view-details-btn" data-bs-toggle="modal"
            data-bs-target="#exampleModal" data-route="{{route('account-manager.followups.show', $project->id)}}">
        {{ $project->accountManager->name }}
    </td>
            <td class="view-details-btn" data-bs-toggle="modal"
                    data-bs-target="#exampleModal" data-route="{{route('admin.followups.show', $project->id)}}">
                {{ $project->business_name }}
            </td>
            <td class="view-details-btn" data-bs-toggle="modal"
                    data-bs-target="#exampleModal" data-route="{{route('admin.followups.show', $project->id)}}">
                {{ $project->client_name }}
            </td>
            <td class="view-details-btn" data-bs-toggle="modal"
                    data-bs-target="#exampleModal" data-route="{{route('admin.followups.show', $project->id)}}">
                {{ $project->client_phone }}
            </td>
            <td class="view-details-btn" data-bs-toggle="modal"
            data-bs-target="#exampleModal" data-route="{{route('admin.followups.show', $project->id)}}">
                <span class="">{{ date('d M, Y',strtotime($project->lastFollowUpType->created_at)) }}</span>
            </td>
            <td class="view-details-btn" data-bs-toggle="modal"
                    data-bs-target="#exampleModal" data-route="{{route('admin.followups.show', $project->id)}}">
                {{ ucfirst($project->lastFollowUpType->followup_type) }}
            </td>

            {{-- <td>
                <a title="View Project" data-route=""
                    href="{{ route('admin.projects.show', $project->id) }}"><i
                        class="fas fa-eye"></i></a> &nbsp;&nbsp;
            </td> --}}
        </tr>
    @endforeach
    <tr>
        <td colspan="6">
            <div class="d-flex justify-content-center">
                {!! $projects->links() !!}
            </div>
        </td>
    </tr>
@endif



