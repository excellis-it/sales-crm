<?php

namespace App\Http\Controllers\TenderUser;

use App\Http\Controllers\Controller;
use App\Models\Goal;
use App\Models\ProjectMilestone;
use App\Models\TenderFollowup;
use App\Models\TenderProject;
use App\Models\TenderStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // Basic Stats
        $totalProjects = TenderProject::where('tender_user_id', $userId)->count();
        $totalValue = TenderProject::where('tender_user_id', $userId)->sum('tender_value_lakhs');
        
        // Project Status Stats
        $statusStats = TenderProject::where('tender_user_id', $userId)
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->with('tenderStatus')
            ->get();

        // Milestone Stats
        $milestoneStats = ProjectMilestone::whereHas('tenderProject', function($q) use ($userId) {
                $q->where('tender_user_id', $userId);
            })
            ->selectRaw('payment_status, count(*) as count, sum(milestone_value) as total_value')
            ->groupBy('payment_status')
            ->get();

        $paidMilestones = $milestoneStats->where('payment_status', 'Paid')->first();
        $dueMilestones = $milestoneStats->where('payment_status', 'Due')->first();

        // Recent Tenders
        $recentTenders = TenderProject::where('tender_user_id', $userId)
            ->with('tenderStatus')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Recent Follow-ups
        $recentFollowups = TenderFollowup::whereHas('tenderProject', function($q) use ($userId) {
                $q->where('tender_user_id', $userId);
            })
            ->with(['tenderProject', 'user'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Quarterly Goal
        $currentQuarter  = (int) ceil((int) date('m') / 3);
        $currentYear     = (int) date('Y');
        $qStartMonth     = ($currentQuarter - 1) * 3 + 1;
        $qStart          = $currentYear . '-' . sprintf('%02d', $qStartMonth) . '-01';
        $qEnd            = date('Y-m-t', mktime(0, 0, 0, $qStartMonth + 2, 1));

        $quarterlyGoal   = Goal::where('user_id', $userId)
            ->where('goals_type', 1)
            ->whereNotNull('quarter')
            ->where('quarter', $currentQuarter)
            ->whereYear('goals_date', $currentYear)
            ->first();

        $quarterlyAchieve = TenderProject::where('tender_user_id', $userId)
            ->whereBetween('created_at', [$qStart . ' 00:00:00', $qEnd . ' 23:59:59'])
            ->sum('tender_value_lakhs');

        $quarterLabel    = 'Q' . $currentQuarter . ' ' . $currentYear;
        $quarterlyTarget = $quarterlyGoal ? $quarterlyGoal->goals_amount : 0;
        $quarterlyPct    = $quarterlyTarget > 0 ? min(round(($quarterlyAchieve / $quarterlyTarget) * 100), 100) : 0;

        return view('tender_user.dashboard', compact(
            'totalProjects',
            'totalValue',
            'statusStats',
            'paidMilestones',
            'dueMilestones',
            'recentTenders',
            'recentFollowups',
            'quarterlyGoal',
            'quarterlyAchieve',
            'quarterlyTarget',
            'quarterlyPct',
            'quarterLabel'
        ));
    }
}
