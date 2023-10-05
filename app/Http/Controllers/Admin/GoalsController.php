<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Goal;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;

class GoalsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $salesManagerGoals = Goal::with('user')->whereHas('user', function ($query) {
            // where Role SALES_MANAGER
            $query->whereHas('roles', function ($query) {
                $query->where('name', 'SALES_MANAGER');
            });
        })->orderBy('goals_date', 'desc')->get();

        $accountManagerGoals = Goal::with('user')->whereHas('user', function ($query) {
            $query->whereHas('roles', function ($query) {
                $query->where('name', 'ACCOUNT_MANAGER');
            });
        })->orderBy('goals_date', 'desc')->get();
        $users = User::role(['SALES_MANAGER', 'ACCOUNT_MANAGER'])->orderBy('name', 'asc')->get();
        return view('admin.goals.list')->with(compact('salesManagerGoals', 'accountManagerGoals', 'users'));
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
        if (!$request->id) {
            $count = Goal::where('user_id', $request->user_id)->whereMonth('goals_date', date('m', strtotime($request->goals_date)))->whereYear('goals_date', date('Y', strtotime($request->goals_date)))->where('goals_type', $request->goals_type)->count();
            if ($count > 0) {
                return redirect()->route('goals.index')->with('error', 'Goal already exists for this month.');
            }
        }
        //  check role by user id
        $user = User::find($request->user_id);
        if ($user->hasRole('SALES_MANAGER')) {
            if ($request->goals_type == 1) {
                $projects = Project::where('user_id', $request->user_id)->whereMonth('sale_date', date('m', strtotime($request->goals_date)))->whereYear('sale_date', date('Y', strtotime($request->goals_date)))->get();
                if (count($projects) > 0) {
                    $goals_achieve  = $projects->sum('project_value');
                } else {
                    $goals_achieve = 0;
                }
            } else if ($request->goals_type == 2) {
                $projects = Project::where('user_id', $request->user_id)->whereMonth('sale_date', date('m', strtotime($request->goals_date)))->whereYear('sale_date', date('Y', strtotime($request->goals_date)))->get();
                if (count($projects) > 0) {
                    $goals_achieve  = $projects->sum('project_upfront');
                } else {
                    $goals_achieve = 0;
                }
            }
        } else {
            $projects = Project::where('assigned_to', $request->user_id)->get();
            if (count($projects) > 0) {
                $goals_achieve = 0;
                foreach ($projects as $key => $value) {
                    $goals_achieve += $value->projectMilestones()->where(['payment_status' => 'Paid'])->whereMonth('payment_date', date('m', strtotime($request->goals_date)))->whereYear('payment_date', date('Y', strtotime($request->goals_date)))->sum('milestone_value');
                }
            } else {
                $goals_achieve = 0;
            }
        }
        if ($request->id) {
            $goal = Goal::find($request->id);
            $message = 'Goal updated successfully.';
        } else {
            $goal = new Goal();
            $message = 'Goal added successfully.';
        }
        $goal->user_id = $request->user_id;
        $goal->goals_date = $request->goals_date;
        $goal->goals_amount = $request->goals_amount;
        $goal->goals_achieve = $goals_achieve;
        $goal->goals_type = $request->goals_type;
        $goal->save();

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
            $goal->delete();
            return redirect()->route('goals.index')->with('success', 'Goal deleted successfully.');
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
}
