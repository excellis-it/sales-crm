<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Goal;
use App\Models\Project;
use App\Models\Prospect;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Facade\Ignition\Exceptions\ViewException;
class GoalsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $goals = Goal::orderBy('goals_date', 'desc')->paginate(15);
        return view('admin.goals.list')->with(compact('goals'));
    }

    // public function goalsList(Request $request)
    // {
    //     $draw = $request->get('draw');
    //     $start = $request->get("start");
    //     $rowperpage = $request->get("length"); // Rows display per page

    //     $columnIndex_arr = $request->get('order');
    //     $columnName_arr = $request->get('columns');
    //     $order_arr = $request->get('order');
    //     $search_arr = $request->get('search');

    //     $columnIndex = $columnIndex_arr[0]['column']; // Column index
    //     $columnName = $columnName_arr[$columnIndex]['data']; // Column name
    //     $columnSortOrder = $order_arr[0]['dir']; // asc or desc
    //     $searchValue = $search_arr['value']; // Search value

    //     // Total records
    //     $totalRecords = Goal::orderBy('goals_date', 'asc')->count();
    //     $totalRecordswithFilter = Goal::orderBy('goals_date', 'asc')->count();

    //     // Fetch records
    //     $records = Goal::query();
    //     $columns = ['goals_date','goals_type','user_id','goals_amount','goals_achieve'];
    //     foreach($columns as $column){
    //         $records->where($column, 'like', '%' . $searchValue . '%');
    //     }
    //     $records->orderBy($columnName,$columnSortOrder);
    //     $records->skip($start);
    //     $records->take($rowperpage);
    //     $records = $records->orderBy('goals_date', 'asc');
    //     $records = $records->get();

    //     $data_arr = array();

    //     foreach($records as $record){
    //         $goals_date = date('M ,Y', strtotime($record->goals_date));
    //         $goals_type = $record->goals_type == 1 ? 'Gross' : 'Net';
    //         $user_id = User::find($record->user_id)->name;
    //         $goals_amount = $record->goals_amount;
    //         $goals_achieve = $record->goals_achieve ?? 0;
    //         $id = $record->id;


    //         if ($record->goals_type == 1) {
    //             $action = '<a href="javascript:void(0);"
    //             data-route="'.route('goals.edit', $record->id).'"
    //             data-role="SALES_MANAGER" class="edit-data"><i
    //                 class="fas fa-edit btn btn-sm btn-primary"></i> </a> &nbsp;  <a title="Delete Project"
    //                 data-route="'.route('goals.delete', $record->id).'"
    //                 href="javascipt:void(0);" id="delete"><i
    //                     class="fas fa-trash btn btn-sm btn-danger"></i></a>';
    //         } else {
    //             // only delete
    //             $action = '<a title="Delete Project"
    //             data-route="'.route('goals.delete', $record->id).'"
    //             href="javascipt:void(0);" id="delete"><i
    //                 class="fas fa-trash btn btn-sm btn-danger"></i></a>';
    //         }

    //        $data_arr[] = array(
    //            "goals_date" => $goals_date,
    //            "goals_type" => $goals_type,
    //            "user_id" => $user_id,
    //            "goals_amount" => $goals_amount,
    //            "goals_achieve" => $goals_achieve,
    //            "action" => $action,
    //        );
    //     }




    //     $response = array(
    //        "draw" => intval($draw),
    //        "iTotalRecords" => $totalRecords,
    //        "iTotalDisplayRecords" => $totalRecordswithFilter,
    //        "aaData" => $data_arr
    //     );

    //     return response()->json($response);
    // }

    public function search(Request $request)
    {
        
        if ($request->ajax()) {
            $goals = Goal::query();
                $columns = ['goals_date','goals_type','goals_amount', 'goals_achieve'];
                foreach ($columns as $column) {
                    $goals->orWhere($column, 'LIKE', '%' . $request->text . '%');
                }

            $goals = $goals->orderBy('goals_date', 'desc')->paginate(15);
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
        if (!$request->id) {
            $count = Goal::where('user_id', $request->user_id)->whereMonth('goals_date', date('m', strtotime($request->goals_date)))->whereYear('goals_date', date('Y', strtotime($request->goals_date)))->where('goals_type', $request->goals_type)->count();
            if ($count > 0) {
                return redirect()->route('goals.index')->with('error', 'Goal already exists for this month.');
            }
        }
        //  check role by user id
        $user = User::find($request->user_id);
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
                    $net_goals->goals_amount = $request->goals_amount * 25 / 100;
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
                $net_goals->goals_amount = $request->goals_amount * 25 / 100;
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
            $goal->delete();
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
}
