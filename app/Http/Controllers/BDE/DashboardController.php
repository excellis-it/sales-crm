<?php

namespace App\Http\Controllers\BDE;

use App\Http\Controllers\Controller;
use App\Models\Goal;
use App\Models\Project;
use App\Models\BdmProspect;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //

    public function index()
    {
        $userId       = auth()->user()->id;
        $currentMonth = date('m');
        $currentYear  = date('Y');

        $count['prospects']    = BdmProspect::where('user_id', $userId)->count();
        $count['win']          = BdmProspect::where('user_id', $userId)->where('status', 'Win')->count();
        $count['follow_up']    = BdmProspect::where('user_id', $userId)->where('status', 'Follow Up')->count();
        $count['close']        = BdmProspect::where('user_id', $userId)->where('status', 'Close')->count();
        $count['sent_proposal'] = BdmProspect::where('user_id', $userId)->where('status', 'Sent Proposal')->count();

        $goal['gross_goals']   = Goal::where('user_id', $userId)->where('goals_type', 1)->whereMonth('goals_date', $currentMonth)->first();
        $goal['net_goals']     = Goal::where('user_id', $userId)->where('goals_type', 2)->whereMonth('goals_date', $currentMonth)->first();
        $goal['meetings_goal'] = Goal::where('user_id', $userId)->where('goals_type', 3)->whereMonth('goals_date', $currentMonth)->whereYear('goals_date', $currentYear)->first();
        $goal['onboard_goal']  = Goal::where('user_id', $userId)->where('goals_type', 4)->whereMonth('goals_date', $currentMonth)->whereYear('goals_date', $currentYear)->first();

        for ($m = 1; $m <= 12; $m++) {
            $monthName    = strtolower(date('F', mktime(0, 0, 0, $m, 1)));
            $startOfMonth = date('Y-m-01', mktime(0, 0, 0, $m, 1));
            $endOfMonth   = date('Y-m-t',  mktime(0, 0, 0, $m, 1));
            $achievements = \App\Helpers\Helper::getUserAchievementDateRange($userId, $startOfMonth, $endOfMonth);
            $goal['gross_goals_' . $monthName] = $achievements['gross_amount'];
            $goal['net_goals_' . $monthName]   = $achievements['net_amount'];
        }

        $goal['gross_goals_febuary'] = $goal['gross_goals_february'];
        $goal['net_goals_febuary']   = $goal['net_goals_february'];

        $currentStart   = date('Y-m-01');
        $currentEnd     = date('Y-m-t');
        $currentAchieve = \App\Helpers\Helper::getUserAchievementDateRange($userId, $currentStart, $currentEnd);

        if ($goal['gross_goals']) {
            $goal['gross_goals']->goals_achieve = $currentAchieve['gross_amount'];
        }
        if ($goal['net_goals']) {
            $goal['net_goals']->goals_achieve = $currentAchieve['net_amount'];
        }

        // Live meetings and onboard achievements for current month via Helper
        $moAchieve = \App\Helpers\Helper::getUserMeetingsAndOnboardAchievement($userId, $currentStart, $currentEnd);

        if ($goal['meetings_goal']) {
            $goal['meetings_goal']->goals_achieve = $moAchieve['meetings'];
        }
        if ($goal['onboard_goal']) {
            $goal['onboard_goal']->goals_achieve = $moAchieve['onboard'];
        }

        for ($m = 1; $m <= 12; $m++) {
            $mName = strtolower(date('F', mktime(0, 0, 0, $m, 1)));
            $startOfMonth = date('Y-m-01', mktime(0, 0, 0, $m, 1));
            $endOfMonth   = date('Y-m-t',  mktime(0, 0, 0, $m, 1));
            $moAchieve = \App\Helpers\Helper::getUserMeetingsAndOnboardAchievement($userId, $startOfMonth, $endOfMonth);
            $goal['meetings_achieve_' . $mName] = $moAchieve['meetings'];
            $goal['onboard_achieve_' . $mName]  = $moAchieve['onboard'];
            $goal['prospect_' . $mName] = BdmProspect::where('user_id', $userId)->whereMonth('sale_date', $m)->whereYear('sale_date', $currentYear)->count();
        }
        $goal['prospect_febuary'] = $goal['prospect_february'];
        $goal['meetings_achieve_febuary'] = $goal['meetings_achieve_february'];
        $goal['onboard_achieve_febuary']  = $goal['onboard_achieve_february'];

        $prospects = BdmProspect::where('user_id', $userId)->orderBy('sale_date', 'desc')->paginate(15);

        return view('bde.dashboard', compact('count', 'goal', 'prospects'));
    }

    public function bdeDashboardBdmProspectSearch(Request $request)
    {
        if ($request->ajax()) {
            $query = str_replace(" ", "%", $request->get('query'));

            $prospects = BdmProspect::where('user_id', auth()->user()->id)
                ->where(function ($q) use ($query) {
                    $q->where('id', 'like', '%' . $query . '%')
                        ->orWhere('sale_date', 'like', '%' . $query . '%')
                        ->orWhere('client_name', 'like', '%' . $query . '%')
                        ->orWhere('client_email', 'like', '%' . $query . '%')
                        ->orWhere('business_name', 'like', '%' . $query . '%')
                        ->orWhere('client_phone', 'like', '%' . $query . '%')
                        ->orWhere('followup_date', 'like', '%' . $query . '%')
                        ->orWhere('status', 'like', '%' . $query . '%')
                        ->orWhere('offered_for', 'like', '%' . $query . '%')
                        ->orWhere('price_quote', 'like', '%' . $query . '%');
                })
                ->paginate(15);

            return response()->json(['data' => view('bde.dashboard_prospect_table', compact('prospects'))->render()]);
        }
    }
}
