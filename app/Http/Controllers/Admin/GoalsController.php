<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Goal;
use App\Models\Project;
use App\Models\Prospect;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Facade\Ignition\Exceptions\ViewException;
class GoalsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $goals = Goal::query();

        $text = Session::get('goals_filter_text');
        $month = Session::get('goals_filter_month');
        $year = Session::get('goals_filter_year');

        if ($request->duration) {
            [$startDate, $endDate] = \App\Helpers\Helper::getDateRangeByDuration($request->duration);
            if ($startDate && $endDate) {
                $goals->whereBetween('goals_date', [$startDate, $endDate]);
            }
        }

        if ($text) {
            $goals->where(function ($q) use ($text) {
                $q->where('goals_amount', 'LIKE', '%' . $text . '%')
                    ->orWhere('goals_achieve', 'LIKE', '%' . $text . '%')
                    ->orWhere('goals_date', 'LIKE', '%' . $text . '%')
                    ->orWhereHas('user', function ($query) use ($text) {
                        $query->where('name', 'LIKE', '%' . $text . '%');
                    });
                if (strcasecmp($text, 'Gross') == 0) {
                    $q->orWhere('goals_type', 1);
                } else if (strcasecmp($text, 'Net') == 0) {
                    $q->orWhere('goals_type', 2);
                }
            });
        }

        if ($month) {
            $goals->whereMonth('goals_date', $month);
        }

        if ($year) {
            $goals->whereYear('goals_date', $year);
        }

        $goals = $goals->selectRaw('MAX(id) as id, user_id, goals_date')
            ->groupBy('user_id', 'goals_date')
            ->orderBy('goals_date', 'desc')
            ->paginate(15);
        return view('admin.goals.list')->with(compact('goals', 'text', 'month', 'year'));
    }




    public function search(Request $request)
    {
        if ($request->ajax()) {
            $goals = Goal::query();

            Session::put('goals_filter_text', $request->text);
            Session::put('goals_filter_month', $request->month);
            Session::put('goals_filter_year', $request->year);

            // Text Search
            if ($request->filled('text')) {
                $text = $request->text;
                $goals->where(function ($q) use ($text) {
                    $q->where('goals_amount', 'LIKE', '%' . $text . '%')
                        ->orWhere('goals_achieve', 'LIKE', '%' . $text . '%')
                        ->orWhere('goals_date', 'LIKE', '%' . $text . '%')
                        ->orWhereHas('user', function ($query) use ($text) {
                            $query->where('name', 'LIKE', '%' . $text . '%');
                        });

                    if (strcasecmp($text, 'Gross') == 0) {
                        $q->orWhere('goals_type', 1);
                    } else if (strcasecmp($text, 'Net') == 0) {
                        $q->orWhere('goals_type', 2);
                    }
                });
            }

            // Month Filter
            if ($request->filled('month')) {
                $goals->whereMonth('goals_date', $request->month);
            }

            // Year Filter
            if ($request->filled('year')) {
                $goals->whereYear('goals_date', $request->year);
            }

            $goals = $goals->selectRaw('MAX(id) as id, user_id, goals_date')
                ->groupBy('user_id', 'goals_date')
                ->orderBy('goals_date', 'desc')
                ->paginate(15);
            return response()->json(['view' => (string)View::make('admin.goals.table')->with(compact('goals'))]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request->all();exit;
        if (!$request->id) {
            $count = Goal::where('user_id', $request->user_id)->whereMonth('goals_date', date('m', strtotime($request->goals_date)))->whereYear('goals_date', date('Y', strtotime($request->goals_date)))->count();
            if ($count > 0) {
                return redirect()->route('goals.index')->with('error', 'Goal already exists for this month.');
            }
        }
        //  check role by user id
        $user = User::find($request->user_id);

        // Block admin from directly creating goals for Sales Executives
        // Sales Executive goals are only distributed by their Sales Manager
        if ($user->hasRole('SALES_EXCUETIVE')) {
            return redirect()->route('goals.index')->with('error', 'Sales Executive goals can only be set by their Sales Manager through goal distribution.');
        }
        if ($user->hasRole('SALES_MANAGER') || $user->hasRole('BUSINESS_DEVELOPMENT_MANAGER')) {
            $projects = Project::where('user_id', $request->user_id)->whereMonth('sale_date', date('m', strtotime($request->goals_date)))->whereYear('sale_date', date('Y', strtotime($request->goals_date)))->get();
            if (count($projects) > 0) {
                $goals_achieve  = $projects->sum('project_value');
            } else {
                $goals_achieve = 0;
            }

            if (count($projects) > 0) {
                $goals_achieve_net  = $projects->sum('project_upfront');
            } else {
                $goals_achieve_net = 0;
            }
        }else if ($user->hasRole('SALES_EXCUETIVE')) {
            $prospects = Prospect::where(['user_id'=> $request->user_id, 'status' => 'Win'])->whereMonth('sale_date', date('m', strtotime($request->goals_date)))->whereYear('sale_date', date('Y', strtotime($request->goals_date)))->get();
            if (count($prospects) > 0) {
                $goals_achieve  = $prospects->sum('price_quote');
            } else {
                $goals_achieve = 0;
            }

            if (count($prospects) > 0) {
                $goals_achieve_net  = $prospects->sum('upfront_value');
            } else {
                $goals_achieve_net = 0;
            }

        } else {
            $projects = Project::where('assigned_to', $request->user_id)->get();
            if (count($projects) > 0) {
                $goals_achieve = 0;
                foreach ($projects as $key => $value) {
                    $goals_achieve += $value->projectMilestones()->where(['payment_status' => 'Paid'])->whereMonth('payment_date', date('m', strtotime($request->goals_date)))->whereYear('payment_date', date('Y', strtotime($request->goals_date)))->sum('milestone_value');
                }
                // return 'f';
            } else {
                $goals_achieve = 0;
            }
        }
        // return $goals_achieve;
        if ($request->id) {
            $goal = Goal::find($request->id);
            $message = 'Goal updated successfully.';

            if ($user->hasRole('SALES_MANAGER') || $user->hasRole('SALES_EXCUETIVE') || $user->hasRole('BUSINESS_DEVELOPMENT_MANAGER')) {
                $goal->user_id = $request->user_id;
                $goal->goals_date = $request->goals_date;
                $goal->goals_amount = $request->goals_amount;
                $goal->goals_achieve = $goals_achieve;
                $goal->save();

                $net_goals = Goal::where('user_id', $request->user_id)->where('goals_type', 2)->whereMonth('goals_date', date('m', strtotime($request->goals_date)))->whereYear('goals_date', date('Y', strtotime($request->goals_date)))->first();
                if ($net_goals) {
                    $net_goals->user_id = $request->user_id;
                    $net_goals->goals_date = $request->goals_date;
                    // 25% of goals_amount
                    $net_goals->goals_amount = $request->goals_amount * 40 / 100;
                    $net_goals->goals_achieve = $goals_achieve_net;
                    $net_goals->save();
                }
            } else {
                $goal->user_id = $request->user_id;
                $goal->goals_date = $request->goals_date;
                $goal->goals_amount = $request->goals_amount;
                $goal->goals_achieve = $goals_achieve;
                $goal->save();
            }
        } else {
            $goal = new Goal();
            $message = 'Goal added successfully.';

            if ($user->hasRole('SALES_MANAGER') || $user->hasRole('SALES_EXCUETIVE') || $user->hasRole('BUSINESS_DEVELOPMENT_MANAGER')) {
                $goal->user_id = $request->user_id;
                $goal->goals_date = $request->goals_date;
                $goal->goals_amount = $request->goals_amount;
                $goal->goals_achieve = $goals_achieve;
                $goal->goals_type = 1;
                $goal->save();

                $net_goals = new Goal();
                $net_goals->user_id = $request->user_id;
                $net_goals->goals_date = $request->goals_date;
                $net_goals->goals_amount = $request->goals_amount * 40 / 100;
                $net_goals->goals_achieve = $goals_achieve_net;
                $net_goals->goals_type = 2;
                $net_goals->save();
            } else {
                $goal->user_id = $request->user_id;
                $goal->goals_date = $request->goals_date;
                $goal->goals_amount = $request->goals_amount;
                $goal->goals_achieve = $goals_achieve;
                $goal->goals_type = 2;
                $goal->save();
            }
        }


        return redirect()->route('goals.index')->with('message', $message);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        if ($request->ajax()) {
            // return $request->all();
            $goal = Goal::find($id);
            $users = User::role($request->role)->orderBy('name', 'asc')->get();
            if ($goal) {
                return response()->json(['status' => 'success', 'data' => $goal, 'users' => $users]);
            } else {
                return response()->json(['status' => 'error', 'message' => 'Goal not found.']);
            }
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function delete($id)
    {
        $goal = Goal::find($id);
        if ($goal) {
            $relatedGoals = Goal::where('user_id', $goal->user_id)
                ->whereMonth('goals_date', date('m', strtotime($goal->goals_date)))
                ->whereYear('goals_date', date('Y', strtotime($goal->goals_date)))
                ->get();
            foreach ($relatedGoals as $r) {
                $r->delete();
            }
            return redirect()->route('goals.index')->with('message', 'Goal deleted successfully.');
        } else {
            return redirect()->route('goals.index')->with('error', 'Goal not found.');
        }
    }

    public function getUser(Request $request)
    {
        $user = User::find($request->user_id);
        if ($user->hasRole('SALES_MANAGER')) {
            $role = 'SALES_MANAGER';
        } else {
            $role = 'ACCOUNT_MANAGER';
        }
        return response()->json(['role' => $role]);
    }

    public function getUserByType(Request $request)
    {
        if ($request->ajax()) {
            $user_type = $request->user_type;
            $users = User::role($user_type)->orderBy('name', 'asc')->get();
            if ($users) {
                return response()->json(['status' => true, 'users' => $users]);
            } else {
                return response()->json(['status' => false, 'message' => 'User not found.']);
            }
        }
    }

    /**
     * Get all Sales Managers who have goals set by admin
     */
    public function getSalesManagers(Request $request)
    {
        $salesManagers = User::role('SALES_MANAGER')->where('status', 1)->orderBy('name', 'asc')->get(['id', 'name']);
        return response()->json(['status' => true, 'sales_managers' => $salesManagers]);
    }

    /**
     * Get available months for a Sales Manager and distribution data
     */
    public function getDistribution(Request $request)
    {
        $salesManagerId = $request->sales_manager_id;
        $goalsDate = $request->goals_date;
        $month = date('m', strtotime($goalsDate));
        $year = date('Y', strtotime($goalsDate));

        $smGoal = Goal::where('user_id', $salesManagerId)
            ->whereMonth('goals_date', $month)
            ->whereYear('goals_date', $year)
            ->where('goals_type', 1)
            ->first();

        if (!$smGoal) {
            return response()->json(['status' => false, 'message' => 'No goal set for this Sales Manager for this month.']);
        }

        $salesExecs = User::role('SALES_EXCUETIVE')
            ->where('sales_manager_id', $salesManagerId)
            ->where('status', 1)
            ->get();

        if ($salesExecs->count() == 0) {
            return response()->json(['status' => false, 'message' => 'No Sales Executives found under this Sales Manager.']);
        }

        // Find already allocated amounts
        $alreadyAllocated = [];
        foreach ($salesExecs as $exec) {
            $execGoal = Goal::where('user_id', $exec->id)
                ->whereMonth('goals_date', $month)
                ->whereYear('goals_date', $year)
                ->where('goals_type', 1)
                ->first();
            if ($execGoal) {
                $alreadyAllocated[$exec->id] = $execGoal->goals_amount;
            }
        }

        $defaultAmount = 0;
        if (count($alreadyAllocated) == 0) {
            $defaultAmount = round($smGoal->goals_amount / $salesExecs->count(), 2);
        }

        $execData = [];
        foreach ($salesExecs as $exec) {
            $execData[] = [
                'id' => $exec->id,
                'name' => $exec->name,
                'amount' => isset($alreadyAllocated[$exec->id]) ? $alreadyAllocated[$exec->id] : $defaultAmount,
            ];
        }

        return response()->json([
            'status' => true,
            'total_amount' => $smGoal->goals_amount,
            'executives' => $execData,
        ]);
    }

    /**
     * Store distributed goals for Sales Executives from admin panel
     */
    public function storeDistribution(Request $request)
    {
        $request->validate([
            'sales_manager_id' => 'required',
            'goals_date' => 'required',
            'amount' => 'required|array',
        ]);

        $salesManagerId = $request->sales_manager_id;
        $goalsDate = $request->goals_date;
        $month = date('m', strtotime($goalsDate));
        $year = date('Y', strtotime($goalsDate));

        $smGoal = Goal::where('user_id', $salesManagerId)
            ->whereMonth('goals_date', $month)
            ->whereYear('goals_date', $year)
            ->where('goals_type', 1)
            ->first();

        if (!$smGoal) {
            return redirect()->route('goals.index')->with('error', 'No goal set for this Sales Manager for this month.');
        }

        $amounts = $request->amount;
        $totalInput = array_sum($amounts);

        /*
        if ($totalInput > ($smGoal->goals_amount + 0.5)) {
            return redirect()->route('goals.index')->with('error', 'Distributed amount exceeds the Sales Manager total goal.');
        }
        */

        foreach ($amounts as $userId => $amount) {
            $exec = User::role('SALES_EXCUETIVE')->where('sales_manager_id', $salesManagerId)->where('id', $userId)->first();
            if (!$exec) continue;

            $prospects = Prospect::where(['user_id' => $exec->id, 'status' => 'Win'])
                ->whereMonth('sale_date', $month)
                ->whereYear('sale_date', $year)
                ->get();

            $goals_achieve = $prospects->count() > 0 ? $prospects->sum('price_quote') : 0;
            $goals_achieve_net = $prospects->count() > 0 ? $prospects->sum('upfront_value') : 0;

            // Gross goal
            $execGrossGoal = Goal::where('user_id', $exec->id)
                ->whereMonth('goals_date', $month)
                ->whereYear('goals_date', $year)
                ->where('goals_type', 1)
                ->first();

            if (!$execGrossGoal) {
                $execGrossGoal = new Goal();
                $execGrossGoal->user_id = $exec->id;
                $execGrossGoal->goals_date = $goalsDate;
                $execGrossGoal->goals_type = 1;
            }
            $execGrossGoal->goals_achieve = $goals_achieve;
            $execGrossGoal->goals_amount = $amount;
            $execGrossGoal->save();

            // Net goal
            $execNetGoal = Goal::where('user_id', $exec->id)
                ->whereMonth('goals_date', $month)
                ->whereYear('goals_date', $year)
                ->where('goals_type', 2)
                ->first();

            if (!$execNetGoal) {
                $execNetGoal = new Goal();
                $execNetGoal->user_id = $exec->id;
                $execNetGoal->goals_date = $goalsDate;
                $execNetGoal->goals_type = 2;
            }
            $execNetGoal->goals_achieve = $goals_achieve_net;
            $execNetGoal->goals_amount = $amount * 0.40;
            $execNetGoal->save();
        }

        return redirect()->route('goals.index')->with('message', 'Goals distributed successfully to Sales Executives.');
    }
}
