<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Goal;
use App\Models\Project;
use App\Models\ProjectMilestone;
use App\Models\ProjectType;
use App\Models\ProjectDocument;
use App\Models\User;
use App\Traits\ImageTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    use ImageTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sales_managers = User::Role(['SALES_MANAGER','ACCOUNT_MANAGER','BUSINESS_DEVELOPMENT_MANAGER'])->orderBy('name', 'DESC')->where('status', 1)->get();
        $users = User::role(['SALES_MANAGER', 'ACCOUNT_MANAGER', 'SALES_EXCUETIVE','BUSINESS_DEVELOPMENT_MANAGER','BUSINESS_DEVELOPMENT_EXCECUTIVE'])->where('status', 1)->orderBy('id', 'desc')->get();
        $account_managers = User::role('ACCOUNT_MANAGER')->orderBy('name', 'DESC')->where('status', 1)->get();
        $project_openers = User::role(['ACCOUNT_MANAGER', 'SALES_EXCUETIVE','BUSINESS_DEVELOPMENT_EXCECUTIVE'])->where('status', 1)->orderBy('id', 'desc')->get();
        if ($request->sales_manager_id) {
            $projects = Project::orderBy('sale_date', 'desc')->where('user_id', $request->sales_manager_id)->paginate(15);
            return view('admin.project.list')->with(compact('projects','sales_managers','users','account_managers','project_openers'));
        }

        if ($request->account_manager_id) {
            $projects = Project::orderBy('sale_date', 'desc')->where('assigned_to', $request->account_manager_id)->paginate(15);
            return view('admin.project.list')->with(compact('projects','sales_managers','users','account_managers','project_openers'));
        }


        $projects = Project::orderBy('sale_date', 'desc')->paginate(15);
        return view('admin.project.list')->with(compact('projects','sales_managers','users','account_managers','project_openers'));
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::role(['SALES_MANAGER', 'ACCOUNT_MANAGER', 'SALES_EXCUETIVE','BUSINESS_DEVELOPMENT_MANAGER','BUSINESS_DEVELOPMENT_EXCECUTIVE'])->where('status', 1)->orderBy('id', 'desc')->get();
        $sales_managers = User::Role(['SALES_MANAGER','ACCOUNT_MANAGER','BUSINESS_DEVELOPMENT_MANAGER'])->orderBy('name', 'DESC')->where('status', 1)->get();
        $project_openers = User::role(['ACCOUNT_MANAGER', 'SALES_EXCUETIVE','BUSINESS_DEVELOPMENT_EXCECUTIVE'])->where('status', 1)->orderBy('id', 'desc')->get();
        return view('admin.project.create')->with(compact('sales_managers', 'users', 'project_openers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $userRole = User::find($request->project_opener);
        if ($userRole->hasRole('SALES_EXCUETIVE')) {
            $request->merge(['user_id' => $userRole->sales_manager_id ]);
        } elseif ($userRole->hasRole('BUSINESS_DEVELOPMENT_EXCECUTIVE')) {
            $request->merge(['user_id' => $userRole->bdm_id]);
        } else {
            $request->merge(['user_id' => $request->project_opener]);
        }


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

        $project = new Project();
        $project->user_id = $data['user_id'];
        $project->assigned_to = $data['assigned_to'];

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
        $project->project_closer = $data['project_closer'];
        $project->project_upfront = $data['project_upfront'];
        $project->website = $data['website'];
        $project->sale_date = $data['sale_date'] ?? '';
        $project->assigned_date = '';
        $project->delivery_tat = $data['delivery_tat'];
        $project->comment = $data['comment'];
        $project->save();

        $project_type = new ProjectType();
        $project_type->project_id = $project->id;
        $project_type->type = $data['project_type'];
        if ($data['project_type'] == 'Other') {
            $project_type->name = $data['other_value'];
        } else {
            $project_type->name = $data['project_type'];
        }
        $project_type->save();

        if (isset($data['milestone_name'])) {
            foreach ($data['milestone_name'] as $key => $milestone) {
                //check if data is null
                if ($data['milestone_name'][$key] != null) {
                    $project_milestone = new ProjectMilestone();
                    $project_milestone->project_id = $project->id;
                    $project_milestone->milestone_name = $milestone;
                    $project_milestone->milestone_value = $data['milestone_value'][$key];
                    $project_milestone->payment_status = $data['payment_status'][$key];
                    // $project_milestone->payment_date =($data['payment_status'][$key] == 'Paid') ? date('Y-m-d') : '';
                    $project_milestone->milestone_comment = $data['milestone_comment'][$key];
                    $project_milestone->payment_mode = $data['milestone_payment_mode'][$key];
                    $project_milestone->payment_date = $data['milestone_payment_date'][$key];
                    $project_milestone->save();
                }
            }
        }


        if (isset($data['pdf'])) {
            foreach ($data['pdf'] as $key => $pdfFile) {
                $project_pdf = new ProjectDocument();
                $project_pdf->project_id = $project->id;
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


        return redirect()->route('sales-projects.index')->with('message', 'Project created successfully.');
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
            $project = Project::find($id);
            $documents = ProjectDocument::where('project_id', $id)->orderBy('id', 'desc')->get();
            $account_managers = User::role('ACCOUNT_MANAGER')->orderBy('name', 'DESC')->where('status', 1)->get();
            return view('admin.project.view')->with(compact('project', 'account_managers', 'documents'));
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
            $users = User::role(['SALES_MANAGER', 'ACCOUNT_MANAGER', 'SALES_EXCUETIVE','BUSINESS_DEVELOPMENT_MANAGER','BUSINESS_DEVELOPMENT_EXCECUTIVE'])->where('status', 1)->orderBy('id', 'desc')->get();
            $sales_managers = User::Role(['SALES_MANAGER','ACCOUNT_MANAGER','BUSINESS_DEVELOPMENT_MANAGER'])->get();
            $account_managers = User::role('ACCOUNT_MANAGER')->orderBy('name', 'DESC')->where('status', 1)->get();
            $project_openers = User::role(['ACCOUNT_MANAGER', 'SALES_EXCUETIVE','BUSINESS_DEVELOPMENT_EXCECUTIVE'])->where('status', 1)->orderBy('id', 'desc')->get();
            $project = Project::find($id);
            $type = true;
            return response()->json(['view' => view('admin.project.edit', compact('project', 'sales_managers', 'users', 'type','account_managers','project_openers'))->render()]);
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
        $userRole = User::find($request->project_opener);
        if ($userRole->hasRole('SALES_EXCUETIVE')) {
            $request->merge(['user_id' => $userRole->sales_manager_id ]);
        } elseif ($userRole->hasRole('BUSINESS_DEVELOPMENT_EXCECUTIVE')) {
            $request->merge(['user_id' => $userRole->bdm_id]);
        } else {
            $request->merge(['user_id' => $request->project_opener]);
        }

        $data = $request->all();
        $project = Project::findOrfail($id);
        $user_id = $project->user_id;
        $project->user_id = $data['user_id'];
        $project->assigned_to = $data['assigned_to'] ?? null;
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
        $project->project_closer = $data['project_closer'];
        $project->project_upfront = $data['project_upfront'];
        $project->website = $data['website'];
        $project->sale_date = $data['sale_date'] ?? '';
        $project->assigned_date = '';
        $project->delivery_tat = $data['delivery_tat'];
        $project->comment = $data['comment'];
        $project->save();

        ProjectType::where('project_id', $id)->delete();

        $project_type = new ProjectType();
        $project_type->project_id = $project->id;
        $project_type->type = $data['project_type'];
        if ($data['project_type'] == 'Other') {
            $project_type->name = $data['other_value'];
        } else {
            $project_type->name = $data['project_type'];
        }

        if (isset($data['start_date'])) {
            $project_type->start_date = $data['start_date'];
            $project_type->end_date = $data['end_date'];
        }

        $project_type->save();

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
                    $project_milestone->payment_status = $data['payment_status'][$key];
                    // $project_milestone->payment_date = ($data['payment_status'][$key] == 'Paid') ? date('Y-m-d') : '';
                    $project_milestone->milestone_comment = $data['milestone_comment'][$key];
                    $project_milestone->payment_mode = $data['milestone_payment_mode'][$key];
                    $project_milestone->payment_date = $data['milestone_payment_date'][$key];
                    $project_milestone->save();
                }
            }
        }



        if (isset($data['pdf'])) {
            foreach ($data['pdf'] as $key => $pdfFile) {
                $project_pdf = new ProjectDocument();
                $project_pdf->project_id = $project->id;
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

        return redirect()->route('sales-projects.index')->with('message', 'Project updated successfully.');
    }

    public function DocumentDownload($id)
    {
        $project_document = ProjectDocument::find($id);
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
        $project = Project::find($id);
        $project->delete();
        return redirect()->back()->with('message', 'Project deleted successfully.');
    }

    public function projectAssignTo(Request $request)
    {
        if ($request->ajax()) {
            $data = $request->all();
            $project = Project::find($data['project_id']);
            $project->assigned_to = $data['assigned_to'];
            $project->assigned_date = date('Y-m-d');
            $project->save();

            // $countGoal = Goal::where('user_id', $data['assigned_to'])->whereMonth('goals_date', date('m'))->whereYear('goals_date', date('Y'))->count();
            // if ($countGoal > 0) {
            //     $milestone = ProjectMilestone::where(['project_id' => $data['project_id'], 'payment_status' => 'Paid'])->whereMonth('payment_date', date('m'))->whereYear('payment_date', date('Y'))->sum('milestone_value');
            //     $goal = Goal::where('user_id', $data['assigned_to'])->whereMonth('goals_date', date('m'))->whereYear('goals_date', date('Y'))->first();
            //     $goal->goals_achieve = $goal->goals_achieve + $milestone;
            //     $goal->save();
            // }
            return response()->json(['status' => 'success', 'message' => 'Project assigned successfully.']);
        }
    }

    public function fetchData(Request $request)
    {
        if ($request->ajax()) {
            $sort_by = $request->get('sortby');
            $sort_type = $request->get('sorttype');
            $query = $request->get('query');
            $query = str_replace(" ", "%", $query);
            $projects = Project::where('id', 'like', '%' . $query . '%')
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

            return response()->json(['data' => view('admin.project.table', compact('projects'))->render()]);
        }
    }

    public function newCustomer(Request $request)
    {
        if ($request->ajax()) {
            $customers = Customer::orderBy('customer_name', 'asc')->get();
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
