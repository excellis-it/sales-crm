<table id="myTable" class="dd table table-striped table-hover" style="width:100%">
    <thead>
        <tr>
            <th>Goals Date</th>
            <th>Role</th>
            <th>Goal Assign For</th>
            <th>Gross Target</th>
            <th>Gross Achieve</th>
            <th style="width:130px;">Gross Progress</th>
            <th>Net Target</th>
            <th>Net Achieve</th>
            <th style="width:130px;">Net Progress</th>
            <th>Meetings Target</th>
            <th>Meetings Achieve</th>
            <th style="width:130px;">Meetings Progress</th>
            <th>On Board Target</th>
            <th>On Board Achieve</th>
            <th style="width:130px;">On Board Progress</th>
            <th>Action</th>
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
                    $gMonth = date('m', strtotime($goal->goals_date));
                    $gYear  = date('Y', strtotime($goal->goals_date));
                    $startOfMonth = date('Y-m-01', strtotime($goal->goals_date));
                    $endOfMonth   = date('Y-m-t',  strtotime($goal->goals_date));
                    $achievements = \App\Helpers\Helper::getUserAchievementDateRange($goal->user_id, $startOfMonth, $endOfMonth);

                    $grossGoal    = \App\Models\Goal::where('user_id', $goal->user_id)->whereMonth('goals_date', $gMonth)->whereYear('goals_date', $gYear)->where('goals_type', 1)->first();
                    $netGoal      = \App\Models\Goal::where('user_id', $goal->user_id)->whereMonth('goals_date', $gMonth)->whereYear('goals_date', $gYear)->where('goals_type', 2)->first();
                    $meetingsGoal = \App\Models\Goal::where('user_id', $goal->user_id)->whereMonth('goals_date', $gMonth)->whereYear('goals_date', $gYear)->where('goals_type', 3)->first();
                    $onboardGoal  = \App\Models\Goal::where('user_id', $goal->user_id)->whereMonth('goals_date', $gMonth)->whereYear('goals_date', $gYear)->where('goals_type', 4)->first();

                    // Quarterly date label
                    $quarterRecord = \App\Models\Goal::where('user_id', $goal->user_id)->whereMonth('goals_date', $gMonth)->whereYear('goals_date', $gYear)->whereNotNull('quarter')->first();
                    $dateLabel = $quarterRecord
                        ? 'Q' . $quarterRecord->quarter . ' ' . $gYear
                        : date('F Y', strtotime($goal->goals_date));

                    // Gross
                    $gross_achieved   = $achievements['gross_amount'];
                    $gross_percentage = $grossGoal && $grossGoal->goals_amount > 0 ? min(round(($gross_achieved / $grossGoal->goals_amount) * 100, 1), 100) : 0;
                    $grossProgressColor = $gross_percentage >= 80 ? '#28a745' : ($gross_percentage >= 50 ? '#f37e20' : '#dc3545');

                    // Net
                    $net_achieved   = $achievements['net_amount'];
                    $net_percentage = $netGoal && $netGoal->goals_amount > 0 ? min(round(($net_achieved / $netGoal->goals_amount) * 100, 1), 100) : 0;
                    $netProgressColor = $net_percentage >= 80 ? '#28a745' : ($net_percentage >= 50 ? '#f37e20' : '#dc3545');

                    // Meetings & OnBoard — live counts via Helper
                    $moAchieve = \App\Helpers\Helper::getUserMeetingsAndOnboardAchievement($goal->user_id, $startOfMonth, $endOfMonth);
                    $meetings_achieved   = $moAchieve['meetings'];
                    $onboard_achieved    = $moAchieve['onboard'];

                    $meetings_percentage = $meetingsGoal && $meetingsGoal->goals_amount > 0 ? min(round(($meetings_achieved / $meetingsGoal->goals_amount) * 100, 1), 100) : 0;
                    $meetingsProgressColor = $meetings_percentage >= 80 ? '#28a745' : ($meetings_percentage >= 50 ? '#f37e20' : '#dc3545');

                    $onboard_percentage = $onboardGoal && $onboardGoal->goals_amount > 0 ? min(round(($onboard_achieved / $onboardGoal->goals_amount) * 100, 1), 100) : 0;
                    $onboardProgressColor = $onboard_percentage >= 80 ? '#28a745' : ($onboard_percentage >= 50 ? '#f37e20' : '#dc3545');

                    // Role and Currency
                    $roleName = ''; $roleBadge = '';
                    $currencySymbol = '$';
                    if ($goal->user->hasRole('SALES_MANAGER')) {
                        $roleName = 'Sales Manager';
                        $roleBadge = 'background:linear-gradient(135deg,#f37e20,#e06c10);color:#fff;';
                    } elseif ($goal->user->hasRole('ACCOUNT_MANAGER')) {
                        $roleName = 'Account Manager';
                        $roleBadge = 'background:linear-gradient(135deg,#17a2b8,#138496);color:#fff;';
                    } elseif ($goal->user->hasRole('SALES_EXCUETIVE')) {
                        $roleName = 'Sales Executive';
                        $roleBadge = 'background:linear-gradient(135deg,#6f42c1,#5a32a3);color:#fff;';
                    } elseif ($goal->user->hasRole('BUSINESS_DEVELOPMENT_MANAGER')) {
                        $roleName = 'BDM';
                        $roleBadge = 'background:linear-gradient(135deg,#ad1e23,#8c181c);color:#fff;';
                    } elseif ($goal->user->hasRole('BUSINESS_DEVELOPMENT_EXCECUTIVE')) {
                        $roleName = 'BDE';
                        $roleBadge = 'background:linear-gradient(135deg,#20c997,#17a689);color:#fff;';
                    } elseif ($goal->user->hasRole('TENDER_USER')) {
                        $roleName = 'Tender Manager';
                        $roleBadge = 'background:linear-gradient(135deg,#6610f2,#520dc2);color:#fff;';
                        $currencySymbol = '₹';
                    } else {
                        $roleName = 'Other';
                        $roleBadge = 'background:#6c757d;color:#fff;';
                    }
                @endphp
                <tr>
                    <td>
                        <span style="font-weight:500;">{{ $dateLabel }}</span>
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
                            <span style="font-weight: 600; color: #334257;">{{ $currencySymbol }}{{ number_format($grossGoal->goals_amount, 2) }}</span>
                        </td>
                        <td>
                            <span style="font-weight: 600; color: {{ $gross_percentage >= 80 ? '#28a745' : ($gross_percentage >= 50 ? '#f37e20' : '#dc3545') }};">{{ $currencySymbol }}{{ number_format($gross_achieved, 2) }}</span>
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
                        <td><span style="font-weight:600;color:#334257;">{{ $currencySymbol }}{{ number_format($netGoal->goals_amount, 2) }}</span></td>
                        <td><span style="font-weight:600;color:{{ $net_percentage >= 80 ? '#28a745' : ($net_percentage >= 50 ? '#f37e20' : '#dc3545') }};">{{ $currencySymbol }}{{ number_format($net_achieved, 2) }}</span></td>
                        <td>
                            <div style="display:flex;align-items:center;gap:8px;">
                                <div style="flex:1;background:#e9ecef;border-radius:10px;height:8px;overflow:hidden;">
                                    <div style="width:{{ $net_percentage }}%;background:{{ $netProgressColor }};height:100%;border-radius:10px;transition:width 0.3s ease;"></div>
                                </div>
                                <span style="font-size:11px;font-weight:600;color:{{ $netProgressColor }};min-width:38px;">{{ $net_percentage }}%</span>
                            </div>
                        </td>
                    @else
                        <td class="text-muted">-</td><td class="text-muted">-</td><td class="text-muted">-</td>
                    @endif

                    <!-- Meetings Goal Columns -->
                    @if ($meetingsGoal)
                        <td><span style="font-weight:600;color:#334257;">{{ number_format($meetingsGoal->goals_amount) }}</span></td>
                        <td><span style="font-weight:600;color:{{ $meetings_percentage >= 80 ? '#28a745' : ($meetings_percentage >= 50 ? '#f37e20' : '#dc3545') }};">{{ $meetings_achieved }}</span></td>
                        <td>
                            <div style="display:flex;align-items:center;gap:8px;">
                                <div style="flex:1;background:#e9ecef;border-radius:10px;height:8px;overflow:hidden;">
                                    <div style="width:{{ $meetings_percentage }}%;background:{{ $meetingsProgressColor }};height:100%;border-radius:10px;"></div>
                                </div>
                                <span style="font-size:11px;font-weight:600;color:{{ $meetingsProgressColor }};min-width:38px;">{{ $meetings_percentage }}%</span>
                            </div>
                        </td>
                    @else
                        <td class="text-muted">-</td><td class="text-muted">-</td><td class="text-muted">-</td>
                    @endif

                    <!-- OnBoard Goal Columns -->
                    @if ($onboardGoal)
                        <td><span style="font-weight:600;color:#334257;">{{ number_format($onboardGoal->goals_amount) }}</span></td>
                        <td><span style="font-weight:600;color:{{ $onboard_percentage >= 80 ? '#28a745' : ($onboard_percentage >= 50 ? '#f37e20' : '#dc3545') }};">{{ $onboard_achieved }}</span></td>
                        <td>
                            <div style="display:flex;align-items:center;gap:8px;">
                                <div style="flex:1;background:#e9ecef;border-radius:10px;height:8px;overflow:hidden;">
                                    <div style="width:{{ $onboard_percentage }}%;background:{{ $onboardProgressColor }};height:100%;border-radius:10px;"></div>
                                </div>
                                <span style="font-size:11px;font-weight:600;color:{{ $onboardProgressColor }};min-width:38px;">{{ $onboard_percentage }}%</span>
                            </div>
                        </td>
                    @else
                        <td class="text-muted">-</td><td class="text-muted">-</td><td class="text-muted">-</td>
                    @endif

                    <td>
                        @php
                            $editGoal = $grossGoal ?? $meetingsGoal ?? $onboardGoal ?? $netGoal;
                            $userRole = 'BUSINESS_DEVELOPMENT_EXCECUTIVE';
                            if ($goal->user->hasRole('SALES_MANAGER')) $userRole = 'SALES_MANAGER';
                            elseif ($goal->user->hasRole('BUSINESS_DEVELOPMENT_MANAGER')) $userRole = 'BUSINESS_DEVELOPMENT_MANAGER';
                            elseif ($goal->user->hasRole('ACCOUNT_MANAGER')) $userRole = 'ACCOUNT_MANAGER';
                            elseif ($goal->user->hasRole('TENDER_USER')) $userRole = 'TENDER_USER';
                        @endphp
                        @if($editGoal)
                            <a title="Delete Goal" data-route="{{ route('goals.delete', $editGoal->id) }}"
                                href="javascript:void(0);" id="delete" style="color:#dc3545;"><i class="fas fa-trash"></i></a>&nbsp;
                            @if(!$goal->user->hasRole('SALES_EXCUETIVE'))
                                <a href="javascript:void(0);" data-route="{{ route('goals.edit', $editGoal->id) }}"
                                    data-role="{{ $userRole }}" data-toggle="modal" class="edit-data" style="color:#f37e20;"><i class="fas fa-edit"></i></a>
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
