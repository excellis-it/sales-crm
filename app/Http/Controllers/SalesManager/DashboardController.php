<?php

namespace App\Http\Controllers\SalesManager;

use App\Http\Controllers\Controller;
use App\Models\Goal;
use App\Models\Project;
use App\Models\Prospect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $count['projects'] = Project::where('user_id', Auth::user()->id)->count();
        $count['prospects'] = Prospect::where('user_id', Auth::user()->id)->count();
        $data['gross_target_this_month'] = Goal::where('user_id', Auth::user()->id)->whereMonth('goals_date', date('m'))->whereYear('goals_date', date('Y'))->where('goals_type', 1)->pluck('goals_amount')->first();
        $data['gross_achieve_this_month'] = Goal::where('user_id', Auth::user()->id)->whereMonth('goals_date', date('m'))->whereYear('goals_date', date('Y'))->where('goals_type', 1)->pluck('goals_achieve')->first();
        $data['net_target_this_month'] = Goal::where('user_id', Auth::user()->id)->whereMonth('goals_date', date('m'))->whereYear('goals_date', date('Y'))->where('goals_type', 2)->pluck('goals_amount')->first();
        $data['net_achieve_this_month'] = Goal::where('user_id', Auth::user()->id)->whereMonth('goals_date', date('m'))->whereYear('goals_date', date('Y'))->where('goals_type', 2)->pluck('goals_achieve')->first();
        return view('sales_manager.dashboard')->with(compact('count', 'data'));
    }
}
