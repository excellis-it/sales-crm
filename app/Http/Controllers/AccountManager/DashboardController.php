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
        return view('account_manager.dashboard')->with(compact('count'));
    }
}
