<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BdmFollowup;
use App\Models\BdmProjectType;
use App\Models\Goal;
use App\Models\BdmProject;
use Illuminate\Http\Request;
use App\Models\BdmProspect;
use App\Models\ProjectMilestone;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
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

        $startDate = $request->start_date;
        $endDate = $request->end_date;

        if ($request->duration) {
            [$startDate, $endDate] = \App\Helpers\Helper::getDateRangeByDuration($request->duration);
            if ($startDate && $endDate) {
                $prospects->whereBetween('sale_date', [$startDate, $endDate]);
            }
        } elseif ($startDate && $endDate) {
            $prospects->whereBetween('sale_date', [$startDate, $endDate]);
        }

        if ($request->user_id) {
            $prospects->where('user_id', $request->user_id);
        }

        $count['win'] = (clone $prospects)->where('status', 'Win')->count();
        $count['follow_up'] = (clone $prospects)->where('status', 'Follow Up')->count();
        $count['close'] = (clone $prospects)->where('status', 'Close')->count();
        $count['sent_proposal'] = (clone $prospects)->where('status', 'Sent Proposal')->count();
        $count['prospect'] = (clone $prospects)->count();

        $prospects = $prospects->paginate('10');

        return view('admin.bdm-prospect.list', compact('count', 'prospects', 'users', 'bdm_managers', 'startDate', 'endDate'));
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
        $prospect->followup_date = $data['followup_date'];
        $prospect->followup_time = $data['followup_time'];
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
        $prospect->save();

        // if comments store at BdmFollowup
        if (isset($data['comments']) && $data['comments'] != null) {
            $followup = new BdmFollowup();
            $followup->user_id = auth()->id();
            $followup->bdm_prospect_id = $prospect->id;
            $followup->remark = $data['comments'];
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

            $project = new BdmProject();
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

            $project = new BdmProject();
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
                $prospects->whereBetween('sale_date', [$request->start_date, $request->end_date]);
            }

            if ($status == 'All') {
                $prospects = $prospects->orderBy('id', 'desc')->paginate('10');
            } else {
                $prospects = $prospects->orderBy('id', 'desc')->where(['status' => $status])->paginate('10');
            }

            return response()->json(['data' => view('admin.bdm-prospect.table', compact('prospects'))->render()]);
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
}
