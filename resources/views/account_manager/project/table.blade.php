@if (count($projects) == 0)
    <tr>
        <td colspan="12" class="text-center">No Project found</td>
    </tr>
@else
    @foreach ($projects as $key => $project)
        @php
            $upsaleTotal    = $project->upsales->sum('upsale_value');
            $upsaleUpfront  = $project->upsales->sum('upsale_upfront');
            $grandTotal     = $project->project_value + $upsaleTotal;
            $totalUpfront   = $project->project_upfront + $upsaleUpfront;

            // Paid milestones: exclude upfront records (by type or name) to avoid double counting
            $paidMilestones = $project->allProjectMilestones
                ->where('payment_status', 'Paid')->whereIn('milestone_type', ['milestone', 'upsale_milestone'])
                ->sum('milestone_value');

            $dueAmount   = $grandTotal - ($totalUpfront + $paidMilestones);
            //  dd($grandTotal, $totalUpfront, $paidMilestones, $dueAmount);
            $hasUpsales  = $project->upsales->count() > 0;
        @endphp
        <tr>
            <td class="edit-route" data-route="{{ route('account-manager.projects.edit', $project->id) }}">
                {{ $project->sale_date ? date('d-m-Y', strtotime($project->sale_date)) : '' }}
            </td>
            <td class="edit-route" data-route="{{ route('account-manager.projects.edit', $project->id) }}">
                <span title="Uploaded By: {{ $project->projectOpener->name ?? 'N/A' }}">
                    {{ $project->projectOpener->name ?? 'N/A' }}
                </span>
            </td>
            <td class="edit-route" data-route="{{ route('account-manager.projects.edit', $project->id) }}">
                {{ $project->business_name }}
            </td>
            <td class="edit-route" data-route="{{ route('account-manager.projects.edit', $project->id) }}">
                @foreach ($project->projectTypes as $index => $projectType)
                    <span class="">{{ Str::limit($projectType->type, 20) }}</span>
                    @if (!$loop->last)
                        <span>,</span>
                    @endif
                @endforeach
            </td>
            {{-- Value (Base+Upsale) --}}
            <td class="edit-route" data-route="{{ route('account-manager.projects.edit', $project->id) }}">
                <div title="Base: {{ $project->project_value }} &#010;Upsale: {{ $upsaleTotal }}">
                    <strong>{{ number_format($grandTotal, 2) }}</strong>
                    @if($hasUpsales)
                        <span style="background:#6f42c1;color:#fff;font-size:10px;border-radius:4px;padding:1px 4px; display:inline-block; margin-top:2px;">
                            Upsale +{{ $project->upsales->count() }}
                        </span>
                    @endif
                </div>
            </td>
            {{-- Total Upfront = base upfront + upsale upfront --}}
            <td class="edit-route" data-route="{{ route('account-manager.projects.edit', $project->id) }}">
                <span title="Base Upfront: {{ $project->project_upfront }} &#010;Upsale Upfront: {{ $upsaleUpfront }}">
                    {{ number_format($totalUpfront, 2) }}
                </span>
            </td>
            <td class="edit-route" data-route="{{ route('account-manager.projects.edit', $project->id) }}">
                {{ $project->currency }}
            </td>
            <td class="edit-route" data-route="{{ route('account-manager.projects.edit', $project->id) }}">
                {{ $project->payment_mode }}
            </td>
            {{-- Milestone Received (only milestones, not upfronts) --}}
            <td class="edit-route" data-route="{{ route('account-manager.projects.edit', $project->id) }}">
                <span title="Total milestones collected excluding upfronts">
                    {{ $paidMilestones > 0 ? number_format($paidMilestones, 2) : 0 }}
                </span>
            </td>
            {{-- Balance Due = Total Value - Total Upfront - Milestone Received --}}
            <td class="edit-route" data-route="{{ route('account-manager.projects.edit', $project->id) }}">
                <div title="Formula: ({{ number_format($grandTotal, 2) }}) - ({{ number_format($totalUpfront, 2) }}) - ({{ number_format($paidMilestones, 2) }})">
                    <span class="{{ $dueAmount > 0 ? 'text-danger fw-bold' : 'text-success fw-bold' }}">
                        {{ number_format($dueAmount, 2) }}
                    </span>
                </div>
            </td>
            <td>
                <a title="Upsale" class="btn-open-upsale" data-project-id="{{ $project->id }}"
                    href="javascript:void(0);">
                    <span style="background:#6f42c1;color:#fff;font-size:11px;padding:3px 6px;border-radius:4px;cursor:pointer;">
                        <i class="la la-money-bill"></i> Upsale @if($hasUpsales)({{ $project->upsales->count() }})@endif
                    </span>
                </a>
                &nbsp;
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
            </div>
        </td>
    </tr>
@endif
