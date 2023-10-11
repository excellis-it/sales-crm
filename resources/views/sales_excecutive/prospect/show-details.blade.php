@if (isset($isThat))
<p>
    <span>Date:-</span>
    {{ date('d M, Y', strtotime($prospect->created_at)) }}
</p>
<p>
    <span>Business Name:-</span>
    {{ $prospect->business_name }}
</p>

<p>
    <span>Client Name:-</span>
    {{ $prospect->client_name }}
</p>

<p>
    <span>Email:-</span>
    {{ $prospect->client_email }}
</p>

<p>
    <span>Phone:-</span>
    {{ $prospect->client_phone }}
</p>

<p>
    <span>Transfer Taken By:-</span>
    {{ $prospect->transfer_token_by }}

</p>

<p>
    <span>Status:-</span>
    {{ $prospect->status }}
</p>

<p>
    <span>Service Offered:-</span>
    {{ $prospect->offered_for }}

</p>

<p>
    <span>Followup Date:-</span>
    {{ date('d M, Y', strtotime($prospect->followup_date)) }}
</p>

<p>
    <span>Price Quoted:-</span>
    {{ $prospect->price_quote }}
</p>
<p>
    <span>Comments:-</span>
    {{ $prospect->comments }}
</p>
@endif
