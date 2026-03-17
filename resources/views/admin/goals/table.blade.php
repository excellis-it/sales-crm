<table id="myTable" class="dd table table-striped table-hover" style="width:100%">
    <thead>
        <tr>
            <th> Goals Date</th>
            <th> Goals Type</th>
            <th> Role</th>
            <th> Goal Assign For</th>
            <th> Target Amount</th>
            <th> Target Achieve</th>
            <th style="width: 140px;"> Progress</th>
            <th> Action</th>
        </tr>
    </thead>
    <tbody>
        @if (count($goals) == 0)
            <tr>
                <td colspan="8" class="text-center">No Goals found</td>
            </tr>
        @else
            @foreach ($goals as $key => $goal)
                @php
                    $percentage = $goal->goals_amount > 0 ? round(($goal->goals_achieve / $goal->goals_amount) * 100, 1) : 0;
                    $percentage = min($percentage, 100);
                    if ($percentage >= 80) {
                        $progressColor = '#28a745';
                    } elseif ($percentage >= 50) {
                        $progressColor = '#f37e20';
                    } else {
                        $progressColor = '#dc3545';
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
                        @if($goal->goals_type == 1)
                            <span style="background: #ffdfc5; color: #ad1e23; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">Gross</span>
                        @else
                            <span style="background: #d4edda; color: #155724; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">Net</span>
                        @endif
                    </td>
                    <td>
                        <span style="{{ $roleBadge }} padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; white-space: nowrap;">{{ $roleName }}</span>
                    </td>
                    <td>
                        <span style="font-weight: 500;">{{ $goal->user->name }}</span>
                    </td>
                    <td>
                        <span style="font-weight: 600; color: #334257;">${{ number_format($goal->goals_amount, 2) }}</span>
                    </td>
                    <td>
                        <span style="font-weight: 600; color: {{ $percentage >= 80 ? '#28a745' : ($percentage >= 50 ? '#f37e20' : '#dc3545') }};">${{ number_format($goal->goals_achieve ?? 0, 2) }}</span>
                    </td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <div style="flex: 1; background: #e9ecef; border-radius: 10px; height: 8px; overflow: hidden;">
                                <div style="width: {{ $percentage }}%; background: {{ $progressColor }}; height: 100%; border-radius: 10px; transition: width 0.3s ease;"></div>
                            </div>
                            <span style="font-size: 11px; font-weight: 600; color: {{ $progressColor }}; min-width: 38px;">{{ $percentage }}%</span>
                        </div>
                    </td>
                    <td>
                        @if ($goal->goals_type == 1)
                            <a title="Delete Goal" data-route="{{ route('goals.delete', $goal->id) }}"
                                href="javascipt:void(0);" id="delete" style="color: #dc3545;"><i class="fas fa-trash"></i></a>&nbsp;
                            @if(!$goal->user->hasRole('SALES_EXCUETIVE'))
                                <a href="javascript:void(0);" data-route="{{ route('goals.edit', $goal->id) }}"
                                    data-role="@if($goal->user->hasRole('SALES_MANAGER'))SALES_MANAGER @elseif($goal->user->hasRole('BUSINESS_DEVELOPMENT_MANAGER'))BUSINESS_DEVELOPMENT_MANAGER @elseif($goal->user->hasRole('ACCOUNT_MANAGER'))ACCOUNT_MANAGER @else BUSINESS_DEVELOPMENT_EXCECUTIVE @endif" data-toggle="modal" class="edit-data" style="color: #f37e20;"> <i class="fas fa-edit"></i></a>
                            @endif
                        @else
                            <a title="Delete Goal" data-route="{{ route('goals.delete', $goal->id) }}"
                                href="javascipt:void(0);" id="delete" style="color: #dc3545;"><i class="fas fa-trash"></i></a>
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
