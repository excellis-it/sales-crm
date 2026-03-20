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
                @if ($prospect->status == 'Win')
                    On Board
                @elseif($prospect->status == 'Follow Up')
                    Follow Up
                @elseif($prospect->status == 'Sent Proposal')
                    Sent Proposal
                @elseif($prospect->status == 'Close')
                    Cancel
                @else
                    {{ $prospect->status ?? '' }}
                @endif
            </td>
            <td>
                {{ $prospect->offered_for ?? '' }}
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
                &nbsp;&nbsp;
                <a title="Delete Prospect" data-route="{{ route('admin.prospects.delete', $prospect->id) }}"
                    href="javascript:void(0);" class="delete"><i class="fas fa-trash text-danger"></i></a>
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

