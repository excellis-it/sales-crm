<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Followup;
use App\Models\Goal;
use App\Models\Project;
use App\Models\ProjectType;
use Illuminate\Http\Request;
use App\Models\Prospect;
use App\Models\ProjectMilestone;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

class ProspectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = User::role(['SALES_MANAGER', 'ACCOUNT_MANAGER', 'SALES_EXCUETIVE'])->orderBy('id', 'desc')->get();
        $sales_executives = User::role(['SALES_EXCUETIVE'])->where(['status' => 1])->orderBy('id', 'desc')->get();
        // dd($request->user_id);
        $prospects = Prospect::orderBy('id', 'desc');

        // When coming from user list or dashboard, clear stale session filters
        if ($request->has('user_id') || $request->has('duration')) {
            Session::forget('admin_prospect_filter_start_date');
            Session::forget('admin_prospect_filter_end_date');
            Session::forget('admin_prospect_filter_search');
            Session::forget('admin_prospect_filter_status');
            Session::forget('admin_prospect_filter_user_id');
        }

        $startDate = Session::get('admin_prospect_filter_start_date', $request->start_date);
        $endDate = Session::get('admin_prospect_filter_end_date', $request->end_date);
        $search = Session::get('admin_prospect_filter_search');
        $user_id_filter = Session::get('admin_prospect_filter_user_id', $request->user_id);
        $status_filter = Session::get('admin_prospect_filter_status', $request->status);

        if ($request->duration) {
            [$startDate, $endDate] = \App\Helpers\Helper::getDateRangeByDuration($request->duration);
            if ($startDate && $endDate) {
                Session::put('admin_prospect_filter_start_date', $startDate);
                Session::put('admin_prospect_filter_end_date', $endDate);
                if ($request->status) {
                    Session::put('admin_prospect_filter_status', $request->status);
                    $status_filter = $request->status;
                }
                $prospects->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
            }
        } elseif ($startDate && $endDate) {
            $prospects->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        }

        if ($search) {
            $query = str_replace(" ", "%", $search);
            $prospects->where(function ($q) use ($query) {
                $q->orWhere('client_name', 'like', '%' . $query . '%')
                    ->orWhere('business_name', 'like', '%' . $query . '%')
                    ->orWhere('client_email', 'like', '%' . $query . '%')
                    ->orWhere('client_phone', 'like', '%' . $query . '%')
                    ->orWhere('price_quote', 'like', '%' . $query . '%')
                    ->orWhere('followup_date', 'like', '%' . $query . '%')
                    ->orWhere('offered_for', 'like', '%' . $query . '%')
                    ->orWhereHas('user', function ($q) use ($query) {
                        $q->where('name', 'like', '%' . $query . '%');
                    })
                    ->orWhereHas('transferTakenBy', function ($q) use ($query) {
                        $q->where('name', 'like', '%' . $query . '%');
                    });
            });
        }

        if ($user_id_filter) {
            $prospects->where('user_id', $user_id_filter);
        }

        if ($status_filter && $status_filter != 'All') {
            $prospects->where('status', $status_filter);
        }

        $count['win'] = (clone $prospects)->where('status', 'Win')->count();
        $count['follow_up'] = (clone $prospects)->where('status', 'Follow Up')->count();
        $count['close'] = (clone $prospects)->where('status', 'Close')->count();
        $count['sent_proposal'] = (clone $prospects)->where('status', 'Sent Proposal')->count();
        $count['prospect'] = (clone $prospects)->count();

        $prospects = $prospects->paginate('10');

        return view('admin.prospect.list', compact('count', 'prospects', 'users', 'sales_executives', 'startDate', 'endDate', 'search', 'user_id_filter', 'status_filter'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::role(['SALES_MANAGER', 'ACCOUNT_MANAGER', 'SALES_EXCUETIVE'])->orderBy('id', 'desc')->get();
        $sales_executives = User::role(['SALES_EXCUETIVE'])->where(['status' => 1])->orderBy('id', 'desc')->get();
        return view('admin.prospect.create')->with(compact('sales_executives', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // try {
        $data = $request->all();
        $getUser = User::where(['id' => $data['user_id']])->first();
        $prospect = new Prospect();
        $prospect->user_id = $data['user_id'];
        if ($getUser->hasRole('SALES_EXCUETIVE')) {
            $prospect->report_to = $getUser['sales_manager_id'];
        } else {
            $prospect->report_to = $getUser['bdm_id'];
        }
        $prospect->client_name = $data['client_name'];
        $prospect->business_name = $data['business_name'];
        $prospect->client_email = $data['client_email'];
        $prospect->client_phone = $data['client_phone'];
        $prospect->business_address = $data['business_address'];
        $prospect->website = $data['website'];
        $prospect->status = $data['status'];
        $prospect->followup_date = $data['followup_date'];
        $prospect->followup_time = $data['followup_time'];
        $prospect->sale_date = $data['sale_date'] ?? '';
        $prospect->upfront_value = $data['upfront_value'] ?? '';
        $prospect->comments = $data['comments'];
        $prospect->price_quote = $data['price_quote'];
        if ($data['offered_for'] == 'Other') {
            $prospect->offered_for = $data['other_value'];
        } else {
            $prospect->offered_for = $data['offered_for'];
        }
        $prospect->transfer_token_by = $data['transfer_token_by'];
        $prospect->save();

        if ($data['comments']) {
            $follow_up = new Followup();
            $follow_up->user_id = Auth::user()->id;
            $follow_up->prospect_id = $prospect->id;
            $follow_up->followup_type = 'other';
            $follow_up->followup_description = $data['comments'];
            $follow_up->save();
        }

        if ($request->status == 'Win') {
            $prospect = Prospect::findOrFail($prospect->id);
            $prospect->is_project = true;
            $prospect->save();
            $user = User::where(['id' => $prospect->user_id])->first();
            //sales executive goal
            $net_goal = Goal::where(['user_id' => $user->sales_manager_id, 'goals_type' => 1])->whereMonth('goals_date', date('m', strtotime($prospect->sale_date)))->whereYear('goals_date', date('Y', strtotime($prospect->sale_date)))->first();
            if ($net_goal) {
                $net_goal->goals_achieve = $net_goal->goals_achieve + $prospect->upfront_value;
                $net_goal->save();
            }
            $gross_goal = Goal::where(['user_id' => $user->sales_manager_id, 'goals_type' => 2])->whereMonth('goals_date', date('m', strtotime($prospect->sale_date)))->whereYear('goals_date', date('Y', strtotime($prospect->sale_date)))->first();
            if ($gross_goal) {
                $gross_goal->goals_achieve = $gross_goal->goals_achieve + $prospect->price_quote;
                $gross_goal->save();
            }

            //sales manager goal
            $net_goal = Goal::where(['user_id' => $user->report_to, 'goals_type' => 1])->whereMonth('goals_date', date('m', strtotime($prospect->sale_date)))->whereYear('goals_date', date('Y', strtotime($prospect->sale_date)))->first();
            if ($net_goal) {
                $net_goal->goals_achieve = $net_goal->goals_achieve + $prospect->upfront_value;
                $net_goal->save();
            }

            $gross_goal = Goal::where(['user_id' => $user->report_to, 'goals_type' => 2])->whereMonth('goals_date', date('m', strtotime($prospect->sale_date)))->whereYear('goals_date', date('Y', strtotime($prospect->sale_date)))->first();
            if ($gross_goal) {
                $gross_goal->goals_achieve = $gross_goal->goals_achieve + $prospect->price_quote;
                $gross_goal->save();
            }

            $project = new Project();
            $project->user_id = $prospect->report_to;
            $project->client_name = $prospect->client_name;
            $project->business_name = $prospect->business_name;
            $project->client_email = $prospect->client_email;
            $project->client_phone = $prospect->client_phone;
            $project->client_address = $prospect->business_address;
            $project->project_value = $prospect->price_quote;
            $project->currency = 'USD'; // default currency 'USD
            $project->payment_mode = '';
            $project->project_opener = $user->id;
            $project->project_closer = '';
            $project->project_upfront = $prospect->upfront_value;
            $project->website = $prospect->website;
            $project->sale_date = $prospect->sale_date ?? '';
            $project->comment = $prospect->comments;
            $project->save();

            if ($prospect->upfront_value > 0) {
                $upfront = ProjectMilestone::where('project_id', $project->id)->where('milestone_type', 'upfront')->first();
                if ($upfront) {
                    $upfront->milestone_value = $prospect->upfront_value;
                    $upfront->save();
                } else {
                    $upfront = new ProjectMilestone();
                    $upfront->project_id = $project->id;
                    $upfront->milestone_name = 'Upfront';
                    $upfront->milestone_type = 'upfront';
                    $upfront->milestone_value = $prospect->upfront_value;
                    $upfront->payment_status = 'Paid';
                    $upfront->payment_mode = $data['payment_mode'] ?? null;
                    $upfront->payment_date = date('Y-m-d');
                    $upfront->save();
                }
            }

            //project milestone
            if (isset($data['milestone_name'])) {
                foreach ($data['milestone_name'] as $key => $milestone) {
                    //check if data is null
                    if ($data['milestone_name'][$key] != null) {
                        $project_milestone = new ProjectMilestone();
                        $project_milestone->project_id = $project->id;
                        $project_milestone->milestone_name = $milestone;
                        $project_milestone->milestone_value = $data['milestone_value'][$key];
                        $project_milestone->payment_status = 'Due';
                        // $project_milestone->payment_date = ($data['payment_status'][$key] == 'Paid') ? date('Y-m-d') : '';
                        $project_milestone->milestone_comment = $data['milestone_comment'][$key];
                        // $project_milestone->payment_mode = $data['milestone_payment_mode'][$key];
                        // $project_milestone->payment_date = $data['milestone_payment_date'][$key];
                        $project_milestone->save();
                    }
                }
            }

            $project_type = new ProjectType();
            $project_type->project_id = $project->id;
            $project_type->type = $prospect->offered_for;
            if (
                $prospect->offered_for != 'SMO' &&
                $prospect->offered_for != 'SEO' &&
                $prospect->offered_for != 'Logo Design' &&
                $prospect->offered_for != 'Digital Marketing & SEO' &&
                $prospect->offered_for != 'Mobile Application Development' &&
                $prospect->offered_for != 'Website Design & Development'
            ) {
                $project_type->name = 'Other';
            } else {
                $project_type->name = $prospect->offered_for;
            }

            $project_type->save();
        }

        return redirect()->route('admin.prospects.index')->with('message', 'Prospect created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $prospect = Prospect::find($id);
            $isThat = 'view';
            return response()->json(['view' => (string)View::make('admin.prospect.show-details')->with(compact('prospect', 'isThat'))]);
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $users = User::role(['SALES_MANAGER', 'ACCOUNT_MANAGER', 'SALES_EXCUETIVE'])->orderBy('id', 'desc')->get();
            $prospect = Prospect::find($id);
            $sales_executives = User::role(['SALES_EXCUETIVE'])->where(['status' => 1])->orderBy('id', 'desc')->get();
            $type = true;
            return response()->json(['view' => view('admin.prospect.edit', compact('prospect', 'users', 'sales_executives', 'type'))->render()]);
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
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
        $data = $request->all();
        $getUser = User::where(['id' => $data['user_id']])->first();
        $prospect = Prospect::findOrfail($id);
        $prospect->user_id = $data['user_id'];
        if ($getUser->hasRole('SALES_EXCUETIVE')) {
            $prospect->report_to = $getUser['sales_manager_id'];
        } else {
            $prospect->report_to = $getUser['bdm_id'];
        }
        $prospect->client_name = $data['client_name'];
        $prospect->business_name = $data['business_name'];
        $prospect->client_email = $data['client_email'];
        $prospect->client_phone = $data['client_phone'];
        $prospect->business_address = $data['business_address'];
        $prospect->website = $data['website'];
        $prospect->status = $data['status'];
        $prospect->followup_date = $data['followup_date'];
        $prospect->followup_time = $data['followup_time'];
        $prospect->sale_date = $data['sale_date'] ?? '';
        $prospect->upfront_value = $data['upfront_value'] ?? '';
        // $prospect->comments = $data['comments'];
        $prospect->price_quote = $data['price_quote'];
        if ($data['offered_for'] == 'Other') {
            $prospect->offered_for = $data['other_value'];
        } else {
            $prospect->offered_for = $data['offered_for'];
        }
        $prospect->transfer_token_by = $data['transfer_token_by'];
        $prospect->save();

        if ($request->status == 'Win') {
            $prospect = Prospect::findOrFail($prospect->id);
            $prospect->is_project = true;
            $prospect->save();
            $user = User::where(['id' => $prospect->user_id])->first();
            //sales executive goal
            $net_goal = Goal::where(['user_id' => $user->sales_manager_id, 'goals_type' => 1])->whereMonth('goals_date', date('m', strtotime($prospect->sale_date)))->whereYear('goals_date', date('Y', strtotime($prospect->sale_date)))->first();
            if ($net_goal) {
                $net_goal->goals_achieve = $net_goal->goals_achieve + $prospect->upfront_value;
                $net_goal->save();
            }
            $gross_goal = Goal::where(['user_id' => $user->sales_manager_id, 'goals_type' => 2])->whereMonth('goals_date', date('m', strtotime($prospect->sale_date)))->whereYear('goals_date', date('Y', strtotime($prospect->sale_date)))->first();
            if ($gross_goal) {
                $gross_goal->goals_achieve = $gross_goal->goals_achieve + $prospect->price_quote;
                $gross_goal->save();
            }

            //sales manager goal
            $net_goal = Goal::where(['user_id' => $user->report_to, 'goals_type' => 1])->whereMonth('goals_date', date('m', strtotime($prospect->sale_date)))->whereYear('goals_date', date('Y', strtotime($prospect->sale_date)))->first();
            if ($net_goal) {
                $net_goal->goals_achieve = $net_goal->goals_achieve + $prospect->upfront_value;
                $net_goal->save();
            }

            $gross_goal = Goal::where(['user_id' => $user->report_to, 'goals_type' => 2])->whereMonth('goals_date', date('m', strtotime($prospect->sale_date)))->whereYear('goals_date', date('Y', strtotime($prospect->sale_date)))->first();
            if ($gross_goal) {
                $gross_goal->goals_achieve = $gross_goal->goals_achieve + $prospect->price_quote;
                $gross_goal->save();
            }

            $project = new Project();
            $project->user_id = $prospect->report_to;
            $project->client_name = $prospect->client_name;
            $project->business_name = $prospect->business_name;
            $project->client_email = $prospect->client_email;
            $project->client_phone = $prospect->client_phone;
            $project->client_address = $prospect->business_address;
            $project->project_value = $prospect->price_quote;
            $project->currency = 'USD'; // default currency 'USD
            $project->payment_mode = '';
            $project->project_opener = $user->id;
            $project->project_closer = '';
            $project->project_upfront = $prospect->upfront_value;
            $project->website = $prospect->website;
            $project->sale_date = $prospect->sale_date ?? '';
            $project->comment = $prospect->comments;
            $project->save();


            //prospect project milestone
            $previous_milestone_value = ProjectMilestone::where('project_id', $id)->sum('milestone_value');

            ProjectMilestone::where('project_id', $id)->delete();
            if (isset($data['milestone_name'])) {
                foreach ($data['milestone_name'] as $key => $milestone) {
                    //check if data is null
                    if ($data['milestone_name'][$key] != null) {
                        $project_milestone = new ProjectMilestone();
                        $project_milestone->project_id = $project->id;
                        $project_milestone->milestone_name = $milestone;
                        $project_milestone->milestone_value = $data['milestone_value'][$key];
                        $project_milestone->payment_status = 'Due';
                        // $project_milestone->payment_date = ($data['payment_status'][$key] == 'Paid') ? date('Y-m-d') : '';
                        $project_milestone->milestone_comment = $data['milestone_comment'][$key];
                        //  $project_milestone->payment_mode = $data['milestone_payment_mode'][$key];
                        //  $project_milestone->payment_date = $data['milestone_payment_date'][$key];
                        $project_milestone->save();
                    }
                }
            }

            if ($prospect->upfront_value > 0) {
                $upfront = ProjectMilestone::where('project_id', $project->id)->where('milestone_type', 'upfront')->first();
                if ($upfront) {
                    $upfront->milestone_value = $prospect->upfront_value;
                    $upfront->save();
                } else {
                    $upfront = new ProjectMilestone();
                    $upfront->project_id = $project->id;
                    $upfront->milestone_name = 'Upfront';
                    $upfront->milestone_type = 'upfront';
                    $upfront->milestone_value = $prospect->upfront_value;
                    $upfront->payment_status = 'Paid';
                    $upfront->payment_mode = $data['payment_mode'] ?? null;
                    $upfront->payment_date = date('Y-m-d');
                    $upfront->save();
                }
            }


            $project_type = new ProjectType();
            $project_type->project_id = $project->id;
            $project_type->type = $prospect->offered_for;
            if (
                $prospect->offered_for != 'SMO' &&
                $prospect->offered_for != 'SEO' &&
                $prospect->offered_for != 'Logo Design' &&
                $prospect->offered_for != 'Digital Marketing & SEO' &&
                $prospect->offered_for != 'Mobile Application Development' &&
                $prospect->offered_for != 'Website Design & Development'
            ) {
                $project_type->name = 'Other';
            } else {
                $project_type->name = $prospect->offered_for;
            }

            $project_type->save();
        }
        return redirect()->route('admin.prospects.index')->with('message', 'Prospect updated successfully.');
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
        $prospect = Prospect::find($id);
        $prospect->delete();
        return redirect()->back()->with('message', 'Prospect deleted successfully.');
    }

    public function filter(Request $request)
    {
        if ($request->ajax()) {
            $status = $request->status;
            $query = $request->get('query');

            Session::put('admin_prospect_filter_search', $query);
            Session::put('admin_prospect_filter_start_date', $request->start_date);
            Session::put('admin_prospect_filter_end_date', $request->end_date);
            Session::put('admin_prospect_filter_user_id', $request->user_id);
            Session::put('admin_prospect_filter_status', $request->status);

            $query = str_replace(" ", "%", $query);
            $prospects = Prospect::query();
            if ($query != '') {
                $prospects = $prospects->where(function ($q) use ($query) {
                    $q->orWhere('client_name', 'like', '%' . $query . '%')
                        ->orWhere('business_name', 'like', '%' . $query . '%')
                        ->orWhere('client_email', 'like', '%' . $query . '%')
                        ->orWhere('client_phone', 'like', '%' . $query . '%')
                        ->orWhere('price_quote', 'like', '%' . $query . '%')
                        ->orWhere('followup_date', 'like', '%' . $query . '%')
                        ->orWhere('offered_for', 'like', '%' . $query . '%')
                        ->orWhereHas('user', function ($q) use ($query) {
                            $q->where('name', 'like', '%' . $query . '%');
                        })
                        ->orWhereHas('transferTakenBy', function ($q) use ($query) {
                            $q->where('name', 'like', '%' . $query . '%');
                        });
                });
            }
            if ($request->followup_date) {
                $followup_date = date('Y-m-d', strtotime($request->followup_date));
                $prospects = $prospects->where('followup_date', $followup_date);
            }

            if ($request->user_id) {
                $prospects = $prospects->where(['user_id' => $request->user_id]);
            }

            if ($request->start_date && $request->end_date) {
                $prospects->whereBetween('created_at', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);
            }

            // Compute counts before applying status filter
            $count = [];
            $count['prospect'] = (clone $prospects)->count();
            $count['win'] = (clone $prospects)->where('status', 'Win')->count();
            $count['follow_up'] = (clone $prospects)->where('status', 'Follow Up')->count();
            $count['sent_proposal'] = (clone $prospects)->where('status', 'Sent Proposal')->count();
            $count['close'] = (clone $prospects)->where('status', 'Close')->count();

            if ($status == 'All') {
                $prospects = $prospects->orderBy('id', 'desc')->paginate('10');
            } else {
                $prospects = $prospects->orderBy('id', 'desc')->where(['status' => $status])->paginate('10');
            }

            return response()->json([
                'data' => view('admin.prospect.table', compact('prospects'))->render(),
                'count' => $count
            ]);
        }
    }

    public function assignToProject($id)
    {
        try {

            $prospect = Prospect::findOrFail($id);
            $prospect->is_project = true;
            $prospect->save();
            $user = User::where(['id' => $prospect->user_id])->first();
            //sales executive goal
            $net_goal = Goal::where(['user_id' => $user->sales_manager_id, 'goals_type' => 1])->whereMonth('goals_date', date('m', strtotime($prospect->sale_date)))->whereYear('goals_date', date('Y', strtotime($prospect->sale_date)))->first();
            if ($net_goal) {
                $net_goal->goals_achieve = $net_goal->goals_achieve + $prospect->upfront_value;
                $net_goal->save();
            }
            $gross_goal = Goal::where(['user_id' => $user->sales_manager_id, 'goals_type' => 2])->whereMonth('goals_date', date('m', strtotime($prospect->sale_date)))->whereYear('goals_date', date('Y', strtotime($prospect->sale_date)))->first();
            if ($gross_goal) {
                $gross_goal->goals_achieve = $gross_goal->goals_achieve + $prospect->price_quote;
                $gross_goal->save();
            }

            //sales manager goal
            $net_goal = Goal::where(['user_id' => $user->report_to, 'goals_type' => 1])->whereMonth('goals_date', date('m', strtotime($prospect->sale_date)))->whereYear('goals_date', date('Y', strtotime($prospect->sale_date)))->first();
            if ($net_goal) {
                $net_goal->goals_achieve = $net_goal->goals_achieve + $prospect->upfront_value;
                $net_goal->save();
            }

            $gross_goal = Goal::where(['user_id' => $user->report_to, 'goals_type' => 2])->whereMonth('goals_date', date('m', strtotime($prospect->sale_date)))->whereYear('goals_date', date('Y', strtotime($prospect->sale_date)))->first();
            if ($gross_goal) {
                $gross_goal->goals_achieve = $gross_goal->goals_achieve + $prospect->price_quote;
                $gross_goal->save();
            }

            $project = new Project();
            $project->user_id = $user->sales_manager_id;
            $project->client_name = $prospect->client_name;
            $project->business_name = $prospect->business_name;
            $project->client_email = $prospect->client_email;
            $project->client_phone = $prospect->client_phone;
            $project->client_address = $prospect->business_address;
            $project->project_value = $prospect->price_quote;
            $project->currency = 'USD'; // default currency 'USD
            $project->payment_mode = '';
            $project->project_opener = $user->id;
            $project->project_closer = '';
            $project->project_upfront = $prospect->upfront_value;
            $project->website = $prospect->website;
            $project->sale_date = $prospect->sale_date ?? '';
            $project->comment = $prospect->comments;
            $project->save();

            if ($prospect->upfront_value > 0) {
                $upfront = ProjectMilestone::where('project_id', $project->id)->where('milestone_type', 'upfront')->first();
                if ($upfront) {
                    $upfront->milestone_value = $prospect->upfront_value;
                    $upfront->save();
                } else {
                    $upfront = new ProjectMilestone();
                    $upfront->project_id = $project->id;
                    $upfront->milestone_name = 'Upfront';
                    $upfront->milestone_type = 'upfront';
                    $upfront->milestone_value = $prospect->upfront_value;
                    $upfront->payment_status = 'Paid';
                    $upfront->payment_mode = '';
                    $upfront->payment_date = date('Y-m-d');
                    $upfront->save();
                }
            }

            $project_type = new ProjectType();
            $project_type->project_id = $project->id;
            $project_type->type = $prospect->offered_for;
            if (
                $prospect->offered_for != 'SMO' &&
                $prospect->offered_for != 'SEO' &&
                $prospect->offered_for != 'Logo Design' &&
                $prospect->offered_for != 'Digital Marketing & SEO' &&
                $prospect->offered_for != 'Mobile Application Development' &&
                $prospect->offered_for != 'Website Design & Development'
            ) {
                $project_type->name = 'Other';
            } else {
                $project_type->name = $prospect->offered_for;
            }

            $project_type->save();

            return redirect()->route('admin.prospects.index')->with('message', 'Prospect converted to project successfully.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
}
