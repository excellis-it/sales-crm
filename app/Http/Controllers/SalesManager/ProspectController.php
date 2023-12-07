<?php

namespace App\Http\Controllers\SalesManager;

use App\Http\Controllers\Controller;
use App\Models\Goal;
use App\Models\Project;
use App\Models\ProjectType;
use Illuminate\Http\Request;
use App\Models\Prospect;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
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
        if ($request->user_id) {
            $count['total'] = Prospect::where('report_to', auth()->user()->id)->where('user_id', $request->user_id)->count();
            $count['win'] = Prospect::where('report_to', auth()->user()->id)->where('user_id', $request->user_id)->where('status', 'Win')->count();
            $count['follow_up'] = Prospect::where('report_to', auth()->user()->id)->where('user_id', $request->user_id)->where('status', 'Follow Up')->count();
            $count['close'] = Prospect::where('report_to', auth()->user()->id)->where('user_id', $request->user_id)->where('status', 'Close')->count();
            $count['sent_proposal'] = Prospect::where('report_to', auth()->user()->id)->where('user_id', $request->user_id)->where('status', 'Sent Proposal')->count();
            $prospects = Prospect::where('report_to', Auth::user()->id)->where('user_id', $request->user_id)->orderBy('sale_date', 'desc')->paginate(15);
            $users = User::role(['SALES_MANAGER', 'ACCOUNT_MANAGER', 'SALES_EXCUETIVE'])->get();
            $sales_executives = User::role('SALES_EXCUETIVE')->where(['status' => 1, 'sales_manager_id' => Auth::user()->id])->orderBy('id', 'desc')->get();
            return view('sales_manager.prospect.list')->with(compact('prospects', 'count', 'users', 'sales_executives'));
        }
        $count['win'] = Prospect::where('report_to', auth()->user()->id)->where('status', 'Win')->count();
        $count['follow_up'] = Prospect::where('report_to', auth()->user()->id)->where('status', 'Follow Up')->count();
        $count['close'] = Prospect::where('report_to', auth()->user()->id)->where('status', 'Close')->count();
        $count['sent_proposal'] = Prospect::where('report_to', auth()->user()->id)->where('status', 'Sent Proposal')->count();
        $prospects = Prospect::where('report_to', Auth::user()->id)->orderBy('sale_date', 'desc')->paginate(15);
        $users = User::role(['SALES_MANAGER', 'ACCOUNT_MANAGER', 'SALES_EXCUETIVE'])->get();
        $sales_executives = User::role('SALES_EXCUETIVE')->where(['status' => 1, 'sales_manager_id' => Auth::user()->id])->orderBy('id', 'desc')->get();
        return view('sales_manager.prospect.list')->with(compact('prospects', 'count', 'users', 'sales_executives'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::role(['SALES_MANAGER', 'ACCOUNT_MANAGER', 'SALES_EXCUETIVE'])->get();
        $sales_executives = User::role('SALES_EXCUETIVE')->where(['status' => 1, 'sales_manager_id' => Auth::user()->id])->orderBy('id', 'desc')->get();
        return view('sales_manager.prospect.create')->with(compact('sales_executives', 'users'));
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

        $prospect = new Prospect();
        $prospect->user_id = $data['user_id'];
        $prospect->report_to = Auth::user()->id;
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

        if ($data['status'] == 'Win') {
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
            $project->sale_date = $prospect->sale_date;
            $project->comment = $prospect->comments;
            $project->save();

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

        return redirect()->route('sales-manager.prospects.index')->with('message', 'Prospect created successfully.');
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
            return response()->json(['view' => (string)View::make('sales_manager.prospect.show-details')->with(compact('prospect', 'isThat'))]);
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
            $prospect = Prospect::find($id);
            $users = User::role(['SALES_MANAGER', 'ACCOUNT_MANAGER', 'SALES_EXCUETIVE'])->get();
            $sales_executives = User::role('SALES_EXCUETIVE')->where(['status' => 1, 'sales_manager_id' => Auth::user()->id])->orderBy('id', 'desc')->get();
            $type = true;
            return response()->json(['view' => (string)View::make('sales_manager.prospect.edit')->with(compact('prospect', 'sales_executives', 'users','type'))]);
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
        $prospect = Prospect::findOrfail($id);
        $prospect->user_id = $data['user_id'];
        $prospect->report_to = Auth::user()->id;
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

        if ($data['status'] == 'Win') {
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
            $project->sale_date = $prospect->sale_date;
            $project->comment = $prospect->comments;
            $project->save();

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
        return redirect()->route('sales-manager.prospects.index')->with('message', 'Prospect updated successfully.');
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

    // public function filter(Request $request)
    // {
    //     if ($request->ajax()) {
    //         $sort_by = $request->get('sortby');
    //         $sort_type = $request->get('sorttype');
    //         $query = $request->get('query');
    //         $query = str_replace(" ", "%", $query);

    //         $prospects = Prospect::where('report_to', Auth::user()->id)->orderBy($sort_by, $sort_type)->where(function ($q) use ($query) {
    //             $q->orWhere('sale_date', 'like', '%' . $query . '%')
    //                 ->orWhere('client_name', 'like', '%' . $query . '%')
    //                 ->orWhere('business_name', 'like', '%' . $query . '%')
    //                 ->orWhere('client_email', 'like', '%' . $query . '%')
    //                 ->orWhere('client_phone', 'like', '%' . $query . '%')
    //                 ->orWhere('status', 'like', '%' . $query . '%')
    //                 ->orWhere('offered_for', 'like', '%' . $query . '%')
    //                 ->orWhere('followup_date', 'like', '%' . $query . '%')
    //                 ->orWhere('price_quote', 'like', '%' . $query . '%')
    //                 ->orWhereHas('user', function ($q) use ($query) {
    //                     $q->Where('name', 'like', '%' . $query . '%');
    //                 })

    //                 ->orWhereHas('transferTakenBy', function ($q) use ($query) {
    //                     $q->Where('name', 'like', '%' . $query . '%');
    //                 });

    //         })->paginate(15);
    //         return response()->json(['data' => view('sales_manager.prospect.table', compact('prospects'))->render()]);
    //     }
    // }

    public function prospectStatusFilter(Request $request)
    {

        if ($request->ajax()) {
            $status = $request->status;
            $query = $request->get('query');
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
                        ->whereHas('user', function ($q) use ($query) {
                            $q->where('name', 'like', '%' . $query . '%');
                        })->whereHas('transferTakenBy', function ($q) use ($query) {
                            $q->where('name', 'like', '%' . $query . '%');
                        });
                });
            }
            if ($request->user_id) {
                $prospects = $prospects->where('user_id', $request->user_id);
            }
            if ($status == 'All') {
                $prospects = $prospects->orderBy('sale_date', 'desc')->where('report_to', Auth::user()->id)->paginate('10');
            } else {
                $prospects = $prospects->orderBy('sale_date', 'desc')->where(['status' => $status])->where('report_to', Auth::user()->id)->paginate('10');
            }

            return response()->json(['data' => view('sales_manager.prospect.table', compact('prospects'))->render()]);
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
            $project->sale_date = $prospect->sale_date;
            $project->comment = $prospect->comments;
            $project->save();

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

            return redirect()->route('sales-manager.prospects.index')->with('message', 'Prospect converted to project successfully.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
}
