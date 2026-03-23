<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Goal;
use App\Models\Project;
use App\Models\ProjectMilestone;
use App\Models\TenderProject;
use App\Models\User;
use App\Models\Prospect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $stats_type = 'this_month';
        $stats = $this->getStatsData($stats_type);
        $count = $stats['count'];
        $goal = $stats['goal'];

        $prospects = Prospect::orderBy('sale_date', 'desc')->take(10)->get();
        $projects = Project::orderBy('sale_date', 'desc')->take(7)->get();

        $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec'];
        $chartType = 'YearEarn';

        // Load full year chart data for the initial page load (YearEarn is default)
        $yearStats = $this->getYearlyChartData();
        $goal = array_merge($goal, $yearStats);

        $top_customers = Customer::with('projects')->get();
        $top_customers = $top_customers->sortByDesc(function ($customer) {
            return $customer->projects->sum('project_value');
        })->take(6);

        $top_performers = Goal::whereMonth('goals_date', date('m'))->whereYear('goals_date', date('Y'))->orderBy('goals_achieve', 'desc')->take(7)->get();

        return view('admin.dashboard')->with(compact('count', 'goal', 'prospects', 'projects', 'labels', 'stats_type', 'chartType', 'top_customers', 'top_performers'));
    }


    public function getTopStats(Request $request)
    {
        $stats_type = $request->type ?? 'this_month';
        $stats = $this->getStatsData($stats_type);
        $count = $stats['count'];
        $goal = $stats['goal'];
        $top_customers = Customer::with('projects')->get();
        $top_customers = $top_customers->sortByDesc(function ($customer) {
            return $customer->projects->sum('project_value');
        })->take(6);

        return response()->json([
            'html' => view('admin.dashboard_stats_cards', ['count' => $count, 'goal' => $goal, 'top_customers' => $top_customers, 'type' => $stats_type])->render(),
            'count' => $count
        ]);
    }

    private function getStatsData($type)
    {
        $startDate = null;
        $endDate = null;

        if ($type == 'today') {
            $startDate = date('Y-m-d');
            $endDate = date('Y-m-d');
        } elseif ($type == 'this_month') {
            $startDate = date('Y-m-01');
            $endDate = date('Y-m-t');
        } elseif ($type == 'this_year' || $type == 'yearEarn') {
            $startDate = date('Y-01-01');
            $endDate = date('Y-12-31');
        } elseif ($type == 'this_week' || $type == 'WeekEarn') {
            $startDate = date('Y-m-d', strtotime('last sunday'));
            $endDate = date('Y-m-d', strtotime('next saturday'));
        }
        // for 'overall', dates remain null

        $count['sales_managers'] = User::Role('SALES_MANAGER')->where('status', 1)->count();
        $count['account_managers'] = User::Role('ACCOUNT_MANAGER')->where('status', 1)->count();
        $count['sales_excecutive'] = User::Role('SALES_EXCUETIVE')->where('status', 1)->count();
        $count['bdm'] = User::Role('BUSINESS_DEVELOPMENT_MANAGER')->where('status', 1)->count();
        $count['tender_managers'] = User::Role('TENDER_USER')->where('status', 1)->count();

        $tpQuery = TenderProject::query();
        $pQuery = Project::query();
        $prQuery = Prospect::query();

        if ($startDate && $endDate) {
            $tpQuery->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
            $pQuery->whereBetween('sale_date', [$startDate, $endDate]);
            $prQuery->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        }

        $count['tender_projects'] = $tpQuery->count();
        $count['tender_projects_value'] = $tpQuery->sum('tender_value_lakhs');
        $count['projects'] = $pQuery->count();

        $count['prospects'] = (clone $prQuery)->count();
        $count['win'] = (clone $prQuery)->where('status', 'Win')->count();
        $count['follow_up'] = (clone $prQuery)->where('status', 'Follow Up')->count();
        $count['close'] = (clone $prQuery)->where('status', 'Close')->count();
        $count['sent_proposal'] = (clone $prQuery)->where('status', 'Sent Proposal')->count();

        // Achievements and Goals
        $account_manager_id = User::Role('ACCOUNT_MANAGER')->pluck('id');
        $bdma_manager_id = User::Role('BUSINESS_DEVELOPMENT_MANAGER')->pluck('id');
        $sales_manager_id = User::Role('SALES_MANAGER')->pluck('id');

        $count['account_manager_revenue'] = 0;
        foreach ($account_manager_id as $acc_m_id) {
            $achievements = \App\Helpers\Helper::getUserAchievementDateRange($acc_m_id, $startDate ?: '2000-01-01', $endDate ?: '2099-12-31');
            $count['account_manager_revenue'] += $achievements['net_amount'];
        }

        $count['bdm_revenue'] = 0;
        foreach ($bdma_manager_id as $bdm_id) {
            $achievements = \App\Helpers\Helper::getUserAchievementDateRange($bdm_id, $startDate ?: '2000-01-01', $endDate ?: '2099-12-31');
            $count['bdm_revenue'] += $achievements['gross_amount'];
        }

        $goalQuery = Goal::query();
        if ($startDate && $endDate) {
             // Goals are usually month-based in this system, so we check if goals_date falls in range
             $goalQuery->whereBetween('goals_date', [$startDate, $endDate]);
        }

        $count['account_manager_goals'] = (clone $goalQuery)->whereIn('user_id', $account_manager_id)->sum('goals_amount');
        $count['bdm_goals'] = (clone $goalQuery)->whereIn('user_id', $bdma_manager_id)->where('goals_type', 1)->sum('goals_amount');
        if ($count['bdm_goals'] == 0) {
            $count['bdm_goals'] = (clone $goalQuery)->whereIn('user_id', $bdma_manager_id)->sum('goals_amount');
        }

        $count['account_manager_percentage'] = ($count['account_manager_goals'] > 0) ? round(($count['account_manager_revenue'] / $count['account_manager_goals']) * 100) : 0;
        $count['bdm_percentage'] = ($count['bdm_goals'] > 0) ? round(($count['bdm_revenue'] / $count['bdm_goals']) * 100) : 0;

        $goal['gross_goals'] = (clone $goalQuery)->where('goals_type', 1)->whereIn('user_id', $sales_manager_id)->sum('goals_amount');
        $goal['net_goals'] = (clone $goalQuery)->where('goals_type', 2)->whereIn('user_id', $sales_manager_id)->sum('goals_amount');

        $goal['gross_goals_achieve'] = 0;
        $goal['net_goals_achieve'] = 0;
        foreach ($sales_manager_id as $sm_id) {
            $achievements = \App\Helpers\Helper::getUserAchievementDateRange($sm_id, $startDate ?: '2000-01-01', $endDate ?: '2099-12-31');
            $goal['gross_goals_achieve'] += $achievements['gross_amount'];
            $goal['net_goals_achieve'] += $achievements['net_amount'];
        }

        return ['count' => $count, 'goal' => $goal];
    }

    private function getYearlyChartData()
    {
        $sales_manager_id = User::Role('SALES_MANAGER')->pluck('id');
        $data = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthName = strtolower(date('F', mktime(0, 0, 0, $m, 1)));
            $startOfMonth = date('Y-m-01', mktime(0, 0, 0, $m, 1));
            $endOfMonth = date('Y-m-t', mktime(0, 0, 0, $m, 1));

            $gross_sum = 0;
            $net_sum = 0;

            foreach ($sales_manager_id as $sm_id) {
                $achievements = \App\Helpers\Helper::getUserAchievementDateRange($sm_id, $startOfMonth, $endOfMonth);
                $gross_sum += $achievements['gross_amount'];
                $net_sum += $achievements['net_amount'];
            }

            $data['gross_goals_' . $monthName] = $gross_sum;
            $data['net_goals_' . $monthName] = $net_sum;
            $data['prospect_' . $monthName] = Prospect::whereMonth('sale_date', $m)->whereYear('sale_date', date('Y'))->count();
        }
        return $data;
    }

    public function dashboardProspectFetch(Request $request)
    {
        if ($request->ajax()) {
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
        $sales_manager_id = User::Role('SALES_MANAGER')->pluck('id');

        if ($type == 'yearEarn') {
            $goal = $this->getYearlyChartData();
            $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec'];
            $type = 'YearEarn';
            return response()->json(['view' => (string)View::make('admin.statistic_ajax_bar_chart')->with(compact('labels', 'goal', 'type'))]);
        } elseif ($type == 'MonthEarn') {
            $current_month = date('m');
            $total_days = cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y'));
            $labels = [];
            $gross_goals = [];
            $net_goals = [];
            $prospects = [];

            for ($i = 1; $i <= $total_days; $i++) {
                $labels[] = $i;
                $date = date('Y-m-') . sprintf('%02d', $i);

                $gross_sum = 0;
                $net_sum = 0;
                foreach ($sales_manager_id as $sm_id) {
                    $achievements = \App\Helpers\Helper::getUserAchievementDateRange($sm_id, $date, $date);
                    $gross_sum += $achievements['gross_amount'];
                    $net_sum += $achievements['net_amount'];
                }
                $gross_goals[] = $gross_sum;
                $net_goals[] = $net_sum;
                $prospects[] = Prospect::whereDate('sale_date', $date)->count();
            }
            $type = 'MonthEarn';
            return response()->json(['view' => (string)View::make('admin.statistic_ajax_bar_chart')->with(compact('labels', 'gross_goals', 'type', 'net_goals', 'prospects'))]);
        } else {
            // WeekEarn
            $startOfWeek = Carbon::now()->startOfWeek();
            $labels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
            $gross_goals = [];
            $net_goals = [];
            $prospects = [];

            for ($i = 0; $i < 7; $i++) {
                $date = (clone $startOfWeek)->addDays($i)->format('Y-m-d');
                $gross_sum = 0;
                $net_sum = 0;
                foreach ($sales_manager_id as $sm_id) {
                    $achievements = \App\Helpers\Helper::getUserAchievementDateRange($sm_id, $date, $date);
                    $gross_sum += $achievements['gross_amount'];
                    $net_sum += $achievements['net_amount'];
                }
                $gross_goals[] = $gross_sum;
                $net_goals[] = $net_sum;
                $prospects[] = Prospect::whereDate('sale_date', $date)->count();
            }
            $type = 'WeekEarn';
            return response()->json(['view' => (string)View::make('admin.statistic_ajax_bar_chart')->with(compact('labels', 'gross_goals', 'type', 'net_goals', 'prospects'))]);
        }
    }

    public function topPerformerFilter(Request $request)
    {
        $duration = $request->duration;
        if ($duration == 'Monthly') {
            $top_performers = Goal::whereMonth('goals_date', date('m'))->whereYear('goals_date', date('Y'))->orderBy('goals_achieve', 'desc')->take(7)->get();
        } else {
            $top_performers = Goal::whereYear('goals_date', date('Y'))->orderBy('goals_achieve', 'desc')->take(7)->get();
        }
        return response()->json(['data' => view('admin.dashboard_performer_table', compact('top_performers'))->render()]);
    }
}
