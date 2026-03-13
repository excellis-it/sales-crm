<table class="table table-striped custom-table mb-0">
    <thead>
        <tr>
            <th>Date</th>
            <th>Tender Name</th>
            <th>Milestone Name</th>
            <th>Amount (Lakhs)</th>
            <th>Payment Mode</th>
            <th>Status</th>
            <th>Comment</th>
            <th class="text-end">Action</th>
        </tr>
    </thead>
    <tbody>
        @if(count($payments) > 0)
            @foreach($payments as $payment)
                <tr>
                    <td>{{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('d M Y') : 'N/A' }}</td>
                    <td>
                        <strong>{{ $payment->tenderProject->tender_id_ref_no }}</strong><br>
                        <small>{{ $payment->tenderProject->tender_name }}</small>
                    </td>
                    <td>{{ $payment->milestone_name }}</td>
                    <td>{{ $payment->milestone_value ?: '0.00' }}</td>
                    <td>{{ $payment->payment_mode ?: 'N/A' }}</td>
                    <td>
                        <span class="badge {{ $payment->payment_status == 'Paid' ? 'badge-paid' : 'badge-due' }}">
                            {{ $payment->payment_status }}
                        </span>
                    </td>
                    <td>{{ $payment->milestone_comment ?: 'No comments' }}</td>
                    <td class="text-end">
                        <a href="{{ route('tender-user.payments.download-invoice', $payment->id) }}" class="btn btn-sm btn-outline-primary" title="Download Invoice">
                            <i class="fa fa-download"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="7" class="text-center">No payment records found.</td>
            </tr>
        @endif
    </tbody>
</table>

<div class="d-flex justify-content-center mt-3">
    {!! $payments->appends(request()->input())->links() !!}
</div>
