@if (count($projects) == 0)
    <tr>
        <td colspan="14" class="text-center">No Projects Found</td>
    </tr>
@else
    @foreach ($projects as $key => $project)
        @php
            $upsaleTotal    = $project->upsales->sum('upsale_value');
            $upsaleUpfront  = $project->upsales->sum('upsale_upfront');
            $grandTotal     = (float)$project->project_value + $upsaleTotal;
            $totalUpfront   = (float)$project->project_upfront + $upsaleUpfront;

            // Paid milestones: exclude upfront records to avoid double counting
            $paidMilestones = $project->allProjectMilestones
                ->where('payment_status', 'Paid')->whereIn('milestone_type', ['milestone', 'upsale_milestone'])
                ->sum('milestone_value');

            $dueAmount   = $grandTotal - ($totalUpfront + $paidMilestones);
            $hasUpsales  = $project->upsales->count() > 0;
        @endphp
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
            </td>
            {{-- Value (Base+Upsale) --}}
            <td class="edit-route" data-route="{{ route('sales-projects.edit', $project->id) }}">
                <div title="Base: {{ $project->project_value }} &#010;Upsale: {{ $upsaleTotal }}">
                    <strong>{{ number_format($grandTotal, 2) }}</strong>
                    @if($hasUpsales)
                        <span style="background:#6f42c1;color:#fff;font-size:10px;border-radius:4px;padding:1px 4px; display:inline-block; margin-top:2px;">
                            Upsale +{{ $project->upsales->count() }}
                        </span>
                    @endif
                </div>
            </td>
            {{-- Total Upfront --}}
            <td class="edit-route" data-route="{{ route('sales-projects.edit', $project->id) }}">
                <span title="Base Upfront: {{ $project->project_upfront }} &#010;Upsale Upfront: {{ $upsaleUpfront }}">
                    {{ number_format($totalUpfront, 2) }}
                </span>
            </td>
            <td class="edit-route" data-route="{{ route('sales-projects.edit', $project->id) }}">
                {{ $project->currency ?? '' }}
            </td>
            <td class="edit-route" data-route="{{ route('sales-projects.edit', $project->id) }}">
                {{ $project->payment_mode ?? '' }}
            </td>
            {{-- Milestone Received --}}
            <td class="edit-route" data-route="{{ route('sales-projects.edit', $project->id) }}">
                <span title="Total milestones collected excluding upfronts">
                    {{ $paidMilestones > 0 ? number_format($paidMilestones, 2) : 0 }}
                </span>
            </td>
            {{-- Balance Due --}}
            <td class="edit-route" data-route="{{ route('sales-projects.edit', $project->id) }}">
                 <div title="Formula: ({{ number_format($grandTotal, 2) }}) - ({{ number_format($totalUpfront, 2) }}) - ({{ number_format($paidMilestones, 2) }})">
                    <span class="{{ $dueAmount > 0 ? 'text-danger fw-bold' : 'text-success fw-bold' }}">
                        {{ number_format($dueAmount, 2) }}
                    </span>
                </div>
            </td>
            <td>
                @if ($project->assigned_to == null)
                    <span class="badge bg-danger">Not Assigned</span>
                @else
                    <span class="badge bg-success">Assigned</span>
                @endif
            </td>
            <td>
                <a title="Upsale" class="btn-open-upsale" data-project-id="{{ $project->id }}"
                    href="javascript:void(0);">
                    <span style="background:#6f42c1;color:#fff;font-size:11px;padding:3px 6px;border-radius:4px;cursor:pointer;">
                        <i class="la la-money-bill"></i> Upsale @if($hasUpsales)({{ $project->upsales->count() }})@endif
                    </span>
                </a>
                <br><br>
                <a title="Delete Project" data-route="{{ route('sales-projects.delete', $project->id) }}"
                    href="javascript:void(0);" class="delete"><i class="fas fa-trash text-danger"></i></a>
                &nbsp;&nbsp;
                <a title="View Follow-ups" class="view-followups" data-id="{{ $project->id }}"
                    href="javascript:void(0);"><i class="fas fa-comments text-primary"></i></a>
            </td>
        </tr>
    @endforeach
@endif

<tr>
    <td colspan="14">
        <div class="d-flex justify-content-between align-items-center">
            <div class="">
                (Showing {{ $projects->firstItem() }} – {{ $projects->lastItem() }} Projects of
                {{ $projects->total() }} Projects)
            </div>
            <div class="d-flex justify-content-center">
                {!! $projects->links() !!}
            </div>
        </div>
    </td>
</tr>
