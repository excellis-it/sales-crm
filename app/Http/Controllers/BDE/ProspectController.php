<?php

namespace App\Http\Controllers\BDE;

use App\Http\Controllers\Controller;
use App\Models\BdmFollowup;
use App\Models\Goal;
use App\Models\BdmProject;
use App\Models\BdmProjectType;
use Illuminate\Http\Request;
use App\Models\BdmProspect;
use App\Models\User;
use App\Models\ProjectMilestone;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class ProspectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($type = null)
    {
        $type = request()->get('type');

        if ($type) {
            // return $type;
            $prospects = BdmProspect::where(['user_id' => Auth::user()->id, 'status' => $type])->orderBy('id', 'desc')->paginate(15);
            $count['win'] = BdmProspect::where('user_id', auth()->user()->id)->where('status', 'Win')->count();
            $count['follow_up'] = BdmProspect::where('user_id', auth()->user()->id)->where('status', 'Follow Up')->count();
            $count['close'] = BdmProspect::where('user_id', auth()->user()->id)->where('status', 'Close')->count();
            $count['sent_proposal'] = BdmProspect::where('user_id', auth()->user()->id)->where('status', 'Sent Proposal')->count();
            $users = User::role(['BUSINESS_DEVELOPMENT_EXCECUTIVE'])->get();
        } else {
            $prospects = BdmProspect::where('user_id', Auth::user()->id)->orderBy('id', 'desc')->paginate(10);
            $count['win'] = BdmProspect::where('user_id', auth()->user()->id)->where('status', 'Win')->count();
            $count['follow_up'] = BdmProspect::where('user_id', auth()->user()->id)->where('status', 'Follow Up')->count();
            $count['close'] = BdmProspect::where('user_id', auth()->user()->id)->where('status', 'Close')->count();
            $count['sent_proposal'] = BdmProspect::where('user_id', auth()->user()->id)->where('status', 'Sent Proposal')->count();
            $users = User::role(['BUSINESS_DEVELOPMENT_EXCECUTIVE'])->get();
        }

        return view('bde.prospect.list')->with(compact('prospects', 'count', 'users'));
    }

    public function bdeProspectFilter(Request $request)
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
                        ->whereHas('user', function ($q) use ($query) {
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

            if ($status == 'All') {
                $prospects = $prospects->orderBy('id', 'desc')->where('user_id', Auth::user()->id)->paginate('15');
            } else {
                $prospects = $prospects->orderBy('id', 'desc')->where(['status' => $status])->where('user_id', Auth::user()->id)->paginate('15');
            }

            return response()->json(['data' => view('bde.prospect.table', compact('prospects'))->render()]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::role(['SALES_MANAGER', 'ACCOUNT_MANAGER', 'SALES_EXCUETIVE'])->get();
        return view('bde.prospect.create')->with(compact('users'));
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
           
            'comments' => 'required',
        ]);
        $data = $request->all();

        $prospect = new BdmProspect();
        $prospect->user_id = Auth::user()->id;
        $prospect->report_to = Auth::user()->bdm_id;
        $prospect->client_name = $data['client_name'];
        $prospect->business_name = $data['business_name'];
        $prospect->followup_time = $data['followup_time'];
        $prospect->client_email = $data['client_email'];
        $prospect->client_phone = $data['client_phone'];
        $prospect->business_address = $data['business_address'];
        $prospect->website = $data['website'];
        $prospect->status = $data['status'];
        $prospect->followup_date = $data['followup_date'];
        $prospect->meeting_date = !empty($data['meeting_date']) ? $data['meeting_date'] : null;
        $prospect->sale_date = $data['sale_date'] ?? '';
        $prospect->upfront_value = $data['upfront_value'] ?? '';
        $prospect->comments = $data['comments'];
        $prospect->price_quote = $data['price_quote'];
        $prospect->payment_mode = $data['payment_mode'] ?? null;
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
            $followup->meeting_date = !empty($data['meeting_date']) ? $data['meeting_date'] : null;
            $followup->save();
        }

        if ($data['status'] == 'Win') {
            $prospect = BdmProspect::findOrFail($prospect->id);
            $prospect->is_project = true;
            $prospect->save();

            //sales executive goal
            $net_goal = Goal::where(['user_id' => Auth::user()->id, 'goals_type' => 1])->whereMonth('goals_date', date('m', strtotime($prospect->sale_date)))->whereYear('goals_date', date('Y', strtotime($prospect->sale_date)))->first();
            if ($net_goal) {
                $net_goal->goals_achieve = $net_goal->goals_achieve + $prospect->upfront_value;
                $net_goal->save();
            }
            $gross_goal = Goal::where(['user_id' => Auth::user()->id, 'goals_type' => 2])->whereMonth('goals_date', date('m', strtotime($prospect->sale_date)))->whereYear('goals_date', date('Y', strtotime($prospect->sale_date)))->first();
            if ($gross_goal) {
                $gross_goal->goals_achieve = $gross_goal->goals_achieve + $prospect->price_quote;
                $gross_goal->save();
            }

            //sales manager goal
            $net_goal = Goal::where(['user_id' => Auth::user()->bdm_id, 'goals_type' => 1])->whereMonth('goals_date', date('m', strtotime($prospect->sale_date)))->whereYear('goals_date', date('Y', strtotime($prospect->sale_date)))->first();
            if ($net_goal) {

                $net_goal->goals_achieve = $net_goal->goals_achieve + $prospect->upfront_value;
                $net_goal->save();
            }

            $gross_goal = Goal::where(['user_id' => Auth::user()->bdm_id, 'goals_type' => 2])->whereMonth('goals_date', date('m', strtotime($prospect->sale_date)))->whereYear('goals_date', date('Y', strtotime($prospect->sale_date)))->first();
            if ($gross_goal) {
                $gross_goal->goals_achieve = $gross_goal->goals_achieve + $prospect->price_quote;
                $gross_goal->save();
            }

            $project = new BdmProject();
            $project->user_id = Auth::user()->bdm_id;
            $project->client_name = $prospect->client_name;
            $project->business_name = $prospect->business_name;
            $project->client_email = $prospect->client_email;
            $project->client_phone = $prospect->client_phone;
            $project->client_address = $prospect->business_address;
            $project->project_value = $prospect->price_quote;
            $project->currency = 'USD'; // default currency 'USD
            $project->payment_mode = $prospect->payment_mode ?? null;
            $project->project_opener = Auth::user()->id;
            $project->project_closer = '';
            $project->project_upfront = $prospect->upfront_value;
            $project->website = $prospect->website;
            $project->sale_date = $prospect->sale_date ?? '';
            $project->comment = $prospect->comments;
            $project->save();

            //prospect milestone
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
                    $upfront->payment_mode = $data['payment_mode'] ?? null;
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

        return redirect()->route('bde-prospects.index')->with('message', 'BdmProspect created successfully.');
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
            return response()->json(['view' => (string)View::make('bde.prospect.show-details')->with(compact('prospect', 'isThat'))]);
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
            $users = User::role(['BUSINESS_DEVELOPMENT_EXCECUTIVE'])->get();
            $prospect = BdmProspect::find($id);
            $type = true;
            return response()->json(['view' => view('bde.prospect.edit', compact('prospect', 'users', 'type'))->render()]);
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
          
            'comments' => 'required',
        ]);
        $data = $request->all();
        $prospect = BdmProspect::findOrfail($id);
        $prospect->user_id = Auth::user()->id;
        $prospect->report_to = Auth::user()->bdm_id;
        $prospect->client_name = $data['client_name'];
        $prospect->business_name = $data['business_name'];
        $prospect->followup_time = $data['followup_time'];
        $prospect->client_email = $data['client_email'];
        $prospect->client_phone = $data['client_phone'];
        $prospect->business_address = $data['business_address'];
        $prospect->website = $data['website'];
        $prospect->status = $data['status'];
        $prospect->followup_date = $data['followup_date'];
        $prospect->upfront_value = $data['upfront_value'] ?? '';
        $prospect->payment_mode = $data['payment_mode'] ?? null;
        $prospect->sale_date = $data['sale_date'] ?? '';
         $prospect->meeting_date = !empty($data['meeting_date']) ? $data['meeting_date'] : null;
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
            $followup->meeting_date = !empty($data['meeting_date']) ? $data['meeting_date'] : null;
            $followup->save();
        }

        if ($data['status'] == 'Win') {
            $prospect = BdmProspect::findOrFail($prospect->id);
            $prospect->is_project = true;
            $prospect->save();

            //sales executive goal
            $net_goal = Goal::where(['user_id' => Auth::user()->id, 'goals_type' => 1])->whereMonth('goals_date', date('m', strtotime($prospect->sale_date)))->whereYear('goals_date', date('Y', strtotime($prospect->sale_date)))->first();
            if ($net_goal) {
                $net_goal->goals_achieve = $net_goal->goals_achieve + $prospect->upfront_value;
                $net_goal->save();
            }
            $gross_goal = Goal::where(['user_id' => Auth::user()->id, 'goals_type' => 2])->whereMonth('goals_date', date('m', strtotime($prospect->sale_date)))->whereYear('goals_date', date('Y', strtotime($prospect->sale_date)))->first();
            if ($gross_goal) {
                $gross_goal->goals_achieve = $gross_goal->goals_achieve + $prospect->price_quote;
                $gross_goal->save();
            }

            //sales manager goal
            $net_goal = Goal::where(['user_id' => Auth::user()->bdm_id, 'goals_type' => 1])->whereMonth('goals_date', date('m', strtotime($prospect->sale_date)))->whereYear('goals_date', date('Y', strtotime($prospect->sale_date)))->first();
            if ($net_goal) {
                $net_goal->goals_achieve = $net_goal->goals_achieve + $prospect->upfront_value;
                $net_goal->save();
            }

            $gross_goal = Goal::where(['user_id' => Auth::user()->bdm_id, 'goals_type' => 2])->whereMonth('goals_date', date('m', strtotime($prospect->sale_date)))->whereYear('goals_date', date('Y', strtotime($prospect->sale_date)))->first();
            if ($gross_goal) {
                $gross_goal->goals_achieve = $gross_goal->goals_achieve + $prospect->price_quote;
                $gross_goal->save();
            }

            $project = new BdmProject();
            $project->user_id = Auth::user()->bdm_id;
            $project->client_name = $prospect->client_name;
            $project->business_name = $prospect->business_name;
            $project->client_email = $prospect->client_email;
            $project->client_phone = $prospect->client_phone;
            $project->client_address = $prospect->business_address;
            $project->project_value = $prospect->price_quote;
            $project->currency = 'USD'; // default currency 'USD
            $project->payment_mode = $prospect->payment_mode ?? null;
            $project->project_opener = Auth::user()->id;
            $project->project_closer = '';
            $project->project_upfront = $prospect->upfront_value;
            $project->website = $prospect->website;
            $project->sale_date = $prospect->sale_date;
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
                    $upfront->payment_mode = $data['payment_mode'] ?? null;
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


        return redirect()->route('bde-prospects.index')->with('message', 'BdmProspect updated successfully.');
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
                        ->whereHas('user', function ($q) use ($query) {
                            $q->where('name', 'like', '%' . $query . '%');
                        })
                        ->orWhereHas('transferTakenBy', function ($q) use ($query) {
                            $q->where('name', 'like', '%' . $query . '%');
                        });
                });
            }
            if ($status == 'All') {
                $prospects = $prospects->orderBy('id', 'desc')->where('user_id', Auth::user()->id)->paginate('10');
            } else {
                $prospects = $prospects->orderBy('id', 'desc')->where(['status' => $status])->where('user_id', Auth::user()->id)->paginate('10');
            }

            return response()->json(['data' => view('bde.prospect.table', compact('prospects'))->render()]);
        }
    }
}
