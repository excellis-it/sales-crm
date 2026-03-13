<?php

namespace App\Http\Controllers\TenderUser;

use App\Http\Controllers\Controller;
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

        return view('tender_user.dashboard', compact(
            'totalProjects', 
            'totalValue', 
            'statusStats', 
            'paidMilestones', 
            'dueMilestones', 
            'recentTenders', 
            'recentFollowups'
        ));
    }
}
