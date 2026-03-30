<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BdmFollowup;
use App\Models\BdmProjectType;
use App\Models\Goal;
use App\Models\BdmProject;
use Illuminate\Http\Request;
use App\Models\BdmProspect;
use App\Models\Customer;
use App\Models\ProjectMilestone;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

class BdmProspectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = User::role(['BUSINESS_DEVELOPMENT_MANAGER', 'BUSINESS_DEVELOPMENT_EXCECUTIVE'])->orderBy('id', 'desc')->get();
        $bdm_managers = User::role(['BUSINESS_DEVELOPMENT_MANAGER'])->where(['status' => 1])->orderBy('id', 'desc')->get();
        // dd($request->user_id);
        $prospects = BdmProspect::orderBy('id', 'desc');

        // When coming from user list or dashboard, clear stale session filters
        if ($request->has('user_id') || $request->has('duration')) {
            Session::forget('admin_bdm_prospect_filter_start_date');
            Session::forget('admin_bdm_prospect_filter_end_date');
            Session::forget('admin_bdm_prospect_filter_search');
            Session::forget('admin_bdm_prospect_filter_status');
            Session::forget('admin_bdm_prospect_filter_user_id');
        }

        $startDate = Session::get('admin_bdm_prospect_filter_start_date');
        $endDate = Session::get('admin_bdm_prospect_filter_end_date');
        $search = Session::get('admin_bdm_prospect_filter_search');
        $user_id_filter = Session::get('admin_bdm_prospect_filter_user_id', $request->user_id);
        $status_filter = Session::get('admin_bdm_prospect_filter_status');

        if ($request->duration) {
            [$startDate, $endDate] = \App\Helpers\Helper::getDateRangeByDuration($request->duration);
            if ($startDate && $endDate) {
                Session::put('admin_bdm_prospect_filter_start_date', $startDate);
                Session::put('admin_bdm_prospect_filter_end_date', $endDate);
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

        return view('admin.bdm-prospect.list', compact('count', 'prospects', 'users', 'bdm_managers', 'startDate', 'endDate', 'search', 'status_filter'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::role(['BUSINESS_DEVELOPMENT_MANAGER', 'BUSINESS_DEVELOPMENT_EXCECUTIVE'])->orderBy('id', 'desc')->get();
        $sales_executives = User::role(['BUSINESS_DEVELOPMENT_EXCECUTIVE'])->where(['status' => 1])->orderBy('id', 'desc')->get();
        return view('admin.bdm-prospect.create')->with(compact('sales_executives', 'users'));
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
            'meeting_date' => 'required_if:status,In Meeting',
            'followup_date' => 'required_if:status,Follow Up',
            'comments' => 'required',
        ]);
        // try {
        $data = $request->all();
        $prospect = new BdmProspect();
        $prospect->user_id = $data['report_to'];
        $prospect->report_to = $data['report_to'];
        $prospect->client_name = $data['client_name'];
        $prospect->business_name = $data['business_name'];
        $prospect->client_email = $data['client_email'];
        $prospect->client_phone = $data['client_phone'];
        $prospect->business_address = $data['business_address'];
        $prospect->website = $data['website'];
        $prospect->status = $data['status'];
        $prospect->last_call_status = $data['last_call_status'] ?? null;
        $prospect->followup_date = $data['followup_date'];
        $prospect->followup_time = $data['followup_time'];
        $prospect->meeting_date = !empty($data['meeting_date']) ? $data['meeting_date'] : null;
        $prospect->sale_date = $data['sale_date'] ?? '';
        $prospect->upfront_value = $data['upfront_value'] ?? '';
        $prospect->comments = $data['comments'];
        $prospect->source = $data['source'];
        $prospect->price_quote = $data['price_quote'];
        if ($data['offered_for'] == 'Other') {
            $prospect->offered_for = $data['other_value'];
        } else {
            $prospect->offered_for = $data['offered_for'];
        }
        $prospect->transfer_token_by = $data['transfer_token_by'];
        $prospect->category = $data['category'];
        $prospect->designation = $data['designation'];
        $prospect->added_by = auth()->id();
        $prospect->save();

        // if comments store at BdmFollowup
        if (isset($data['comments']) && $data['comments'] != null) {
            $followup = new BdmFollowup();
            $followup->user_id = auth()->id();
            $followup->bdm_prospect_id = $prospect->id;
            $followup->remark = $data['comments'];
            $followup->status = $data['status'];
            $followup->last_call_status = $data['last_call_status'] ?? null;
            $followup->meeting_date = !empty($data['meeting_date']) ? $data['meeting_date'] : null;
            $followup->save();
        }

        if ($request->status == 'Win') {
            $prospect = BdmProspect::findOrFail($prospect->id);
            $prospect->is_project = true;
            $prospect->save();
            $user = User::where(['id' => $prospect->user_id])->first();
            //business development executive goal
            // $net_goal = Goal::where(['user_id' => $user->bdm_id, 'goals_type' => 1])->whereMonth('goals_date', date('m', strtotime($prospect->sale_date)))->whereYear('goals_date', date('Y', strtotime($prospect->sale_date)))->first();
            // if ($net_goal) {
            //     $net_goal->goals_achieve = $net_goal->goals_achieve + $prospect->upfront_value;
            //     $net_goal->save();
            // }
            // $gross_goal = Goal::where(['user_id' => $user->bdm_id, 'goals_type' => 2])->whereMonth('goals_date', date('m', strtotime($prospect->sale_date)))->whereYear('goals_date', date('Y', strtotime($prospect->sale_date)))->first();
            // if ($gross_goal) {
            //     $gross_goal->goals_achieve = $gross_goal->goals_achieve + $prospect->price_quote;
            //     $gross_goal->save();
            // }

            //business development manager goal
            $net_goal = Goal::where(['user_id' => $request->report_to, 'goals_type' => 1])->whereMonth('goals_date', date('m', strtotime($prospect->sale_date)))->whereYear('goals_date', date('Y', strtotime($prospect->sale_date)))->first();
            if ($net_goal) {
                $net_goal->goals_achieve = $net_goal->goals_achieve + $prospect->upfront_value;
                $net_goal->save();
            }

            $gross_goal = Goal::where(['user_id' => $request->report_to, 'goals_type' => 2])->whereMonth('goals_date', date('m', strtotime($prospect->sale_date)))->whereYear('goals_date', date('Y', strtotime($prospect->sale_date)))->first();
            if ($gross_goal) {
                $gross_goal->goals_achieve = $gross_goal->goals_achieve + $prospect->price_quote;
                $gross_goal->save();
            }

             $customer_exist = Customer::where('customer_email', $prospect->client_email)->first();
            if ($customer_exist) {
                $customer_id = $customer_exist->id;
            } else {
                $customer = new Customer();
                $customer->customer_name = $prospect->client_name;
                $customer->customer_email = $prospect->client_email;
                $customer->customer_phone = $prospect->client_phone;
                $customer->customer_address = $prospect->business_address;
                $customer->save();
                $customer_id = $customer->id;
            }

            $project = new BdmProject();
            $project->customer_id = $customer_id ?? null;
            $project->user_id = $prospect->report_to;
            $project->client_name = $prospect->client_name;
            $project->business_name = $prospect->business_name;
            $project->client_email = $prospect->client_email;
            $project->client_phone = $prospect->client_phone;
            $project->client_address = $prospect->business_address;
            $project->project_value = $prospect->price_quote;
            $project->currency = 'USD'; // default currency 'USD
            $project->payment_mode = '';
            $project->project_opener = $request->report_to;
            $project->project_closer = '';
            $project->project_upfront = $prospect->upfront_value;
            $project->website = $prospect->website;
            $project->sale_date = $prospect->sale_date ?? '';
            $project->comment = $prospect->comments;
            $project->save();

            if ($prospect->upfront_value > 0) {
                $upfront = ProjectMilestone::where('bdm_project_id', $project->id)->where('milestone_type', 'upfront')->first();
                if ($upfront) {
                    $upfront->milestone_value = $prospect->upfront_value;
                    $upfront->save();
                } else {
                    $upfront = new ProjectMilestone();
                    $upfront->bdm_project_id = $project->id;
                    $upfront->milestone_name = 'Upfront';
                    $upfront->milestone_type = 'upfront';
                    $upfront->milestone_value = $prospect->upfront_value;
                    $upfront->payment_status = 'Paid';
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
                        $project_milestone->bdm_project_id = $project->id;
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

            $project_type = new BDMProjectType();
            $project_type->bdm_project_id = $project->id;
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

        return redirect()->route('admin.bdm-prospects.index')->with('message', 'BdmProspect created successfully.');
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
            $prospect = BdmProspect::find($id);
            $isThat = 'view';
            return response()->json(['view' => (string)View::make('admin.bdm-prospect.show-details')->with(compact('prospect', 'isThat'))]);
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
            $users = User::role(['BUSINESS_DEVELOPMENT_MANAGER', 'BUSINESS_DEVELOPMENT_EXCECUTIVE'])->orderBy('id', 'desc')->get();
            $prospect = BdmProspect::find($id);
            $bdm_managers = User::role(['BUSINESS_DEVELOPMENT_MANAGER'])->where(['status' => 1])->orderBy('id', 'desc')->get();
            $type = true;
            return response()->json(['view' => view('admin.bdm-prospect.edit', compact('prospect', 'users', 'bdm_managers', 'type'))->render()]);
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
        $request->validate([
            'meeting_date' => 'required_if:status,In Meeting',
            'followup_date' => 'required_if:status,Follow Up',
            'comments' => 'required',
        ]);
        $data = $request->all();
        // $getUser = User::where(['id' => $data['user_id']])->first();
        $prospect = BdmProspect::findOrfail($id);
        $prospect->user_id = $data['report_to'];
        $prospect->report_to = $data['report_to'];
        $prospect->client_name = $data['client_name'];
        $prospect->business_name = $data['business_name'];
        $prospect->client_email = $data['client_email'];
        $prospect->client_phone = $data['client_phone'];
        $prospect->business_address = $data['business_address'];
        $prospect->website = $data['website'];
        $prospect->status = $data['status'];
        $prospect->source = $data['source'];
        $prospect->followup_date = $data['followup_date'];
        $prospect->followup_time = $data['followup_time'];
        $prospect->meeting_date = !empty($data['meeting_date']) ? $data['meeting_date'] : null;
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
        $prospect->category = $data['category'];
        $prospect->designation = $data['designation'];
        $prospect->save();

        // if comments store at BdmFollowup
        if (isset($data['comments']) && $data['comments'] != null) {
            $followup = new BdmFollowup();
            $followup->user_id = auth()->id();
            $followup->bdm_prospect_id = $prospect->id;
            $followup->remark = $data['comments'];
            $followup->status = $data['status'];
            $followup->last_call_status = $data['last_call_status'] ?? null;
            $followup->meeting_date = !empty($data['meeting_date']) ? $data['meeting_date'] : null;
            $followup->save();
        }

        if ($request->status == 'Win') {
            $prospect = BdmProspect::findOrFail($prospect->id);
            $prospect->is_project = true;
            $prospect->save();
            $user = User::where(['id' => $prospect->user_id])->first();
            // //business development executive goal
            // $net_goal = Goal::where(['user_id' => $user->bdm_id, 'goals_type' => 1])->whereMonth('goals_date', date('m', strtotime($prospect->sale_date)))->whereYear('goals_date', date('Y', strtotime($prospect->sale_date)))->first();
            // if ($net_goal) {
            //     $net_goal->goals_achieve = $net_goal->goals_achieve + $prospect->upfront_value;
            //     $net_goal->save();
            // }
            // $gross_goal = Goal::where(['user_id' => $user->bdm_id, 'goals_type' => 2])->whereMonth('goals_date', date('m', strtotime($prospect->sale_date)))->whereYear('goals_date', date('Y', strtotime($prospect->sale_date)))->first();
            // if ($gross_goal) {
            //     $gross_goal->goals_achieve = $gross_goal->goals_achieve + $prospect->price_quote;
            //     $gross_goal->save();
            // }

            //business development manager goal
            $net_goal = Goal::where(['user_id' => $request->report_to, 'goals_type' => 1])->whereMonth('goals_date', date('m', strtotime($prospect->sale_date)))->whereYear('goals_date', date('Y', strtotime($prospect->sale_date)))->first();
            if ($net_goal) {
                $net_goal->goals_achieve = $net_goal->goals_achieve + $prospect->upfront_value;
                $net_goal->save();
            }

            $gross_goal = Goal::where(['user_id' => $request->report_to, 'goals_type' => 2])->whereMonth('goals_date', date('m', strtotime($prospect->sale_date)))->whereYear('goals_date', date('Y', strtotime($prospect->sale_date)))->first();
            if ($gross_goal) {
                $gross_goal->goals_achieve = $gross_goal->goals_achieve + $prospect->price_quote;
                $gross_goal->save();
            }

             $customer_exist = Customer::where('customer_email', $prospect->client_email)->first();
            if ($customer_exist) {
                $customer_id = $customer_exist->id;
            } else {
                $customer = new Customer();
                $customer->customer_name = $prospect->client_name;
                $customer->customer_email = $prospect->client_email;
                $customer->customer_phone = $prospect->client_phone;
                $customer->customer_address = $prospect->business_address;
                $customer->save();
                $customer_id = $customer->id;
            }

            $project = new BdmProject();
            $project->customer_id = $customer_id ?? null;
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
            $previous_milestone_value = ProjectMilestone::where('bdm_project_id', $id)->sum('milestone_value');

            ProjectMilestone::where('bdm_project_id', $id)->delete();
            if (isset($data['milestone_name'])) {
                foreach ($data['milestone_name'] as $key => $milestone) {
                    //check if data is null
                    if ($data['milestone_name'][$key] != null) {
                        $project_milestone = new ProjectMilestone();
                        $project_milestone->bdm_project_id = $project->id;
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
                $upfront = ProjectMilestone::where('bdm_project_id', $project->id)->where('milestone_type', 'upfront')->first();
                if ($upfront) {
                    $upfront->milestone_value = $prospect->upfront_value;
                    $upfront->save();
                } else {
                    $upfront = new ProjectMilestone();
                    $upfront->bdm_project_id = $project->id;
                    $upfront->milestone_name = 'Upfront';
                    $upfront->milestone_type = 'upfront';
                    $upfront->milestone_value = $prospect->upfront_value;
                    $upfront->payment_status = 'Paid';
                    $upfront->payment_date = date('Y-m-d');
                    $upfront->save();
                }
            }

            $project_type = new BdmProjectType();
            $project_type->bdm_project_id = $project->id;
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
        return redirect()->route('admin.bdm-prospects.index')->with('message', 'BdmProspect updated successfully.');
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
        $prospect = BdmProspect::find($id);
        $prospect->delete();
        return redirect()->back()->with('message', 'BdmProspect deleted successfully.');
    }

    public function filter(Request $request)
    {
        if ($request->ajax()) {
            $status = $request->status;
            $query = $request->get('query');

            Session::put('admin_bdm_prospect_filter_search', $query);
            Session::put('admin_bdm_prospect_filter_start_date', $request->start_date);
            Session::put('admin_bdm_prospect_filter_end_date', $request->end_date);
            Session::put('admin_bdm_prospect_filter_user_id', $request->user_id);
            Session::put('admin_bdm_prospect_filter_status', $request->status);

            $query = str_replace(" ", "%", $query);
            $prospects = BdmProspect::query();
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
                'data' => view('admin.bdm-prospect.table', compact('prospects'))->render(),
                'count' => $count
            ]);
        }
    }

    public function assignToProject($id)
    {
        try {

            $prospect = BdmProspect::findOrFail($id);
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

            $project = new BdmProject();
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
                $upfront = ProjectMilestone::where('bdm_project_id', $project->id)->where('milestone_type', 'upfront')->first();
                if ($upfront) {
                    $upfront->milestone_value = $prospect->upfront_value;
                    $upfront->save();
                } else {
                    $upfront = new ProjectMilestone();
                    $upfront->bdm_project_id = $project->id;
                    $upfront->milestone_type = 'upfront';
                    $upfront->milestone_value = $prospect->upfront_value;
                    $upfront->payment_status = 'Paid';
                    $upfront->payment_date = date('Y-m-d');
                    $upfront->save();
                }
            }

            $project_type = new BDMProjectType();
            $project_type->bdm_project_id = $project->id;
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

            return redirect()->route('admin.bdm-prospects.index')->with('message', 'BdmProspect converted to project successfully.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt,xlsx,xls',
        ]);

        $file = $request->file('file');
        $extension = $file->getClientOriginalExtension();

        if (in_array($extension, ['xlsx', 'xls'])) {
             return redirect()->back()->with('error', 'Excel files (.xlsx, .xls) are not supported directly yet. Please "Save As" .csv and try again.');
        }

        $handle = fopen($file->getPathname(), 'r');

        // Handle UTF-8 BOM
        $bom = fread($handle, 3);
        if ($bom !== pack("CCC", 0xef, 0xbb, 0xbf)) {
            rewind($handle);
        }

        $header = fgetcsv($handle);

        if (!$header) {
            fclose($handle);
            return redirect()->back()->with('error', 'The CSV file is empty or invalid.');
        }

        // Clean headers (remove whitespace/quotes)
        $header = array_map(function($h) { return trim(strtolower(str_replace(' ', '_', $h))); }, $header);

        $rowCount = 0;
        $errors = [];
        $line = 1;

        while (($data = fgetcsv($handle)) !== false) {
            $line++;
            // Basic data check
            if (empty(array_filter($data))) continue;

            if (count($header) !== count($data)) {
                $errors[] = "Line $line: Column count mismatch (Got " . count($data) . " columns, expected " . count($header) . ").";
                continue;
            }
            $row = array_combine($header, $data);

            // Required field check - updated with more flexible mapping if possible
            $required = ['client_name', 'designation', 'client_phone', 'business_name', 'business_address', 'category'];
            $missing = [];
            foreach ($required as $field) {
                if (empty(trim($row[$field] ?? ''))) {
                    $missing[] = ucwords(str_replace('_', ' ', $field));
                }
            }

            if (!empty($missing)) {
                $errors[] = "Line $line: Missing required field(s): " . implode(', ', $missing);
                continue;
            }

            $prospect = new BdmProspect();
            $prospect->client_name = trim($row['client_name']);
            $prospect->business_name = trim($row['business_name']);
            $prospect->category = trim($row['category']);
            $prospect->designation = trim($row['designation']);
            $prospect->client_email = !empty(trim($row['client_email'] ?? '')) ? trim($row['client_email']) : null;
            $prospect->client_phone = trim($row['client_phone']);
            $prospect->business_address = trim($row['business_address']);
            $prospect->website = !empty(trim($row['website'] ?? '')) ? trim($row['website']) : null;
            $prospect->price_quote = !empty(trim($row['price_quote'] ?? '')) ? floatval($row['price_quote']) : 0;
            $prospect->source = trim($row['source'] ?? '');
            $prospect->offered_for = trim($row['offered_for'] ?? '');
            $prospect->status = trim($row['status'] ?? 'Prospect');
            $prospect->followup_date = !empty(trim($row['followup_date'] ?? '')) ? trim($row['followup_date']) : date('Y-m-d');
            $prospect->followup_time = !empty(trim($row['followup_time'] ?? '')) ? trim($row['followup_time']) : '10:00';
            $prospect->added_by = auth()->id();

            // Default user_id and report_to
            $prospect->user_id = auth()->id();
            $prospect->report_to = auth()->id();

            // Email id match of sales manager
            if (!empty(trim($row['sales_manager_email'] ?? ''))) {
                $email = trim($row['sales_manager_email']);
                $salesManager = User::role(['BUSINESS_DEVELOPMENT_MANAGER'])->where('email', $email)->first();
                if ($salesManager) {
                    $prospect->user_id = $salesManager->id;
                    $prospect->report_to = $salesManager->id;
                }
            }

            $prospect->save();
            $rowCount++;
        }

        fclose($handle);

        $message = $rowCount . ' prospects imported successfully.';
        if (!empty($errors)) {
            // Only show first 5 errors to avoid overwhelming the screen
            $errorCount = count($errors);
            $errorHeader = $errorCount > 5 ? "First 5 errors: " : "Errors: ";
            $message .= ' Some rows were skipped. ' . $errorHeader . implode(' | ', array_slice($errors, 0, 5));
            if ($errorCount > 5) $message .= " ... and " . ($errorCount - 5) . " more errors.";
            return redirect()->back()->with('error', $message);
        }

        return redirect()->back()->with('message', $message);
    }

    public function downloadSample()
    {
        $filename = "prospects_sample.csv";
        $handle = fopen('php://temp', 'w+');

        $header = [
            'client_name', 'business_name', 'category', 'designation',
            'client_email', 'client_phone', 'business_address', 'website',
            'price_quote', 'source', 'offered_for', 'status', 'followup_date', 'followup_time',
            'sales_manager_email'
        ];

        fputcsv($handle, $header);

        // Sample data
        fputcsv($handle, [
            'John Doe', 'Doe Industries', 'IT', 'Manager',
            'john@example.com', '1234567890', '123 Street, NY', 'https://example.com',
            '5000', 'LinkedIn', 'Website Design & Development', 'Prospect', date('Y-m-d'), '14:30',
            'salesmanager@example.com'
        ]);

        rewind($handle);
        $csvContent = stream_get_contents($handle);
        fclose($handle);

        return response($csvContent)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}
