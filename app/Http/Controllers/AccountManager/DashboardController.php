<?php

namespace App\Http\Controllers\AccountManager;

use App\Http\Controllers\Controller;
use App\Models\Goal;
use App\Models\Project;
use App\Models\ProjectMilestone;
use App\Models\Upsale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::user()->id;
        $currentStart = date('Y-m-01');
        $currentEnd = date('Y-m-t');

        // Project counts
        $projects = Project::where('assigned_to', $userId)->get();
        $count['projects'] = $projects->count();

        // Total project value & upfront for assigned projects
        $count['total_project_value'] = $projects->sum('project_value');
        $count['total_upfront'] = $projects->sum('project_upfront');

        // Upsales for assigned projects
        $projectIds = $projects->pluck('id');
        $count['upsales'] = Upsale::whereIn('project_id', $projectIds)->count();
        $count['upsale_value'] = Upsale::whereIn('project_id', $projectIds)->sum('upsale_value');

        // Payments (paid milestones)
        $paidMilestones = ProjectMilestone::where('payment_status', 'Paid')
            ->whereIn('project_id', $projectIds)
            ->get();
        $count['total_payments'] = $paidMilestones->count();
        $count['total_payment_amount'] = $paidMilestones->sum('milestone_value');

        // Pending milestones
        $pendingMilestones = ProjectMilestone::where('payment_status', '!=', 'Paid')
            ->whereIn('project_id', $projectIds)
            ->get();
        $count['pending_milestones'] = $pendingMilestones->count();
        $count['pending_amount'] = $pendingMilestones->sum('milestone_value');

        // This month's payments
        $count['monthly_payments'] = ProjectMilestone::where('payment_status', 'Paid')
            ->whereIn('project_id', $projectIds)
            ->whereBetween('payment_date', [$currentStart, $currentEnd])
            ->sum('milestone_value');

        // Monthly revenue goal (Net)
        $count['net_target'] = Goal::where('user_id', $userId)
            ->whereMonth('goals_date', date('m'))
            ->whereYear('goals_date', date('Y'))
            ->where('goals_type', 2)
            ->first();

        if ($count['net_target']) {
            $achievements = \App\Helpers\Helper::getUserAchievementDateRange($userId, $currentStart, $currentEnd);
            $count['net_target']->goals_achieve = $achievements['net_amount'];
        }

        // Recent 5 projects
        $recentProjects = Project::where('assigned_to', $userId)
            ->with(['projectTypes', 'projectOpener', 'upsales', 'allProjectMilestones'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Recent 5 payments
        $recentPayments = ProjectMilestone::where('payment_status', 'Paid')
            ->whereIn('project_id', $projectIds)
            ->with('project')
            ->orderBy('payment_date', 'desc')
            ->limit(5)
            ->get();

        // Upcoming pending milestones
        $upcomingMilestones = ProjectMilestone::where('payment_status', '!=', 'Paid')
            ->whereIn('project_id', $projectIds)
            ->with('project')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('account_manager.dashboard')->with(compact(
            'count',
            'recentProjects',
            'recentPayments',
            'upcomingMilestones'
        ));
    }
}
