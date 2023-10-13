@if (isset($isThat))
<p>
    <span>Sales Excecutive:-</span>
    {{ $prospect->user->name }}
</p>
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
    <span>Website:-</span>
    {{ $prospect->website }}
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
    <span>Followup Time</span>
    {{ $prospect->followup_time }}
</p>
<p>
    <span>Sale Date:-</span>
    {{($prospect->sale_date) ? date('d M, Y', strtotime($prospect->sale_date)) : '' }}
</p>
<p>
    <span>Price Quoted:-</span>
    {{ $prospect->price_quote }}
</p>
<p>
    <span>Comments:-</span>
    {{ $prospect->comments }}
</p>
<p>
    <span>Upfront Value:-</span>
    {{ ($prospect->upfront_value) ? '$'.$prospect->upfront_value : 'N/A' }}
</p>
@endif
