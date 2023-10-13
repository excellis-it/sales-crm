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
        $count['projects'] = Project::where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->count();
        $prospects = Prospect::where('report_to', auth()->user()->id)->orderBy('sale_date', 'desc')->get();
        $count['prospects'] = Prospect::where('report_to', auth()->user()->id)->count();
        $count['win'] = Prospect::where('report_to', auth()->user()->id)->where('status', 'Win')->count();
        $count['follow_up'] = Prospect::where('report_to', auth()->user()->id)->where('status', 'Follow Up')->count();
        $count['close'] = Prospect::where('report_to', auth()->user()->id)->where('status', 'Close')->count();
        $count['sent_proposal'] = Prospect::where('report_to', auth()->user()->id)->where('status', 'Sent Proposal')->count();
        $goal['gross_goals'] = Goal::where('user_id', auth()->user()->id)->where('goals_type', 1)->whereMonth('goals_date', date('m'))->first();
        $goal['net_goals'] = Goal::where('user_id', auth()->user()->id)->where('goals_type', 2)->whereMonth('goals_date', date('m'))->first();

        $goal['gross_goals_january'] = Goal::where('user_id', auth()->user()->id)->where('goals_type', 1)->whereMonth('goals_date', 1)->whereYear('goals_date', date('Y'))->first()['goals_achieve'] ?? '';
        $goal['gross_goals_febuary'] = Goal::where('user_id', auth()->user()->id)->where('goals_type', 1)->whereMonth('goals_date', 2)->whereYear('goals_date', date('Y'))->first()['goals_achieve'] ?? '';
        $goal['gross_goals_march'] = Goal::where('user_id', auth()->user()->id)->where('goals_type', 1)->whereMonth('goals_date', 3)->whereYear('goals_date', date('Y'))->first()['goals_achieve'] ?? '';
        $goal['gross_goals_april'] = Goal::where('user_id', auth()->user()->id)->where('goals_type', 1)->whereMonth('goals_date', 4)->whereYear('goals_date', date('Y'))->first()['goals_achieve'] ?? '';
        $goal['gross_goals_may'] = Goal::where('user_id', auth()->user()->id)->where('goals_type', 1)->whereMonth('goals_date', 5)->whereYear('goals_date', date('Y'))->first()['goals_achieve'] ?? '';
        $goal['gross_goals_june'] = Goal::where('user_id', auth()->user()->id)->where('goals_type', 1)->whereMonth('goals_date', 6)->whereYear('goals_date', date('Y'))->first()['goals_achieve'] ?? '';
        $goal['gross_goals_july'] = Goal::where('user_id', auth()->user()->id)->where('goals_type', 1)->whereMonth('goals_date', 7)->whereYear('goals_date', date('Y'))->first()['goals_achieve'] ?? '';
        $goal['gross_goals_august'] = Goal::where('user_id', auth()->user()->id)->where('goals_type', 1)->whereMonth('goals_date', 8)->whereYear('goals_date', date('Y'))->first()['goals_achieve'] ?? '';
        $goal['gross_goals_september'] = Goal::where('user_id', auth()->user()->id)->where('goals_type', 1)->whereMonth('goals_date', 9)->whereYear('goals_date', date('Y'))->first()['goals_achieve'] ?? '';
        $goal['gross_goals_october'] = Goal::where('user_id', auth()->user()->id)->where('goals_type', 1)->whereMonth('goals_date', 10)->whereYear('goals_date', date('Y'))->first()['goals_achieve'] ?? '';
        $goal['gross_goals_november'] = Goal::where('user_id', auth()->user()->id)->where('goals_type', 1)->whereMonth('goals_date', 11)->whereYear('goals_date', date('Y'))->first()['goals_achieve'] ?? '';
        $goal['gross_goals_december'] = Goal::where('user_id', auth()->user()->id)->where('goals_type', 1)->whereMonth('goals_date', 12)->whereYear('goals_date', date('Y'))->first()['goals_achieve'] ?? '';

        $goal['net_goals_january'] = Goal::where('user_id', auth()->user()->id)->where('goals_type', 2)->whereMonth('goals_date', 1)->whereYear('goals_date', date('Y'))->first()['goals_achieve'] ?? '';
        $goal['net_goals_febuary'] = Goal::where('user_id', auth()->user()->id)->where('goals_type', 2)->whereMonth('goals_date', 2)->whereYear('goals_date', date('Y'))->first()['goals_achieve'] ?? '';
        $goal['net_goals_march'] = Goal::where('user_id', auth()->user()->id)->where('goals_type', 2)->whereMonth('goals_date', 3)->whereYear('goals_date', date('Y'))->first()['goals_achieve'] ?? '';
        $goal['net_goals_april'] = Goal::where('user_id', auth()->user()->id)->where('goals_type', 2)->whereMonth('goals_date', 4)->whereYear('goals_date', date('Y'))->first()['goals_achieve'] ?? '';
        $goal['net_goals_may'] = Goal::where('user_id', auth()->user()->id)->where('goals_type', 2)->whereMonth('goals_date', 5)->whereYear('goals_date', date('Y'))->first()['goals_achieve'] ?? '';

        $goal['net_goals_june'] = Goal::where('user_id', auth()->user()->id)->where('goals_type', 2)->whereMonth('goals_date', 6)->whereYear('goals_date', date('Y'))->first()['goals_achieve'] ?? '';
        $goal['net_goals_july'] = Goal::where('user_id', auth()->user()->id)->where('goals_type', 2)->whereMonth('goals_date', 7)->whereYear('goals_date', date('Y'))->first()['goals_achieve'] ?? '';
        $goal['net_goals_august'] = Goal::where('user_id', auth()->user()->id)->where('goals_type', 2)->whereMonth('goals_date', 8)->whereYear('goals_date', date('Y'))->first()['goals_achieve'] ?? '';
        $goal['net_goals_september'] = Goal::where('user_id', auth()->user()->id)->where('goals_type', 2)->whereMonth('goals_date', 9)->whereYear('goals_date', date('Y'))->first()['goals_achieve'] ?? '';
        $goal['net_goals_october'] = Goal::where('user_id', auth()->user()->id)->where('goals_type', 2)->whereMonth('goals_date', 10)->whereYear('goals_date', date('Y'))->first()['goals_achieve'] ?? '';
        $goal['net_goals_november'] = Goal::where('user_id', auth()->user()->id)->where('goals_type', 2)->whereMonth('goals_date', 11)->whereYear('goals_date', date('Y'))->first()['goals_achieve'] ?? '';
        $goal['net_goals_december'] = Goal::where('user_id', auth()->user()->id)->where('goals_type', 2)->whereMonth('goals_date', 12)->whereYear('goals_date', date('Y'))->first()['goals_achieve'] ?? '';

        $goal['prospect_january'] = Prospect::where('report_to', auth()->user()->id)->whereMonth('sale_date', 1)->whereYear('sale_date', date('Y'))->count();
        $goal['prospect_febuary'] = Prospect::where('report_to', auth()->user()->id)->whereMonth('sale_date', 2)->whereYear('sale_date', date('Y'))->count();
        $goal['prospect_march'] = Prospect::where('report_to', auth()->user()->id)->whereMonth('sale_date', 3)->whereYear('sale_date', date('Y'))->count();
        $goal['prospect_april'] = Prospect::where('report_to', auth()->user()->id)->whereMonth('sale_date', 4)->whereYear('sale_date', date('Y'))->count();
        $goal['prospect_may'] = Prospect::where('report_to', auth()->user()->id)->whereMonth('sale_date', 5)->whereYear('sale_date', date('Y'))->count();
        $goal['prospect_june'] = Prospect::where('report_to', auth()->user()->id)->whereMonth('sale_date', 6)->whereYear('sale_date', date('Y'))->count();
        $goal['prospect_july'] = Prospect::where('report_to', auth()->user()->id)->whereMonth('sale_date', 7)->whereYear('sale_date', date('Y'))->count();
        $goal['prospect_august'] = Prospect::where('report_to', auth()->user()->id)->whereMonth('sale_date', 8)->whereYear('sale_date', date('Y'))->count();
        $goal['prospect_september'] = Prospect::where('report_to', auth()->user()->id)->whereMonth('sale_date', 9)->whereYear('sale_date', date('Y'))->count();
        $goal['prospect_october'] = Prospect::where('report_to', auth()->user()->id)->whereMonth('sale_date', 10)->whereYear('sale_date', date('Y'))->count();
        $goal['prospect_november'] = Prospect::where('report_to', auth()->user()->id)->whereMonth('sale_date', 11)->whereYear('sale_date', date('Y'))->count();
        $goal['prospect_december'] = Prospect::where('report_to', auth()->user()->id)->whereMonth('sale_date', 12)->whereYear('sale_date', date('Y'))->count();
        return view('sales_manager.dashboard')->with(compact('count', 'goal', 'prospects'));
    }
}
