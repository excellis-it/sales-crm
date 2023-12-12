<?php

namespace App\Http\Controllers\AccountManager;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Goal;
use App\Models\Project;
use App\Models\ProjectMilestone;
use App\Models\ProjectType;
use App\Traits\ImageTrait;
use App\Models\ProjectDocument;
use App\Models\User;
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
        $projects = Project::where('assigned_to', Auth::user()->id)->orderBy('sale_date', 'desc')->paginate(15);
        $users = User::role(['SALES_MANAGER', 'ACCOUNT_MANAGER', 'SALES_EXCUETIVE'])->orderBy('id', 'desc')->get();
        return view('account_manager.project.list', compact('projects', 'users'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::role(['SALES_MANAGER', 'ACCOUNT_MANAGER', 'SALES_EXCUETIVE'])->orderBy('id', 'desc')->get();
        return view('account_manager.project.create')->with(compact('users'));
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
        $project->client_name = $data['client_name'];
        $project->customer_id = $data['customer'];
        $project->business_name = $data['business_name'];
        $project->client_email = $data['client_email'];
        $project->client_phone = $data['client_phone'];
        $project->client_address = $data['client_address'];
        $project->project_description = $data['project_description'] ?? '';
        $project->project_value = $data['project_value'];
        $project->currency = $data['currency'];
        $project->payment_mode = $data['payment_mode'];
        $project->project_opener = Auth::user()->id;
        $project->project_closer = $data['project_closer'];
        $project->project_upfront = $data['project_upfront'];
        $project->website = $data['website'];
        $project->sale_date = $data['sale_date'];
        $project->assigned_date = date('Y-m-d');
        $project->assigned_to = Auth::user()->id;
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

        $net_goals = Goal::where('user_id', Auth::user()->id)->whereMonth('goals_date', date('m', strtotime($data['sale_date'])))->whereYear('goals_date', date('Y', strtotime($data['sale_date'])))->where('goals_type', 2)->first();
        if ($net_goals) {
            $net_goals->goals_achieve = $net_goals->goals_achieve + $data['project_upfront'];
            $net_goals->save();
        }
        if (isset($data['milestone_name'])) {
            // dd($data);
            foreach ($data['milestone_name'] as $key => $milestone) {
                //check if data is null
                if (($data['milestone_name'][$key])) {
                    $project_milestone = new ProjectMilestone();
                    $project_milestone->project_id = $project->id;
                    $project_milestone->milestone_name = $milestone;
                    $project_milestone->milestone_value = $data['milestone_value'][$key];
                    $project_milestone->payment_status = $data['payment_status'][$key];
                    $project_milestone->payment_date = ($data['payment_status'][$key] == 'Paid') ? date('Y-m-d') : '';
                    $project_milestone->milestone_comment = $data['milestone_comment'][$key];
                    $project_milestone->payment_mode = $data['milestone_payment_mode'][$key];
                    $project_milestone->payment_date = $data['milestone_payment_date'][$key];
                    $project_milestone->save();

                    if ($data['payment_status'][$key] == 'Paid') {
                        $net_goals_t = Goal::where('user_id', Auth::user()->id)->whereMonth('goals_date', date('m', strtotime($data['sale_date'])))->whereYear('goals_date', date('Y', strtotime($data['sale_date'])))->where('goals_type', 2)->first();
                        if ($net_goals_t) {
                            $net_goals_t->goals_achieve = $net_goals_t->goals_achieve + $data['milestone_value'][$key];
                            $net_goals_t->save();
                        }
                    }
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

        return redirect()->route('account-manager.projects.index')->with('message', 'Project created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $project = Project::findOrFail($id);
        $documents = ProjectDocument::where('project_id', $id)->orderBy('id', 'desc')->get();
        return view('account_manager.project.view', compact('project', 'documents'));
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
            $project = Project::find($id);
            $type = true;
            return response()->json(['view' => view('account_manager.project.edit', compact('project', 'users', 'type'))->render()]);
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
        $project->user_id = Auth::user()->id;
        $project->client_name = $data['client_name'];
        $project->business_name = $data['business_name'];
        $project->client_email = $data['client_email'];
        $project->client_phone = $data['client_phone'];
        $project->client_address = $data['client_address'];
        $project->project_description = $data['project_description'] ?? '';
        $project->project_value = $data['project_value'];
        $project->currency = $data['currency'];
        $project->payment_mode = $data['payment_mode'];
        $project->project_opener = Auth::user()->id;
        $project->project_closer = $data['project_closer'];
        $project->project_upfront = $data['project_upfront'];
        $project->website = $data['website'];
        $project->sale_date = $data['sale_date'];
        $project->assigned_date = date('Y-m-d');
        $project->assigned_to = Auth::user()->id;
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

        $project_type->save();
        $previous_milestone_value = ProjectMilestone::where(['project_id'=> $id, 'payment_status' => 'Paid'])->sum('milestone_value');

        ProjectMilestone::where('project_id', $id)->delete();
        if (isset($data['milestone_name'])) {
            $paid_amount = 0;
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

                    if ($data['payment_status'][$key] == 'Paid') {
                        $paid_amount += $data['milestone_value'][$key];
                    }
                }

                $net_goals_t = Goal::where('user_id', Auth::user()->id)->whereMonth('goals_date', date('m', strtotime($data['sale_date'])))->whereYear('goals_date', date('Y', strtotime($data['sale_date'])))->where('goals_type', 2)->first();
                if ($net_goals_t) {
                    $net_goals_t->goals_achieve = $net_goals_t->goals_achieve +(( $paid_amount - $previous_milestone_value)) ;
                    $net_goals_t->save();
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

        return redirect()->route('account-manager.projects.index')->with('message', 'Project updated successfully.');
    }

    public function accountManagerdocumentDownload($id)
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

    public function accountManagerFilterProject(Request $request)
    {
        if ($request->ajax()) {
            $sort_by = $request->get('sortby');
            $sort_type = $request->get('sorttype');
            $query = $request->get('query');
            $query = str_replace(" ", "%", $query);

            $projects = Project::where('assigned_to', Auth::user()->id)->orderBy($sort_by, $sort_type)->where(function ($q) use ($query) {
                $q->orWhere('sale_date', 'like', '%' . $query . '%')
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

            // ->orWhereHas('projectTypes', function ($q) use ($query) {
            //     $q->orWhere('type', 'like', '%' . $query . '%');
            // })
            // ->orWhereRaw('project_value - project_upfront like ?', ["%{$query}%"])
            // ->orderBy($sort_by, $sort_type)
            // ->paginate(15);

            return response()->json(['data' => view('account_manager.project.table', compact('projects'))->render()]);
        }
    }
    public function destroy($id)
    {
        //
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
