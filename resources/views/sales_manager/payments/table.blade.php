
@foreach ($project_milestones as $key => $project_milestone)
<tr>
    <td >
        {{ $project_milestone->project->business_name }}
    </td>
    <td >
        {{ $project_milestone->milestone_name }}
    </td>
    <td >
        {{ $project_milestone->milestone_value }}
    </td>
    <td >
        {{ $project_milestone->payment_mode }}
    </td>
    <td >
       {{ $project_milestone->payment_date ? date('d-m-Y', strtotime($project_milestone->payment_date)) : '' }}
    </td>
    <td >
        <a href="{{ route('sales-manager.payments.download-invoice', $project_milestone->id) }}" class="btn btn-primary btn-sm"><i class="fas fa-download"></i></a>
    </td>
</tr>
@endforeach

<tr>
<td colspan="10">
    <div class="d-flex justify-content-between align-items-center">
        <div class="">
            (Showing {{ $project_milestones->firstItem() }} – {{ $project_milestones->lastItem() }} Milestone of
            {{ $project_milestones->count() }} Project Milestones)
        </div>
        <div class="d-flex justify-content-center">
            {!! $project_milestones->links() !!}
        </div>


        {{-- page no of page --}}

    </div>

</td>


</tr>

