<table id="myTable" class="dd table table-striped table-hover" style="width:100%">
    <thead>
        <tr>
            <th> Goals Date</th>
            <th> Role</th>
            <th> Goal Assign For</th>
            <th> Gross Target</th>
            <th> Gross Achieve</th>
            <th style="width: 140px;"> Gross Progress</th>
            <th> Net Target</th>
            <th> Net Achieve</th>
            <th style="width: 140px;"> Net Progress</th>
            <th> Action</th>
        </tr>
    </thead>
    <tbody>
        @if (count($goals) == 0)
            <tr>
                <td colspan="10" class="text-center">No Goals found</td>
            </tr>
        @else
            @foreach ($goals as $key => $goal)
                @php
                    $startOfMonth = date('Y-m-01', strtotime($goal->goals_date));
                    $endOfMonth = date('Y-m-t', strtotime($goal->goals_date));
                    $achievements = \App\Helpers\Helper::getUserAchievementDateRange($goal->user_id, $startOfMonth, $endOfMonth);

                    $grossGoal = \App\Models\Goal::where('user_id', $goal->user_id)->whereMonth('goals_date', date('m', strtotime($goal->goals_date)))->whereYear('goals_date', date('Y', strtotime($goal->goals_date)))->where('goals_type', 1)->first();
                    $netGoal = \App\Models\Goal::where('user_id', $goal->user_id)->whereMonth('goals_date', date('m', strtotime($goal->goals_date)))->whereYear('goals_date', date('Y', strtotime($goal->goals_date)))->where('goals_type', 2)->first();

                    // Gross logic
                    $gross_achieved = $achievements['gross_amount'];
                    $gross_percentage = $grossGoal && $grossGoal->goals_amount > 0 ? round(($gross_achieved / $grossGoal->goals_amount) * 100, 1) : 0;
                    $gross_percentage = min($gross_percentage, 100);
                    if ($gross_percentage >= 80) {
                        $grossProgressColor = '#28a745';
                    } elseif ($gross_percentage >= 50) {
                        $grossProgressColor = '#f37e20';
                    } else {
                        $grossProgressColor = '#dc3545';
                    }

                    // Net logic
                    $net_achieved = $achievements['net_amount'];
                    $net_percentage = $netGoal && $netGoal->goals_amount > 0 ? round(($net_achieved / $netGoal->goals_amount) * 100, 1) : 0;
                    $net_percentage = min($net_percentage, 100);
                    if ($net_percentage >= 80) {
                        $netProgressColor = '#28a745';
                    } elseif ($net_percentage >= 50) {
                        $netProgressColor = '#f37e20';
                    } else {
                        $netProgressColor = '#dc3545';
                    }

                    // Determine role
                    $roleName = '';
                    $roleBadge = '';
                    if ($goal->user->hasRole('SALES_MANAGER')) {
                        $roleName = 'Sales Manager';
                        $roleBadge = 'background: linear-gradient(135deg, #f37e20, #e06c10); color: #fff;';
                    } elseif ($goal->user->hasRole('ACCOUNT_MANAGER')) {
                        $roleName = 'Account Manager';
                        $roleBadge = 'background: linear-gradient(135deg, #17a2b8, #138496); color: #fff;';
                    } elseif ($goal->user->hasRole('SALES_EXCUETIVE')) {
                        $roleName = 'Sales Executive';
                        $roleBadge = 'background: linear-gradient(135deg, #6f42c1, #5a32a3); color: #fff;';
                    } elseif ($goal->user->hasRole('BUSINESS_DEVELOPMENT_MANAGER')) {
                        $roleName = 'BDM';
                        $roleBadge = 'background: linear-gradient(135deg, #ad1e23, #8c181c); color: #fff;';
                    } else {
                        $roleName = 'BDE';
                        $roleBadge = 'background: linear-gradient(135deg, #20c997, #17a689); color: #fff;';
                    }
                @endphp
                <tr>
                    <td>
                        <span style="font-weight: 500;">{{ date('F Y', strtotime($goal->goals_date)) }}</span>
                    </td>
                    <td>
                        <span style="{{ $roleBadge }} padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; white-space: nowrap;">{{ $roleName }}</span>
                    </td>
                    <td>
                        <span style="font-weight: 500;">{{ $goal->user->name }}</span>
                    </td>

                    <!-- Gross Goal Columns -->
                    @if ($grossGoal)
                        <td>
                            <span style="font-weight: 600; color: #334257;">${{ number_format($grossGoal->goals_amount, 2) }}</span>
                        </td>
                        <td>
                            <span style="font-weight: 600; color: {{ $gross_percentage >= 80 ? '#28a745' : ($gross_percentage >= 50 ? '#f37e20' : '#dc3545') }};">${{ number_format($gross_achieved, 2) }}</span>
                        </td>
                        <td style="width: 100px;">
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <div style="flex: 1; background: #e9ecef; border-radius: 10px; height: 8px; overflow: hidden;">
                                    <div style="width: {{ $gross_percentage }}%; background: {{ $grossProgressColor }}; height: 100%; border-radius: 10px; transition: width 0.3s ease;"></div>
                                </div>
                                <span style="font-size: 11px; font-weight: 600; color: {{ $grossProgressColor }}; min-width: 38px;">{{ $gross_percentage }}%</span>
                            </div>
                        </td>
                    @else
                        <td class="text-muted">-</td>
                        <td class="text-muted">-</td>
                        <td class="text-muted">-</td>
                    @endif

                    <!-- Net Goal Columns -->
                    @if ($netGoal)
                        <td>
                            <span style="font-weight: 600; color: #334257;">${{ number_format($netGoal->goals_amount, 2) }}</span>
                        </td>
                        <td>
                            <span style="font-weight: 600; color: {{ $net_percentage >= 80 ? '#28a745' : ($net_percentage >= 50 ? '#f37e20' : '#dc3545') }};">${{ number_format($net_achieved, 2) }}</span>
                        </td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <div style="flex: 1; background: #e9ecef; border-radius: 10px; height: 8px; overflow: hidden;">
                                    <div style="width: {{ $net_percentage }}%; background: {{ $netProgressColor }}; height: 100%; border-radius: 10px; transition: width 0.3s ease;"></div>
                                </div>
                                <span style="font-size: 11px; font-weight: 600; color: {{ $netProgressColor }}; min-width: 38px;">{{ $net_percentage }}%</span>
                            </div>
                        </td>
                    @else
                        <td class="text-muted">-</td>
                        <td class="text-muted">-</td>
                        <td class="text-muted">-</td>
                    @endif

                    <td>
                        @php
                            $editGoal = $grossGoal ?? $netGoal;
                            $userRole = 'BUSINESS_DEVELOPMENT_EXCECUTIVE';
                            if($goal->user->hasRole('SALES_MANAGER')) $userRole = 'SALES_MANAGER';
                            elseif($goal->user->hasRole('BUSINESS_DEVELOPMENT_MANAGER')) $userRole = 'BUSINESS_DEVELOPMENT_MANAGER';
                            elseif($goal->user->hasRole('ACCOUNT_MANAGER')) $userRole = 'ACCOUNT_MANAGER';
                        @endphp
                        @if($editGoal)
                            <a title="Delete Goal" data-route="{{ route('goals.delete', $editGoal->id) }}"
                                href="javascript:void(0);" id="delete" style="color: #dc3545;"><i class="fas fa-trash"></i></a>&nbsp;
                            @if(!$goal->user->hasRole('SALES_EXCUETIVE'))
                                <a href="javascript:void(0);" data-route="{{ route('goals.edit', $editGoal->id) }}"
                                    data-role="{{ $userRole }}" data-toggle="modal" class="edit-data" style="color: #f37e20;"> <i class="fas fa-edit"></i></a>
                            @endif
                        @endif
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>

<div class="d-flex justify-content-center">
    {!! $goals->links() !!}
</div>
