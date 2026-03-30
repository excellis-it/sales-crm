<div class="table-responsive">
    <table class="table table-hover mb-0">
        <thead>
            <tr>
                <th>#</th>
                <th>Date & Time</th>
                <th>User</th>
                <th>Source</th>
                <th>Type</th>
                <th>Project / Prospect</th>
                <th>Description</th>
                <th>Status</th>
                <th>Last Call</th>
                <th>Next Follow-up</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($activities as $index => $activity)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td style="white-space: nowrap;">
                        {{ \Carbon\Carbon::parse($activity['created_at'])->format('d M Y') }}
                        <br>
                        <small class="text-muted">{{ \Carbon\Carbon::parse($activity['created_at'])->format('h:i A') }}</small>
                    </td>
                    <td>
                        <strong>{{ $activity['user_name'] }}</strong>
                    </td>
                    <td>
                        <span class="{{ $activity['source'] === 'BDM' ? 'badge-source-bdm' : 'badge-source-tele' }}">
                            {{ $activity['source'] }}
                        </span>
                    </td>
                    <td>
                        <span class="{{ $activity['type'] === 'Project' ? 'badge-type-project' : 'badge-type-prospect' }}">
                            {{ $activity['type'] }}
                        </span>
                    </td>
                    <td>
                        <span title="{{ $activity['reference_name'] }}">
                            {{ Str::limit($activity['reference_name'], 25) }}
                        </span>
                    </td>
                    <td class="description-cell" title="{{ $activity['description'] }}">
                        {{ $activity['description'] }}
                    </td>
                    <td>
                        @if ($activity['status'])
                            @php
                                $statusClass = match($activity['status']) {
                                    'Win' => 'bg-success',
                                    'Follow Up' => 'bg-warning',
                                    'In Meeting' => 'bg-info',
                                    'Sent Proposal' => 'bg-primary',
                                    'Close', 'Lost' => 'bg-danger',
                                    default => 'bg-secondary',
                                };
                            @endphp
                            <span class="badge {{ $statusClass }}" style="font-size: 11px;">{{ $activity['status'] }}</span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        @if ($activity['last_call_status'])
                            <span style="font-size: 11px; color: #dc3545; background: #fde8ea; padding: 2px 8px; border-radius: 4px;">
                                {{ $activity['last_call_status'] }}
                            </span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td style="white-space: nowrap;">
                        @if ($activity['next_followup_date'])
                            <span style="font-size: 11px; color: #fd7e14; background: #fff4e6; padding: 2px 8px; border-radius: 4px;">
                                {{ \Carbon\Carbon::parse($activity['next_followup_date'])->format('d M Y') }}
                            </span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center text-muted py-4">
                        <i class="la la-inbox" style="font-size: 40px; display: block; margin-bottom: 10px;"></i>
                        No activities found for the selected filters.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
