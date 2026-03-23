<table id="example2" class="table table-hover table-center mb-0">
    <thead>
        <tr>
            <th>Date</th>
            <th>Name</th>
            <th>Gross Target Amount</th>
            <th>Gross Achieve Amount</th>
            <th>Net Target Amount</th>
            <th>Net Achieve Amount</th>
        </tr>
    </thead>
    <tbody>
        @if (count($goals) > 0)
            @foreach ($goals as $key => $goal)
                @php
                    $startOfMonth = date('Y-m-01', strtotime($goal->goals_date));
                    $endOfMonth = date('Y-m-t', strtotime($goal->goals_date));
                    $achievements = \App\Helpers\Helper::getUserAchievementDateRange($goal->user_id, $startOfMonth, $endOfMonth);
                    
                    $grossGoal = \App\Models\Goal::where('user_id', $goal->user_id)->whereMonth('goals_date', date('m', strtotime($goal->goals_date)))->whereYear('goals_date', date('Y', strtotime($goal->goals_date)))->where('goals_type', 1)->first();
                    $netGoal = \App\Models\Goal::where('user_id', $goal->user_id)->whereMonth('goals_date', date('m', strtotime($goal->goals_date)))->whereYear('goals_date', date('Y', strtotime($goal->goals_date)))->where('goals_type', 2)->first();

                    $gross_achieved = $achievements['gross_amount'];
                    $net_achieved = $achievements['net_amount'];
                @endphp
                <tr>
                    <td>
                        {{ date('F - Y', strtotime($goal->goals_date)) }}
                    </td>
                    <td>
                        {{ $goal?->user?->name }}
                    </td>
                    
                    <!-- Gross Goal Columns -->
                    @if ($grossGoal)
                        <td>
                            ${{ number_format($grossGoal->goals_amount) }}
                        </td>
                        <td>
                            <span class="{{ $gross_achieved >= $grossGoal->goals_amount ? 'text-success fw-bold' : '' }}">${{ number_format($gross_achieved) }}</span>
                        </td>
                    @else
                        <td class="text-muted">-</td>
                        <td class="text-muted">-</td>
                    @endif

                    <!-- Net Goal Columns -->
                    @if ($netGoal)
                        <td>
                            ${{ number_format($netGoal->goals_amount) }}
                        </td>
                        <td>
                            <span class="{{ $net_achieved >= $netGoal->goals_amount ? 'text-success fw-bold' : '' }}">${{ number_format($net_achieved) }}</span>
                        </td>
                    @else
                        <td class="text-muted">-</td>
                        <td class="text-muted">-</td>
                    @endif
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="6" class="text-center">No Data Found</td>
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
