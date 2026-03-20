<?php

namespace App\Http\Controllers\SalesExcecutive;

use App\Http\Controllers\Controller;
use App\Models\Goal;
use App\Models\Prospect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // return Auth::user();
        $prospects = Prospect::where('user_id', auth()->user()->id)->orderBy('sale_date', 'desc')->paginate(15);
        $count['prospects'] = Prospect::where('user_id', auth()->user()->id)->count();
        $count['win'] = Prospect::where('user_id', auth()->user()->id)->where('status', 'Win')->count();
        $count['follow_up'] = Prospect::where('user_id', auth()->user()->id)->where('status', 'Follow Up')->count();
        $count['close'] = Prospect::where('user_id', auth()->user()->id)->where('status', 'Close')->count();
        $count['sent_proposal'] = Prospect::where('user_id', auth()->user()->id)->where('status', 'Sent Proposal')->count();
        $goal['gross_goals'] = Goal::where('user_id', auth()->user()->id)->where('goals_type', 1)->whereMonth('goals_date', date('m'))->first();
        $goal['net_goals'] = Goal::where('user_id', auth()->user()->id)->where('goals_type', 2)->whereMonth('goals_date', date('m'))->first();

        for ($m = 1; $m <= 12; $m++) {
            $monthName = strtolower(date('F', mktime(0, 0, 0, $m, 1)));
            $startOfMonth = date('Y-m-01', mktime(0, 0, 0, $m, 1));
            $endOfMonth = date('Y-m-t', mktime(0, 0, 0, $m, 1));
            
            $achievements = \App\Helpers\Helper::getUserAchievementDateRange(auth()->user()->id, $startOfMonth, $endOfMonth);
            $goal['gross_goals_' . $monthName] = $achievements['gross_amount'];
            $goal['net_goals_' . $monthName] = $achievements['net_amount'];
        }

        // Backward compatibility for misspelled february
        $goal['gross_goals_febuary'] = $goal['gross_goals_february'];
        $goal['net_goals_febuary'] = $goal['net_goals_february'];

        // Add dynamic achievement values for the current month
        $currentStart = date('Y-m-01');
        $currentEnd = date('Y-m-t');
        $currentAchieve = \App\Helpers\Helper::getUserAchievementDateRange(auth()->user()->id, $currentStart, $currentEnd);
        
        if ($goal['gross_goals']) {
            $goal['gross_goals']->goals_achieve = $currentAchieve['gross_amount'];
        }
        if ($goal['net_goals']) {
            $goal['net_goals']->goals_achieve = $currentAchieve['net_amount'];
        }

        $goal['prospect_january'] = Prospect::where('user_id', auth()->user()->id)->whereMonth('sale_date', 1)->whereYear('sale_date', date('Y'))->count();
        $goal['prospect_febuary'] = Prospect::where('user_id', auth()->user()->id)->whereMonth('sale_date', 2)->whereYear('sale_date', date('Y'))->count();
        $goal['prospect_march'] = Prospect::where('user_id', auth()->user()->id)->whereMonth('sale_date', 3)->whereYear('sale_date', date('Y'))->count();
        $goal['prospect_april'] = Prospect::where('user_id', auth()->user()->id)->whereMonth('sale_date', 4)->whereYear('sale_date', date('Y'))->count();
        $goal['prospect_may'] = Prospect::where('user_id', auth()->user()->id)->whereMonth('sale_date', 5)->whereYear('sale_date', date('Y'))->count();
        $goal['prospect_june'] = Prospect::where('user_id', auth()->user()->id)->whereMonth('sale_date', 6)->whereYear('sale_date', date('Y'))->count();
        $goal['prospect_july'] = Prospect::where('user_id', auth()->user()->id)->whereMonth('sale_date', 7)->whereYear('sale_date', date('Y'))->count();
        $goal['prospect_august'] = Prospect::where('user_id', auth()->user()->id)->whereMonth('sale_date', 8)->whereYear('sale_date', date('Y'))->count();
        $goal['prospect_september'] = Prospect::where('user_id', auth()->user()->id)->whereMonth('sale_date', 9)->whereYear('sale_date', date('Y'))->count();
        $goal['prospect_october'] = Prospect::where('user_id', auth()->user()->id)->whereMonth('sale_date', 10)->whereYear('sale_date', date('Y'))->count();
        $goal['prospect_november'] = Prospect::where('user_id', auth()->user()->id)->whereMonth('sale_date', 11)->whereYear('sale_date', date('Y'))->count();
        $goal['prospect_december'] = Prospect::where('user_id', auth()->user()->id)->whereMonth('sale_date', 12)->whereYear('sale_date', date('Y'))->count();


        return view('sales_excecutive.dashboard')->with(compact('count','goal', 'prospects'));
    }

    public function salesExecutiveDashboardProspectSearch(Request $request)
    {
        if ($request->ajax()) {
            $query = str_replace(" ", "%", $request->get('query'));
        
            $prospects = Prospect::where('user_id', auth()->user()->id)
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
        
            return response()->json(['data' => view('sales_excecutive.dashboard_prospect_table', compact('prospects'))->render()]);
        }
        
    }
}
