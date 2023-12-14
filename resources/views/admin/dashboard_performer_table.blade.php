@if(count($top_performers) == 0)
<tr>
    <td colspan="10" class="text-center">No Performers Found</td>
</tr>
@else
@foreach($top_performers as $key => $performer)
    <tr>
        <td>{{ $performer->user->name }}</td>
        @foreach($performer->user->roles as $role)
            @if($role->name =='BUSINESS_DEVELOPMENT_MANAGER')
                <td>Business Development Manager</td>
            @elseif($role->name =='ACCOUNT_MANAGER')
                <td>Account manager</td>
            @elseif($role->name =='SALES_MANAGER')
                <td>Sales Manager</td>
            @elseif($role->name =='SALES_EXCUETIVE')
                <td>Sales Executive</td>
            @elseif($role->name =='BUSINESS_DEVELOPMENT_EXCECUTIVE')
                <td>Business Development Executive</td>
            @else
                <td>Not Found</td>
            @endif

        @endforeach
        <td>
            <div class="project_value">
                <h5 class="shop-sell">${{ $performer->goals_achieve }}</h5>
            </div>
        </td>
    </tr>
@endforeach

@endif

