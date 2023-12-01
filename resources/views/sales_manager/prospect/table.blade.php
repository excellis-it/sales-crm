

    @if (count($prospects) == 0)
    <tr>
        <td colspan="12" class="text-center">No Prospect Found</td>
    </tr>
    @else
        @foreach ($prospects as $key => $prospect)
            <tr>
                <td   @if ($prospect->status != 'Win') class="edit-route" data-route="{{ route('sales-manager.prospects.edit', $prospect->id) }}" @endif>
                    {{ ($prospect->sale_date) ?  $prospect->sale_date: '' }}
                </td>
                <td   @if ($prospect->status != 'Win') class="edit-route" data-route="{{ route('sales-manager.prospects.edit', $prospect->id) }}" @endif>
                    {{ $prospect->business_name }}
                </td>
                <td   @if ($prospect->status != 'Win') class="edit-route" data-route="{{ route('sales-manager.prospects.edit', $prospect->id) }}" @endif>
                    {{ $prospect->client_name }}
                </td>
                <td   @if ($prospect->status != 'Win') class="edit-route" data-route="{{ route('sales-manager.prospects.edit', $prospect->id) }}" @endif>
                    {{ $prospect->client_email }}
                </td>
                <td   @if ($prospect->status != 'Win') class="edit-route" data-route="{{ route('sales-manager.prospects.edit', $prospect->id) }}" @endif>
                    {{ $prospect->client_phone }}
                </td>
                <td   @if ($prospect->status != 'Win') class="edit-route" data-route="{{ route('sales-manager.prospects.edit', $prospect->id) }}" @endif>
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
                        data-route="{{ route('sales-manager.prospects.show', $prospect->id) }}"
                        data-bs-toggle="modal" data-bs-target="#exampleModal"
                        href="javascript:void(0);"><i class="fas fa-eye"></i></a> &nbsp;&nbsp;
                </td>
            </tr>
        @endforeach
    @endif



<tr>
    <td colspan="12">
        <div class="d-flex justify-content-center">
            {!! $prospects->links() !!}
        </div>
    </td>
</tr>
