<?php

namespace App\Http\Controllers\AccountManager;

use App\Http\Controllers\Controller;
use App\Models\Goal;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $count['projects'] = Project::where('assigned_to', auth()->user()->id)->count();
        $count['net_target'] = Goal::where('user_id', Auth::user()->id)->whereMonth('goals_date', date('m'))->whereYear('goals_date', date('Y'))->where('goals_type', 2)->first();
        
        if ($count['net_target']) {
            $currentStart = date('Y-m-01');
            $currentEnd = date('Y-m-t');
            $achievements = \App\Helpers\Helper::getUserAchievementDateRange(Auth::user()->id, $currentStart, $currentEnd);
            $count['net_target']->goals_achieve = $achievements['net_amount'];
        }

        return view('account_manager.dashboard')->with(compact('count'));
    }
}
