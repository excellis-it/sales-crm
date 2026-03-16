<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BdmFollowup;
use App\Models\Customer;
use App\Models\Followup;
use App\Models\Goal;
use App\Models\BdmProject;
use App\Models\ProjectMilestone;
use App\Models\BdmProjectType;
use App\Models\BdmProjectDocument;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use App\Traits\ImageTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BdmProjectController extends Controller
{
    use ImageTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // return Session::get('call_status');


        $sales_managers = User::Role(['BUSINESS_DEVELOPMENT_MANAGER'])->orderBy('name', 'DESC')->where('status', 1)->get();
        $users = User::role(['BUSINESS_DEVELOPMENT_MANAGER'])->where('status', 1)->orderBy('id', 'desc')->get();
        $account_managers = User::role('ACCOUNT_MANAGER')->orderBy('name', 'DESC')->where('status', 1)->get();
        $project_openers = User::role(['BUSINESS_DEVELOPMENT_EXCECUTIVE'])->where('status', 1)->orderBy('id', 'desc')->get();



        $projects = BdmProject::orderBy('sale_date', 'desc')->paginate(15);
        return view('admin.bdm-project.list')->with(compact('projects','sales_managers','users','account_managers','project_openers'));
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::role(['BUSINESS_DEVELOPMENT_MANAGER', 'BUSINESS_DEVELOPMENT_EXCECUTIVE'])->where('status', 1)->orderBy('id', 'desc')->get();
        $sales_managers = User::Role(['BUSINESS_DEVELOPMENT_MANAGER'])->orderBy('name', 'DESC')->where('status', 1)->get();
        $project_openers = User::role(['BUSINESS_DEVELOPMENT_MANAGER', 'BUSINESS_DEVELOPMENT_EXCECUTIVE'])->where('status', 1)->orderBy('id', 'desc')->get();
        return view('admin.bdm-project.create')->with(compact('sales_managers', 'users', 'project_openers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


        $data = $request->all();
        if ($data['customer'] == 0) {  //new customer == 1 and existing customer == 0
            $data['customer'] = $request->customer_id;
        } else {
            $customer = new Customer();
            $customer->customer_name = $data['client_name'];
            $customer->customer_email = $data['client_email'];
            $customer->customer_phone = $data['client_phone'];
            $customer->customer_address = $data['client_address'];
            $customer->save();
            $data['customer'] = $customer->id;
        }

        $project = new BdmProject();
        $project->user_id = $data['user_id'];
        // $project->assigned_to = $data['assigned_to'];
        $project->assigned_date = date('Y-m-d');
        $project->customer_id = $data['customer'];
        $project->client_name = $data['client_name'];
        $project->business_name = $data['business_name'];
        $project->client_email = $data['client_email'];
        $project->client_phone = $data['client_phone'];
        $project->client_address = $data['client_address'];
        $project->project_description = $data['project_description'] ?? '';
        $project->project_value = $data['project_value'];
        $project->currency = $data['currency'];
        $project->payment_mode = $data['payment_mode'];
        $project->project_opener = $data['project_opener'];
        $project->project_closer = $data['project_closer'] ?? null;
        $project->project_upfront = $data['project_upfront'];
        $project->website = $data['website'] ?? null;
        $project->sale_date = $data['sale_date'] ?? '';
        // $project->assigned_date = $project->assigned_to ? date('Y-m-d') : null;
        $project->delivery_tat = $data['delivery_tat'] ?? null;
        $project->comment = $data['comment'] ?? '';
        $project->save();

            if (isset($data['comment']) && $data['comment'] != null) {
            $followup = new BdmFollowup();
            $followup->user_id = auth()->id();
            $followup->bdm_project_id = $project->id;
            $followup->remark = $data['comment'];
            $followup->save();
        }


        if (isset($data['project_type'])) {
            foreach ($data['project_type'] as $key => $project_type) {
                $add_project_type = new BdmProjectType();
                $add_project_type->bdm_project_id = $project->id;
                $add_project_type->type = $project_type;
                if ($project_type == 'Other') {
                    $add_project_type->name = $data['other_value'];
                } else {
                    $add_project_type->name = $project_type;
                }
                $add_project_type->save();
            }
        }

        if (isset($data['milestone_name'])) {
            foreach ($data['milestone_name'] as $key => $milestone) {
                //check if data is null
                if ($data['milestone_name'][$key] != null) {
                    $project_milestone = new ProjectMilestone();
                    $project_milestone->bdm_project_id = $project->id;
                    $project_milestone->milestone_name = $milestone;
                    $project_milestone->milestone_value = $data['milestone_value'][$key];
                    $project_milestone->payment_status = $data['payment_status'][$key];
                    $project_milestone->milestone_comment = $data['milestone_comment'][$key];
                    if ($data['payment_status'][$key] == 'Paid') {
                        $project_milestone->payment_mode = $data['milestone_payment_mode'][$key] ?? null;
                        $project_milestone->payment_date = $data['milestone_payment_date'][$key] ?? null;
                    }
                    $project_milestone->save();
                }
            }
        }


        if (isset($data['pdf'])) {
            foreach ($data['pdf'] as $key => $pdfFile) {
                $project_pdf = new BdmProjectDocument();
                $project_pdf->bdm_project_id = $project->id;
                $project_pdf->document_file = $this->imageUpload($pdfFile, 'project_pdf');
                $project_pdf->save();
            }
        }

        $user = User::findOrFail($data['user_id']);
        if ($user->hasRole('SALES_MANAGER') || $user->hasRole('BUSINESS_DEVELOPMENT_MANAGER')) {
            $gross_goals = Goal::where('user_id', $data['user_id'])->whereMonth('goals_date', date('m', strtotime($data['sale_date'])))->whereYear('goals_date', date('Y', strtotime($data['sale_date'])))->where('goals_type', 1)->first();
            if ($gross_goals) {
                $gross_goals->goals_achieve = $gross_goals->goals_achieve + $data['project_value'];
                $gross_goals->save();
            }

            $net_goals = Goal::where('user_id', $data['user_id'])->whereMonth('goals_date', date('m', strtotime($data['sale_date'])))->whereYear('goals_date', date('Y', strtotime($data['sale_date'])))->where('goals_type', 2)->first();
            if ($net_goals) {
                $net_goals->goals_achieve = $net_goals->goals_achieve + $data['project_upfront'];
                $net_goals->save();
            }
        }


        return redirect()->route('admin.bdm-projects.index')->with('message', 'BdmProject created successfully.');
        // } catch (\Throwable $th) {
        //     return redirect()->back()->with('error', $th->getMessage());
        // }
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
            $project = BdmProject::find($id);
            $documents = BdmProjectDocument::where('bdm_project_id', $id)->orderBy('id', 'desc')->get();
            $account_managers = User::role('ACCOUNT_MANAGER')->orderBy('name', 'DESC')->where('status', 1)->get();
            return view('admin.bdm-project.view')->with(compact('project', 'account_managers', 'documents'));
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
        // dd($id);

        try {
            $users = User::role(['BUSINESS_DEVELOPMENT_MANAGER'])->where('status', 1)->orderBy('id', 'desc')->get();
            $sales_managers = User::Role(['BUSINESS_DEVELOPMENT_MANAGER'])->get();
            $account_managers = User::role('ACCOUNT_MANAGER')->orderBy('name', 'DESC')->where('status', 1)->get();
            $project_openers = User::role(['BUSINESS_DEVELOPMENT_EXCECUTIVE'])->where('status', 1)->orderBy('id', 'desc')->get();
            $project = BdmProject::find($id);
            // $followups = Followup::where('bdm_project_id', $id)->get();
            // return $project->projectTypes()->pluck('type');
            $type = true;
            return response()->json(['view' => view('admin.bdm-project.edit', compact('project', 'sales_managers', 'users', 'type','account_managers','project_openers'))->render()]);
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

        // dd($data);
        if ($data['customer'] == 0) {  //new customer == 1 and existing customer == 0
            $data['customer'] = $request->customer_id;
        } else {
            $customer = new Customer();
            $customer->customer_name = $data['client_name'];
            $customer->customer_email = $data['client_email'];
            $customer->customer_phone = $data['client_phone'];
            $customer->customer_address = $data['client_address'];
            $customer->save();
            $data['customer'] = $customer->id;
        }

        $project = BdmProject::findOrfail($id);
        $user_id = $project->user_id;
        $project->user_id = $data['user_id'];
        // $project->assigned_to = $data['assigned_to'] ?? null;
        $project->assigned_date = date('Y-m-d');
        $project->client_name = $data['client_name'];
        $project->business_name = $data['business_name'];
        $project->client_email = $data['client_email'];
        $project->client_phone = $data['client_phone'];
        $project->client_address = $data['client_address'];
        $project->project_description = $data['project_description'] ?? '';
        $project->project_value = $data['project_value'];
        $project->currency = $data['currency'];
        $project->payment_mode = $data['payment_mode'];
        $project->project_opener = $data['project_opener'];
        $project->project_closer = $data['project_closer'] ?? null;
        $project->project_upfront = $data['project_upfront'];
        $project->website = $data['website'] ?? null;
        $project->sale_date = $data['sale_date'] ?? '';

        // if ($project->assigned_to != ($data['assigned_to'] ?? null)) {
        //     $project->assigned_date = date('Y-m-d');
        // }

        // $project->assigned_to = $data['assigned_to'] ?? null;
        $project->delivery_tat = $data['delivery_tat'] ?? null;
        $project->comment = $data['comment'] ?? '';
        $project->save();

        BdmProjectType::where('bdm_project_id', $id)->delete();


        if (isset($data['project_type'])) {
            foreach ($data['project_type'] as $key => $project_type) {
                $update_project_type = new BdmProjectType();
                $update_project_type->bdm_project_id = $project->id;
                $update_project_type->type = $project_type;
                if ($project_type == 'Other') {
                    $update_project_type->name = $data['other_value'] ?? 'Other';
                } else {
                    $update_project_type->name = $project_type;
                }

                if (isset($data['start_date'])) {
                    $update_project_type->start_date = $data['start_date'];
                    $update_project_type->end_date = $data['end_date'];
                }
                $update_project_type->save();
            }
        }

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
                    $project_milestone->payment_status = $data['payment_status'][$key];
                    // $project_milestone->payment_date = ($data['payment_status'][$key] == 'Paid') ? date('Y-m-d') : '';
                    $project_milestone->milestone_comment = $data['milestone_comment'][$key];
                    if($data['payment_status'][$key] == 'Paid')
                    {
                        $project_milestone->payment_mode = $data['milestone_payment_mode'][$key] ?? null;
                        $project_milestone->payment_date = $data['milestone_payment_date'][$key] ?? null;
                    }
                    $project_milestone->save();
                }
            }
        }



        if (isset($data['pdf'])) {
            foreach ($data['pdf'] as $key => $pdfFile) {
                $project_pdf = new BdmProjectDocument();
                $project_pdf->bdm_project_id = $project->id;
                $project_pdf->document_file = $this->imageUpload($pdfFile, 'project_pdf');
                $project_pdf->save();
            }
        }
        // dd($user_id.' '. $data['user_id']);
        if ($user_id != $data['user_id']) {
             $past_user = User::findOrFail($user_id);
            if ($past_user->hasRole('SALES_MANAGER') || $past_user->hasRole('BUSINESS_DEVELOPMENT_MANAGER')) {

                $gross_goals = Goal::where('user_id', $user_id)->whereMonth('goals_date', date('m', strtotime($project->sale_date)))->whereYear('goals_date', date('Y', strtotime($project->sale_date)))->where('goals_type', 1)->first();
                if ($gross_goals) {
                    $gross_goals->goals_achieve = $gross_goals->goals_achieve - $project->project_value;
                    $gross_goals->save();
                }

                $net_goals = Goal::where('user_id', $user_id)->whereMonth('goals_date', date('m', strtotime($project->sale_date)))->whereYear('goals_date', date('Y', strtotime($project->sale_date)))->where('goals_type', 2)->first();
                if ($net_goals) {
                    $net_goals->goals_achieve = $net_goals->goals_achieve - $project->project_upfront;
                    $net_goals->save();
                }
            }

            $user = User::findOrFail($data['user_id']);
            if ($user->hasRole('SALES_MANAGER') || $user->hasRole('BUSINESS_DEVELOPMENT_MANAGER')) {
                $gross_goals = Goal::where('user_id', $data['user_id'])->whereMonth('goals_date', date('m', strtotime($project->sale_date)))->whereYear('goals_date', date('Y', strtotime($project->sale_date)))->where('goals_type', 1)->first();
                if ($gross_goals) {
                    $gross_goals->goals_achieve = $gross_goals->goals_achieve + $project->project_value;
                    $gross_goals->save();
                }

                $net_goals = Goal::where('user_id', $data['user_id'])->whereMonth('goals_date', date('m', strtotime($project->sale_date)))->whereYear('goals_date', date('Y', strtotime($project->sale_date)))->where('goals_type', 2)->first();
                if ($net_goals) {
                    $net_goals->goals_achieve = $net_goals->goals_achieve + $project->project_upfront;
                    $net_goals->save();
                }
            }
        }

        Session::put('page_number',$request->page_no);
        $update_success = Session::put('update_success',true);
        // $url = '/admin/sales-project/?page=' . $page_no;
        // return redirect()->to($url);

        return redirect()->route('admin.bdm-projects.index')->with('message', 'BdmProject updated successfully.');
    }

    public function DocumentDownload($id)
    {
        $project_document = BdmProjectDocument::find($id);
        $file_path = $project_document->document_file;

        return response()->download(storage_path('app/public/' . $file_path));
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
        $project = BdmProject::find($id);
        $project->delete();
        return redirect()->back()->with('message', 'BdmProject deleted successfully.');
    }

    public function projectAssignTo(Request $request)
    {
        if ($request->ajax()) {
            $data = $request->all();
            $project = BdmProject::find($data['bdm_project_id']);
            $project->assigned_to = $data['assigned_to'];
            $project->assigned_date = date('Y-m-d');
            $project->save();

            // $countGoal = Goal::where('user_id', $data['assigned_to'])->whereMonth('goals_date', date('m'))->whereYear('goals_date', date('Y'))->count();
            // if ($countGoal > 0) {
            //     $milestone = ProjectMilestone::where(['bdm_project_id' => $data['bdm_project_id'], 'payment_status' => 'Paid'])->whereMonth('payment_date', date('m'))->whereYear('payment_date', date('Y'))->sum('milestone_value');
            //     $goal = Goal::where('user_id', $data['assigned_to'])->whereMonth('goals_date', date('m'))->whereYear('goals_date', date('Y'))->first();
            //     $goal->goals_achieve = $goal->goals_achieve + $milestone;
            //     $goal->save();
            // }
            return response()->json(['status' => 'success', 'message' => 'BdmProject assigned successfully.']);
        }
    }

    public function fetchData(Request $request)
    {

        if ($request->ajax()) {
            $sort_by = $request->get('sortby');
            $sort_type = $request->get('sorttype');
            $query = $request->get('query');
            $query = str_replace(" ", "%", $query);
            $projects = BdmProject::where('id', 'like', '%' . $query . '%')
                ->orWhere('sale_date', 'like', '%' . $query . '%')
                ->orWhere('client_name', 'like', '%' . $query . '%')
                ->orWhere('business_name', 'like', '%' . $query . '%')
                ->orWhere('client_phone', 'like', '%' . $query . '%')
                ->orWhere('project_value', 'like', '%' . $query . '%')
                ->orWhere('project_upfront', 'like', '%' . $query . '%')
                ->orWhere('currency', 'like', '%' . $query . '%')
                ->orWhere('payment_mode', 'like', '%' . $query . '%')
                ->orderBy($sort_by, $sort_type)
                //sales manager
                ->orWhereHas('salesManager', function ($q) use ($query) {
                    $q->where('name', 'like', '%' . $query . '%');
                })
                ->paginate(15);

                Session::put('call_status',$request->get('call_status'));
                if($request->get('call_status') == '')
                {
                    $page = Session::put('page_number',1);
                }
                if(Session::get('call_status') == 'Yes') {
                    Session::put('call_status',"");
                    Session::put('update_success',false);
                }

            return response()->json(['data' => view('admin.bdm-project.table', compact('projects'))->render()]);
        }
    }

    public function newCustomer(Request $request)
    {
        if ($request->ajax()) {
            $customers = Customer::orderBy('customer_name', 'asc')->where('deleted_at', null)->get();
            return response()->json($customers);
        }
    }

    public function customerDetails(Request $request)
    {
        if ($request->ajax()) {
            $customer = Customer::find($request->customer_id);
            return response()->json($customer);
        }
    }


}
