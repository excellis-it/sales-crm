@if (count($prospects) == 0)
    <tr>
        <td colspan="13" class="text-center">No Prospect Found</td>
    </tr>
@else
    @foreach ($prospects as $prospect)
        <tr>
            <td @if ($prospect->status != 'Win') class="edit-route" data-route="{{ route('admin.prospects.edit', $prospect->id) }}" @endif>
                {{ $prospect->created_at ? date('d-m-Y', strtotime($prospect->created_at)) : '' }}
            </td>
            <td @if ($prospect->status != 'Win') class="edit-route" data-route="{{ route('admin.prospects.edit', $prospect->id) }}" @endif>
                {{ $prospect->user->name ?? '' }}
            </td>
            <td @if ($prospect->status != 'Win') class="edit-route" data-route="{{ route('admin.prospects.edit', $prospect->id) }}" @endif>
                {{ $prospect->client_name ?? '' }}
            </td>
            <td @if ($prospect->status != 'Win') class="edit-route" data-route="{{ route('admin.prospects.edit', $prospect->id) }}" @endif>
                {{ $prospect->business_name ?? '' }}
            </td>
            <td @if ($prospect->status != 'Win') class="edit-route" data-route="{{ route('admin.prospects.edit', $prospect->id) }}" @endif>
                {{ $prospect->client_email ?? '' }}
            </td>
            <td @if ($prospect->status != 'Win') class="edit-route" data-route="{{ route('admin.prospects.edit', $prospect->id) }}" @endif>
                {{ $prospect->client_phone ?? '' }}
            </td>
            <td>
                {{ $prospect->transferTakenBy->name ?? '' }}
            </td>
            <td>
                {{ $prospect->reportTo->name ?? '-' }}
            </td>
            <td @if ($prospect->status != 'Win') class="edit-route" data-route="{{ route('admin.prospects.edit', $prospect->id) }}" @endif>
                {{ $prospect->status ?? '' }}
            </td>
            <td>
                @if ($prospect->status == 'Win')
                <span>On Board</span>
                @elseif ($prospect->status == 'Follow Up')
                <span>Follow Up</span>
                @elseif ($prospect->status == 'Sent Proposal')
                <span>Sent Proposal</span>
                @elseif ($prospect->status == 'Close')
                <span>Cancel</span>
            @endif
            </td>
            <td>
                {{ $prospect->followup_date ? date('d-m-Y', strtotime($prospect->followup_date)) : '' }}
            </td>
            <td>
                {{ $prospect->price_quote ?? '' }}
            </td>
            <td>
                <a title="View Prospect" class="view-details-btn"
                    data-route="{{ route('admin.prospects.show', $prospect->id) }}" data-bs-toggle="modal"
                    data-bs-target="#exampleModal" href="javascript:void(0);"><i class="fas fa-eye text-info"></i></a>
                &nbsp;&nbsp;
                <a title="View Follow-ups" class="view-followups" data-id="{{ $prospect->id }}"
                    href="javascript:void(0);"><i class="fas fa-comments text-primary"></i></a>
            </td>
        </tr>
    @endforeach

@endif

<tr>
    <td colspan="13">
        <div class="d-flex justify-content-center">
            {!! $prospects->links() !!}
        </div>
    </td>
</tr>

