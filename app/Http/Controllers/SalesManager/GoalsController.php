<?php

namespace App\Http\Controllers\SalesManager;

use App\Http\Controllers\Controller;
use App\Models\Goal;
use App\Models\Prospect;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class GoalsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $salesManagerId = Auth::user()->id;

        // Fetch goals set by admin for this sales manager
        $available_months = Goal::where('user_id', $salesManagerId)
            ->where('goals_type', 1)
            ->orderBy('goals_date', 'desc')
            ->get(['goals_date', 'goals_amount'])
            ->unique('goals_date');

        $goals = Goal::whereHas('user', function ($query) use ($salesManagerId) {
            $query->where('sales_manager_id', $salesManagerId)->whereHas('roles', function($q2){
                $q2->where('name', 'SALES_EXCUETIVE');
            });
        })->orderBy('goals_date', 'desc')->paginate(15);

        return view('sales_manager.goals.list')->with(compact('goals', 'available_months'));
    }

    public function search(Request $request)
    {
        if ($request->ajax()) {
            $salesManagerId = Auth::user()->id;
            $goals = Goal::whereHas('user', function ($query) use ($salesManagerId) {
                $query->where('sales_manager_id', $salesManagerId)->whereHas('roles', function ($q2) {
                    $q2->where('name', 'SALES_EXCUETIVE');
                });
            });

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

            $goals = $goals->orderBy('goals_date', 'desc')->paginate(15);
            return response()->json(['view' => (string)View::make('sales_manager.goals.table')->with(compact('goals'))]);
        }
    }

    public function getDistribution(Request $request)
    {
        $salesManagerId = Auth::user()->id;
        $goalsDate = $request->goals_date;
        $month = date('m', strtotime($goalsDate));
        $year = date('Y', strtotime($goalsDate));

        $smGoal = Goal::where('user_id', $salesManagerId)
            ->whereMonth('goals_date', $month)
            ->whereYear('goals_date', $year)
            ->where('goals_type', 1)
            ->first();

        if (!$smGoal) {
            return response()->json(['status' => false, 'message' => 'No goal set for this month.']);
        }

        $salesExecs = User::role('SALES_EXCUETIVE')
            ->where('sales_manager_id', $salesManagerId)
            ->where('status', 1) // assuming status is tracked
            ->get();

        if ($salesExecs->count() == 0) {
            return response()->json(['status' => false, 'message' => 'No Sales Executives found.']);
        }

        $html = '<form action="' . route('sales-manager.goals.store') . '" method="post" id="distributionForm">';
        $html .= csrf_field();
        $html .= '<input type="hidden" name="goals_date" value="' . $goalsDate . '">';
        $html .= '<div class="d-flex justify-content-between p-3 mb-3 align-items-center rounded border">
                    <div style="font-weight: bold; font-size: 16px;">Total Goal: $<span id="total_sm_goal">' . $smGoal->goals_amount . '</span></div>
                    <div style="font-weight: bold; font-size: 16px;"><span id="allocation_label">Remaining to Allocate</span>: $<span id="remaining_goal">0.00</span></div>
                  </div>';
        $html .= '<div class="table-responsive"><table class="table table-hover table-center mb-4">';
        $html .= '<thead><tr><th>Sales Executive</th><th>Allocated Target Amount ($)</th></tr></thead>';
        $html .= '<tbody>';

        // Find if already distributed
        $alreadyAllocated = [];
        foreach($salesExecs as $exec) {
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
        if(count($alreadyAllocated) == 0){
             $defaultAmount = round($smGoal->goals_amount / $salesExecs->count(), 2);
        }

        foreach ($salesExecs as $exec) {
            $amount = isset($alreadyAllocated[$exec->id]) ? $alreadyAllocated[$exec->id] : $defaultAmount;
            $html .= '<tr>';
            $html .= '<td class="align-middle">' . $exec->name . '</td>';
            $html .= '<td><input type="number" step="0.01" name="amount[' . $exec->id . ']" class="form-control exec-amount" value="' . $amount . '" required></td>';
            $html .= '</tr>';
        }

        $html .= '</tbody></table></div>';
        $html .= '<div class="text-end"><button type="submit" class="btn px-4 submit-btn" id="submitDistribution">Save Distribution</button></div>';
        $html .= '</form>';

        return response()->json([
             'status' => true,
             'html' => $html,
             'total_amount' => $smGoal->goals_amount
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'goals_date' => 'required',
            'amount' => 'required|array',
        ]);

        $salesManagerId = Auth::user()->id;
        $goalsDate = $request->goals_date;
        $month = date('m', strtotime($goalsDate));
        $year = date('Y', strtotime($goalsDate));

        $smGoal = Goal::where('user_id', $salesManagerId)
            ->whereMonth('goals_date', $month)
            ->whereYear('goals_date', $year)
            ->where('goals_type', 1)
            ->first();

        if (!$smGoal) {
            return redirect()->route('sales-manager.goals.index')->with('error', 'Admin has not set a monthly goal for you for this month.');
        }

        $amounts = $request->amount;
        $totalInput = array_sum($amounts);

        // Let's add a small leeway for floating point rounding issues
        /* 
        if ($totalInput > ($smGoal->goals_amount + 0.5)) {
            return redirect()->route('sales-manager.goals.index')->with('error', 'Distributed amount exceeds your total goal.');
        }
        */

        foreach ($amounts as $userId => $amount) {
            $exec = User::role('SALES_EXCUETIVE')->where('sales_manager_id', $salesManagerId)->where('id', $userId)->first();
            if (!$exec) continue;

            $prospects = Prospect::where(['user_id'=> $exec->id, 'status' => 'Win'])
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
            $execNetGoal->goals_amount = $amount * 0.25;
            $execNetGoal->save();
        }

        return redirect()->route('sales-manager.goals.index')->with('message', 'Goals distributed successfully to the team.');
    }
}
