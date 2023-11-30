@if (isset($isThat))
 @if (count($followups) > 0)
    @foreach ($followups as $item)
    <div class="testimonial-box">
        <div class="box-top">
            <div class="profile">
                <div class="name-user">
                    <strong class="date">Followup on {{ date('d M, Y',strtotime($item['created_at'])) }}</strong>
                    <br>
                    <p>Followup type was {{ ($item['followup_type']) }}</p>
                </div>
            </div>
        </div>
        <div class="client-comment">
            <p>{{$item['followup_description']}}
            </p>
        </div>
    </div>
    @endforeach
 @endif
@endif
