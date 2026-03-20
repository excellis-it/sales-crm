<table id="example2" class="table table-hover table-center mb-0">
    <thead>
        <tr>
            <th>Date</th>
            <th>Type</th>
            <th>Name</th>
            <th>Target Amount</th>
            <th>Achieve Amount</th>
        </tr>
    </thead>
    <tbody>
        @if (count($goals) > 0)
            @foreach ($goals as $key => $goal)
                @php
                    $startOfMonth = date('Y-m-01', strtotime($goal->goals_date));
                    $endOfMonth = date('Y-m-t', strtotime($goal->goals_date));
                    $achievements = \App\Helpers\Helper::getUserAchievementDateRange($goal->user_id, $startOfMonth, $endOfMonth);
                    $dynamic_achieve = $goal->goals_type == 1 ? $achievements['gross_amount'] : $achievements['net_amount'];
                @endphp
                <tr>
                    <td>
                        {{ date('F - Y', strtotime($goal->goals_date)) }}
                    </td>
                    <td>
                        @if ($goal->goals_type == 1)
                            <span class="badge bg-inverse-success">Gross</span>
                        @else
                            <span class="badge bg-inverse-warning">Net</span>
                        @endif
                    </td>
                    <td>
                        {{ $goal?->user?->name }}
                    </td>
                    <td>
                        ${{ number_format($goal->goals_amount) }}
                    </td>
                    <td>
                        ${{ number_format($dynamic_achieve) }}
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="5" class="text-center">No Data Found</td>
            </tr>
        @endif
    </tbody>
</table>

@if ($goals->total() > 15)
    <div class="row align-items-center mb-3">
        <div class="col-sm-12 col-md-5">
            <div class="dataTables_info" id="example2_info" role="status" aria-live="polite">
                Showing {{ $goals->firstItem() }} to {{ $goals->lastItem() }} of
                {{ $goals->total() }} entries
            </div>
        </div>
        <div class="col-sm-12 col-md-7">
            {{ $goals->links('pagination::bootstrap-4') }}
        </div>
    </div>
@endif
