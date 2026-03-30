@foreach ($project_milestones as $key => $project_milestone)
    <tr>
        <td>
            <strong>{{ $project_milestone->project->business_name ?? ($project_milestone->tenderProject->tender_name ?? ($project_milestone->bdmProject->business_name ?? 'N/A')) }}</strong>
        </td>
        <td>
            @if ($project_milestone->project_id)
                <span class="badge bg-primary">Tele Sales Project</span>
            @elseif ($project_milestone->tender_project_id)
                <span class="badge bg-warning text-dark">Tender Project</span>
            @elseif ($project_milestone->bdm_project_id)
                <span class="badge bg-success">BDM Project</span>
            @else
                <span class="badge bg-secondary">Other</span>
            @endif
        </td>
        <td>
            {{ $project_milestone->milestone_name }}
        </td>
        <td>
            <strong>
                @php
                    $currency = '$';
                    if ($project_milestone->project_id) {
                        $currency = $project_milestone->project->currency ?? '$';
                    } elseif ($project_milestone->bdm_project_id) {
                        $currency = $project_milestone->bdmProject->currency ?? '$';
                    } elseif ($project_milestone->tender_project_id) {
                        $currency = 'INR';
                    }
                @endphp
                {{ $currency }}{{ number_format($project_milestone->milestone_value, 2) }}
            </strong>
        </td>
        <td>
            <span class="text-muted"><i class="fas fa-credit-card me-1"></i> {{ $project_milestone->payment_mode }}</span>
        </td>
        <td>
            {{ $project_milestone->payment_date ? date('M d, Y', strtotime($project_milestone->payment_date)) : 'N/A' }}
        </td>
        <td class="text-center">
            <a href="{{ route('admin.payments.download-invoice', $project_milestone->id) }}"
                class="btn btn-sm btn-outline-primary" title="Download Invoice">
                <i class="fas fa-download"></i>
            </a>
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
