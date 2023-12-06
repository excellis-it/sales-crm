<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Goal;
use App\Models\Project;
use App\Models\ProjectMilestone;
use App\Models\User;
use App\Models\Prospect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class DashboardController extends Controller
{
    public function index()
    {
        $count['users'] = User::whereNotIn('id', [Auth::user()->id])->count();
        $count['sales_managers'] = User::Role('SALES_MANAGER')->count();
        $count['account_managers'] = User::Role('ACCOUNT_MANAGER')->count();
        $count['sales_excecutive'] = User::Role('SALES_EXCUETIVE')->count();
        $count['projects'] = Project::orderBy('created_at', 'desc')->count();
        $count['bdm'] = User::Role('BUSINESS_DEVELOPMENT_MANAGER')->count();
        // $prospects = Prospect::orderBy('sale_date', 'desc')->get();
        $prospects = Prospect::orderBy('sale_date', 'desc')->take(10)->get();
        $count['prospects'] = Prospect::count();
        $count['win'] = Prospect::where('status', 'Win')->count();
        $count['follow_up'] = Prospect::where('status', 'Follow Up')->count();
        $count['close'] = Prospect::where('status', 'Close')->count();
        $count['sent_proposal'] = Prospect::where('status', 'Sent Proposal')->count();
        $projects = Project::orderBy('sale_date', 'desc')->take(7)->get();
        // account manager revenue this month
        $account_manager_id = User::Role('ACCOUNT_MANAGER')->pluck('id');
        $bdma_manager_id = User::Role('BUSINESS_DEVELOPMENT_MANAGER')->pluck('id');
        $count['account_manager_revenue'] = Goal::whereIn('user_id', $account_manager_id)->whereMonth('goals_date', date('m'))->whereYear('goals_date', date('Y'))->sum('goals_achieve');
        $count['bdm_revenue'] = Goal::whereIn('user_id', $bdma_manager_id)->whereMonth('goals_date', date('m'))->whereYear('goals_date', date('Y'))->sum('goals_achieve');
        $count['account_manager_goals'] = Goal::whereIn('user_id', $account_manager_id)->whereMonth('goals_date', date('m'))->whereYear('goals_date', date('Y'))->sum('goals_amount');
        $count['bdm_goals'] = Goal::whereIn('user_id', $bdma_manager_id)->whereMonth('goals_date', date('m'))->whereYear('goals_date', date('Y'))->sum('goals_amount');
        $count['account_manager_percentage'] = ($count['account_manager_goals'] > 0) ? round(($count['account_manager_revenue']  / $count['account_manager_goals']) * 100) : 0;
        $count['bdm_percentage'] = ($count['bdm_goals'] > 0) ? round(($count['bdm_revenue'] / $count['bdm_goals']) * 100) : 0;
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
        $labels = ['Jan', 'Febr', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec'];
        $type = 'YearEarn';
        // dd($count);
        return view('admin.dashboard')->with(compact('count', 'goal', 'prospects','projects','labels','type'));
    }

    public function dashboardProspectFetch(Request $request)
    {

        if ($request->ajax()) {
            $sort_by = $request->get('sortby');
            $sort_type = $request->get('sorttype');
            $query = $request->get('query');
            $query = str_replace(" ", "%", $query);
            $prospects = Prospect::where('id', 'like', '%' . $query . '%')
                ->orWhere('sale_date', 'like', '%' . $query . '%')
                ->orWhere('client_name', 'like', '%' . $query . '%')
                ->orWhere('client_email', 'like', '%' . $query . '%')
                ->orWhere('business_name', 'like', '%' . $query . '%')
                ->orWhere('client_phone', 'like', '%' . $query . '%')
                ->orWhere('followup_date', 'like', '%' . $query . '%')
                ->orWhere('status', 'like', '%' . $query . '%')
                ->orWhere('offered_for', 'like', '%' . $query . '%')
                ->orWhere('price_quote', 'like', '%' . $query . '%')
                ->paginate(10);

            return response()->json(['data' => view('admin.dashboard_prospect_table', compact('prospects'))->render()]);
        }
    }

    public function getEarningStatistics(Request $request)
    {
      
        $type = $request->type;
        if($type == 'yearEarn')
        {
           
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
            $labels = ['Jan', 'Febr', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec'];
            $type = 'YearEarn';
           

            return response()->json(['view'=>(String)View::make('admin.statistic_ajax_bar_chart')->with(compact('labels','goal','type'))]);
            // return "gg";
            // return response()->json(['view'=>(String)View::make('admin.statistic_ajax_bar_chart')->with(compact('labels','goal','type'))]);
        }
        else if($type == 'MonthEarn')
        {

            $current_month = date('m');
            $total_days = cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y'));
                $goals = [];
                $labels = [];

                for ($i = 1; $i <= $total_days; $i++) {
                   // store all days in array in labels
                    $labels[] = $i;
                      $gross_goals[]= Goal::where('goals_type', 1)->whereDay('goals_date',$i)->whereMonth('goals_date', $current_month)->whereYear('goals_date', date('Y'))->first()['goals_achieve'] ?? 0;
                      $net_goals[]= Goal::where('goals_type', 2)->whereDay('goals_date',$i)->whereMonth('goals_date', $current_month)->whereYear('goals_date', date('Y'))->first()['goals_achieve'] ?? 0;
                      $prospects[]= Prospect::whereDay('sale_date',$i)->whereMonth('sale_date', $current_month)->whereYear('sale_date', date('Y'))->count();
                      // $goals[$i]['days'] = Goal::where('goals_type', 1)
                    //     ->whereDay('goals_date', $i)
                    //     ->whereMonth('goals_date', date('m'))
                    //     ->whereYear('goals_date', date('Y'))
                    //     ->sum('goals_achieve');
                }
                
            
            $type = 'MonthEarn';

            return response()->json(['view'=>(String)View::make('admin.statistic_ajax_bar_chart')->with(compact('labels','gross_goals','type','net_goals','prospects'))]);
        }else{

        //    $date =  Prospect::whereDay('sale_date', 6)
        //             ->whereRaw('WEEK(sale_date) = 49')
        //             ->whereYear('sale_date', date('Y'))->toSql();
        //             return $date;
            //week earn chart
            $current_month = date('m');
            $current_week = date('W');
            $total_days = 7;
            $labels = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            $gross_goals = [];
            $net_goals = [];
            $prospects = [];

            for ($i = 1; $i <= $total_days; $i++) {
                // store all days in array in labels
                
                $j =$i -1;
                $currentDay = $labels[$j];
                $gross_goals[] = Goal::where('goals_type', 1)
                    ->whereDay('goals_date', $i)
                    ->whereRaw('WEEK(goals_date) = ?', [$current_week])
                    ->whereYear('goals_date', date('Y'))
                    ->sum('goals_achieve');

                $net_goals[] = Goal::where('goals_type', 2)
                    ->whereDay('goals_date', $i)
                    ->whereRaw('WEEK(goals_date) = ?', [$current_week])
                    ->whereYear('goals_date', date('Y'))
                    ->sum('goals_achieve');

                $prospects[] = Prospect::whereRaw('DAYNAME(sale_date)="'.$currentDay.'"')
                    ->whereRaw('WEEK(sale_date) ='.$current_week)
                    ->whereYear('sale_date', date('Y'))
                    ->count();
            }
            
            $type = 'WeekEarn';
            return response()->json(['view'=>(String)View::make('admin.statistic_ajax_bar_chart')->with(compact('labels','gross_goals','type','net_goals','prospects'))]);
        }

        
        
    }
}
