@if (count($prospects) == 0)
    <tr>
        <td colspan="12" class="text-center">No Prospect Found</td>
    </tr>
@else
    @foreach ($prospects as $key => $prospect)
        <tr>
            <td>
                {{ $prospect->created_at ? date('d-m-Y', strtotime($prospect->created_at)) : '' }}
            </td>
            <td>
                {{ $prospect->user->name ?? 'N/A' }}
            </td>
            <td>
                {{ $prospect->business_name }}
            </td>
            <td>
                {{ $prospect->client_name }}
            </td>
            <td>
                {{ $prospect->client_email }}
            </td>
            <td>
                {{ $prospect->client_phone }}
            </td>
            <td>
                {{ $prospect->transferTakenBy->name ?? '' }}
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
                {{ $prospect->offered_for }}
            </td>
            <td>
                {{ date('d M, Y', strtotime($prospect->followup_date)) }}
            </td>
            <td>
                {{ $prospect->price_quote }}
            </td>

            <td>

                <a title="View Prospect" class="view-details-btn"
                    data-route="{{ route('sales-manager.transfer-taken.show', $prospect->id) }}" data-bs-toggle="modal"
                    data-bs-target="#exampleModal" href="javascript:void(0);"><i class="fas fa-eye"></i></a> &nbsp;&nbsp;
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
