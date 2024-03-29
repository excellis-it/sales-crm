<?php

namespace App\Http\Controllers\SalesManager;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Goal;
use App\Models\Project;
use App\Models\ProjectDocument;
use App\Models\ProjectMilestone;
use App\Models\ProjectType;
use App\Models\User;
use Session;
use App\Traits\ImageTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    use ImageTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response     
     */
    public function index()
    {
        $projects = Project::where('user_id', Auth::user()->id)->orderBy('sale_date', 'desc')->paginate(15);
        $account_managers = User::role('ACCOUNT_MANAGER')->orderBy('name', 'DESC')->where('status', 1)->get();
        $users = User::role(['SALES_MANAGER', 'ACCOUNT_MANAGER', 'SALES_EXCUETIVE', 'BUSINESS_DEVELOPMENT_MANAGER', 'BUSINESS_DEVELOPMENT_EXCECUTIVE'])->where('status', 1)->orderBy('id', 'desc')->get();
        $project_openers = User::role(['SALES_EXCUETIVE'])->where(['sales_manager_id' => Auth::user()->id, 'status' => 1])->orderBy('id', 'desc')->get();
        return view('sales_manager.project.list')->with(compact('projects', 'users', 'project_openers', 'account_managers'));
    }

    public function filterProject(Request $request)
    {
        // return $request->all();
        if ($request->ajax()) {
            $sort_by = $request->get('sortby');
            $sort_type = $request->get('sorttype');
            $query = $request->get('query');
            $query = str_replace(" ", "%", $query);

            $projects = Project::where('user_id', Auth::user()->id)->orderBy($sort_by, $sort_type)->where(function ($q) use ($query) {
                $q->orWhere('sale_date', 'like', '%' . $query . '%')
                    ->orWhere('business_name', 'like', '%' . $query . '%')
                    ->orWhere('business_name', 'like', '%' . $query . '%')
                    ->orWhere('client_name', 'like', '%' . $query . '%')
                    ->orWhere('client_phone', 'like', '%' . $query . '%')
                    ->orWhere('project_value', 'like', '%' . $query . '%')
                    ->orWhere('project_upfront', 'like', '%' . $query . '%')
                    ->orWhere('currency', 'like', '%' . $query . '%')
                    ->orWhere('payment_mode', 'like', '%' . $query . '%')
                    ->orWhereHas('projectTypes', function ($q) use ($query) {
                        $q->Where('type', 'like', '%' . $query . '%');
                    })
                    ->orWhereRaw('project_value - project_upfront like ?', ["%{$query}%"]);
            })->paginate(15);


            Session::put('call_status',$request->get('call_status'));
            if($request->get('call_status') == '')
            {
                $page = Session::put('page_number',1); 
            }
            if(Session::get('call_status') == 'Yes') {
                Session::put('call_status',"");
                Session::put('update_success',false);
            }

            return response()->json(['data' => view('sales_manager.project.table', compact('projects'))->render()]);
        }
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::role(['SALES_MANAGER', 'ACCOUNT_MANAGER', 'SALES_EXCUETIVE'])->orderBy('id', 'desc')->get();
        return view('sales_manager.project.create')->with(compact('users'));
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

        $project = new Project();
        $project->user_id = Auth::user()->id;
        $project->customer_id = $data['customer'];
        $project->client_name = $data['client_name'];
        $project->business_name = $data['business_name'];
        $project->assigned_to = $data['assigned_to'];
        $project->assigned_date = date('Y-m-d');
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
        $project->sale_date = $data['sale_date'];
        $project->assigned_date = '';
        $project->delivery_tat = $data['delivery_tat'];
        $project->comment = $data['comment'];
        $project->save();

        $countGross = Goal::where('user_id', Auth::user()->id)->whereMonth('goals_date', date('m', strtotime($data['sale_date'])))->whereYear('goals_date', date('Y', strtotime($data['sale_date'])))->where('goals_type', 1)->count();
        if ($countGross > 0) {
            $goals = Goal::where('user_id', Auth::user()->id)->whereMonth('goals_date', date('m', strtotime($data['sale_date'])))->whereYear('goals_date', date('Y', strtotime($data['sale_date'])))->where('goals_type', 1)->first();
            $goals->goals_achieve = $goals->goals_achieve + $data['project_value'];
            $goals->save();
        }

        $countNet = Goal::where('user_id', Auth::user()->id)->whereMonth('goals_date', date('m', strtotime($data['sale_date'])))->whereYear('goals_date', date('Y', strtotime($data['sale_date'])))->where('goals_type', 2)->count();
        if ($countNet > 0) {
            $goals = Goal::where('user_id', Auth::user()->id)->whereMonth('goals_date', date('m', strtotime($data['sale_date'])))->whereYear('goals_date', date('Y', strtotime($data['sale_date'])))->where('goals_type', 2)->first();
            $goals->goals_achieve = $goals->goals_achieve + $data['project_upfront'];
            $goals->save();
        }

        if (isset($data['project_type'])) {
            foreach ($data['project_type'] as $key => $project_type) {
                $add_project_type = new ProjectType();
                $add_project_type->project_id = $project->id;
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
        return redirect()->route('projects.index')->with('message', 'Project created successfully.');
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
            return view('sales_manager.project.view')->with(compact('project', 'documents'));
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
            $users = User::role(['SALES_MANAGER', 'ACCOUNT_MANAGER', 'SALES_EXCUETIVE', 'BUSINESS_DEVELOPMENT_MANAGER', 'BUSINESS_DEVELOPMENT_EXCECUTIVE'])->where('status', 1)->orderBy('id', 'desc')->get();
            $project_openers = User::role(['SALES_EXCUETIVE'])->where(['sales_manager_id' => Auth::user()->id, 'status' => 1])->orderBy('id', 'desc')->get();
            $account_managers = User::role('ACCOUNT_MANAGER')->orderBy('name', 'DESC')->where('status', 1)->get();
            $project = Project::find($id);
            $type = true;
            return response()->json(['view' => view('sales_manager.project.edit', compact('project', 'users', 'type','project_openers','account_managers'))->render()]);
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
        $project = Project::findOrfail($id);
        $project_value = $project->project_value;
        $project_upfront = $project->project_upfront;

        $project->user_id = Auth::user()->id;
        $project->client_name = $data['client_name'];
        $project->assigned_to = $data['assigned_to'];
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
        $project->sale_date = $data['sale_date'];
        $project->assigned_date = '';
        $project->delivery_tat = $data['delivery_tat'];
        $project->comment = $data['comment'];
        $project->save();

        $countGross = Goal::where('user_id', Auth::user()->id)->whereMonth('goals_date', date('m', strtotime($data['sale_date'])))->whereYear('goals_date', date('Y', strtotime($data['sale_date'])))->where('goals_type', 1)->count();
        if ($countGross > 0) {
            $goals = Goal::where('user_id', Auth::user()->id)->whereMonth('goals_date', date('m', strtotime($data['sale_date'])))->whereYear('goals_date', date('Y', strtotime($data['sale_date'])))->where('goals_type', 1)->first();
            $goals->goals_achieve = ($goals->goals_achieve - $project_value) + $data['project_value'];
            $goals->save();
        }

        $countNet = Goal::where('user_id', Auth::user()->id)->whereMonth('goals_date', date('m', strtotime($data['sale_date'])))->whereYear('goals_date', date('Y', strtotime($data['sale_date'])))->where('goals_type', 2)->count();
        if ($countNet > 0) {
            $goals = Goal::where('user_id', Auth::user()->id)->whereMonth('goals_date', date('m', strtotime($data['sale_date'])))->whereYear('goals_date', date('Y', strtotime($data['sale_date'])))->where('goals_type', 2)->first();
            $goals->goals_achieve = ($goals->goals_achieve - $project_upfront) + $data['project_upfront'];
            $goals->save();
        }

        // ProjectType::where('project_id', $id)->delete();

        // $project_type = new ProjectType();
        // $project_type->project_id = $project->id;
        // $project_type->type = $data['project_type'];
        // if ($data['project_type'] == 'Other') {
        //     $project_type->name = $data['other_value'];
        // } else {
        //     $project_type->name = $data['project_type'];
        // }

        // $project_type->save();

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
                    $project_milestone->payment_mode = $data['milestone_payment_mode'][$key] ?? '';
                    $project_milestone->payment_date = $data['milestone_payment_date'][$key] ?? '';
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

        Session::put('page_number',$request->page_no);
        $update_success = Session::put('update_success',true);
        // return "d";
        return redirect()->route('projects.index')->with('message', 'Project updated successfully.');
    }

    public function projectDocumentDownload($id)
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
