<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Goal;
use App\Models\Project;
use App\Models\User;
use App\Models\Prospect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $count['users'] = User::whereNotIn('id', [Auth::user()->id])->count();
        $count['sales_managers'] = User::Role('SALES_MANAGER')->count();
        $count['account_managers'] = User::Role('ACCOUNT_MANAGER')->count();
        $count['sales_excecutive'] = User::Role('SALES_EXCUETIVE')->count();
        $count['projects'] = Project::orderBy('created_at', 'desc')->count();
        $prospects = Prospect::orderBy('sale_date', 'desc')->get();
        $count['prospects'] = Prospect::count();
        $count['win'] = Prospect::where('status', 'Win')->count();
        $count['follow_up'] = Prospect::where('status', 'Follow Up')->count();
        $count['close'] = Prospect::where('status', 'Close')->count();
        $count['sent_proposal'] = Prospect::where('status', 'Sent Proposal')->count();
        // get sales manager id
        $sales_manager_id = User::Role('SALES_MANAGER')->pluck('id');
        $goal['gross_goals_achieve'] = Goal::where('goals_type', 1)->whereIn('user_id', $sales_manager_id)->whereMonth('goals_date', date('m'))->sum('goals_achieve');
        $goal['net_goals_achieve'] = Goal::where('goals_type', 2)->whereIn('user_id', $sales_manager_id)->whereMonth('goals_date', date('m'))->sum('goals_achieve');
        $goal['gross_goals'] = Goal::where('goals_type', 1)->whereIn('user_id', $sales_manager_id)->whereMonth('goals_date', date('m'))->sum('goals_amount');
        $goal['net_goals'] = Goal::where('goals_type', 2)->whereIn('user_id', $sales_manager_id)->whereMonth('goals_date', date('m'))->sum('goals_amount');

        $goal['gross_goals_january'] = Goal::where('goals_type', 1)->whereMonth('goals_date', 1)->whereYear('goals_date', date('Y'))->first()['goals_achieve'] ?? '';
        $goal['gross_goals_febuary'] = Goal::where('goals_type', 1)->whereMonth('goals_date', 2)->whereYear('goals_date', date('Y'))->first()['goals_achieve'] ?? '';
        $goal['gross_goals_march'] = Goal::where('goals_type', 1)->whereMonth('goals_date', 3)->whereYear('goals_date', date('Y'))->first()['goals_achieve'] ?? '';
        $goal['gross_goals_april'] = Goal::where('goals_type', 1)->whereMonth('goals_date', 4)->whereYear('goals_date', date('Y'))->first()['goals_achieve'] ?? '';
        $goal['gross_goals_may'] = Goal::where('goals_type', 1)->whereMonth('goals_date', 5)->whereYear('goals_date', date('Y'))->first()['goals_achieve'] ?? '';
        $goal['gross_goals_june'] = Goal::where('goals_type', 1)->whereMonth('goals_date', 6)->whereYear('goals_date', date('Y'))->first()['goals_achieve'] ?? '';
        $goal['gross_goals_july'] = Goal::where('goals_type', 1)->whereMonth('goals_date', 7)->whereYear('goals_date', date('Y'))->first()['goals_achieve'] ?? '';
        $goal['gross_goals_august'] = Goal::where('goals_type', 1)->whereMonth('goals_date', 8)->whereYear('goals_date', date('Y'))->first()['goals_achieve'] ?? '';
        $goal['gross_goals_september'] = Goal::where('goals_type', 1)->whereMonth('goals_date', 9)->whereYear('goals_date', date('Y'))->first()['goals_achieve'] ?? '';
        $goal['gross_goals_october'] = Goal::where('goals_type', 1)->whereMonth('goals_date', 10)->whereYear('goals_date', date('Y'))->first()['goals_achieve'] ?? '';
        $goal['gross_goals_november'] = Goal::where('goals_type', 1)->whereMonth('goals_date', 11)->whereYear('goals_date', date('Y'))->first()['goals_achieve'] ?? '';
        $goal['gross_goals_december'] = Goal::where('goals_type', 1)->whereMonth('goals_date', 12)->whereYear('goals_date', date('Y'))->first()['goals_achieve'] ?? '';

        $goal['net_goals_january'] = Goal::where('goals_type', 2)->whereMonth('goals_date', 1)->whereYear('goals_date', date('Y'))->first()['goals_achieve'] ?? '';
        $goal['net_goals_febuary'] = Goal::where('goals_type', 2)->whereMonth('goals_date', 2)->whereYear('goals_date', date('Y'))->first()['goals_achieve'] ?? '';
        $goal['net_goals_march'] = Goal::where('goals_type', 2)->whereMonth('goals_date', 3)->whereYear('goals_date', date('Y'))->first()['goals_achieve'] ?? '';
        $goal['net_goals_april'] = Goal::where('goals_type', 2)->whereMonth('goals_date', 4)->whereYear('goals_date', date('Y'))->first()['goals_achieve'] ?? '';
        $goal['net_goals_may'] = Goal::where('goals_type', 2)->whereMonth('goals_date', 5)->whereYear('goals_date', date('Y'))->first()['goals_achieve'] ?? '';

        $goal['net_goals_june'] = Goal::where('goals_type', 2)->whereMonth('goals_date', 6)->whereYear('goals_date', date('Y'))->first()['goals_achieve'] ?? '';
        $goal['net_goals_july'] = Goal::where('goals_type', 2)->whereMonth('goals_date', 7)->whereYear('goals_date', date('Y'))->first()['goals_achieve'] ?? '';
        $goal['net_goals_august'] = Goal::where('goals_type', 2)->whereMonth('goals_date', 8)->whereYear('goals_date', date('Y'))->first()['goals_achieve'] ?? '';
        $goal['net_goals_september'] = Goal::where('goals_type', 2)->whereMonth('goals_date', 9)->whereYear('goals_date', date('Y'))->first()['goals_achieve'] ?? '';
        $goal['net_goals_october'] = Goal::where('goals_type', 2)->whereMonth('goals_date', 10)->whereYear('goals_date', date('Y'))->first()['goals_achieve'] ?? '';
        $goal['net_goals_november'] = Goal::where('goals_type', 2)->whereMonth('goals_date', 11)->whereYear('goals_date', date('Y'))->first()['goals_achieve'] ?? '';
        $goal['net_goals_december'] = Goal::where('goals_type', 2)->whereMonth('goals_date', 12)->whereYear('goals_date', date('Y'))->first()['goals_achieve'] ?? '';

        $goal['prospect_january'] = Prospect::whereMonth('sale_date', 1)->whereYear('sale_date', date('Y'))->count();
        $goal['prospect_febuary'] = Prospect::whereMonth('sale_date', 2)->whereYear('sale_date', date('Y'))->count();
        $goal['prospect_march'] = Prospect::whereMonth('sale_date', 3)->whereYear('sale_date', date('Y'))->count();
        $goal['prospect_april'] = Prospect::whereMonth('sale_date', 4)->whereYear('sale_date', date('Y'))->count();
        $goal['prospect_may'] = Prospect::whereMonth('sale_date', 5)->whereYear('sale_date', date('Y'))->count();
        $goal['prospect_june'] = Prospect::whereMonth('sale_date', 6)->whereYear('sale_date', date('Y'))->count();
        $goal['prospect_july'] = Prospect::whereMonth('sale_date', 7)->whereYear('sale_date', date('Y'))->count();
        $goal['prospect_august'] = Prospect::whereMonth('sale_date', 8)->whereYear('sale_date', date('Y'))->count();
        $goal['prospect_september'] = Prospect::whereMonth('sale_date', 9)->whereYear('sale_date', date('Y'))->count();
        $goal['prospect_october'] = Prospect::whereMonth('sale_date', 10)->whereYear('sale_date', date('Y'))->count();
        $goal['prospect_november'] = Prospect::whereMonth('sale_date', 11)->whereYear('sale_date', date('Y'))->count();
        $goal['prospect_december'] = Prospect::whereMonth('sale_date', 12)->whereYear('sale_date', date('Y'))->count();
        // dd($count);
        return view('admin.dashboard')->with(compact('count', 'goal', 'prospects'));
    }
}
