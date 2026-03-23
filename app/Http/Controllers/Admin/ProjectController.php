<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Followup;
use App\Models\Goal;
use App\Models\Project;
use App\Models\ProjectMilestone;
use App\Models\ProjectType;
use App\Models\ProjectDocument;
use App\Models\User;
use Illuminate\Support\Facades\Session;
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
        $sales_managers = User::Role(['SALES_MANAGER', 'ACCOUNT_MANAGER'])->orderBy('name', 'DESC')->where('status', 1)->get();
        $users = User::role(['SALES_MANAGER', 'ACCOUNT_MANAGER', 'SALES_EXCUETIVE'])->where('status', 1)->orderBy('id', 'desc')->get();
        $account_managers = User::role('ACCOUNT_MANAGER')->orderBy('name', 'DESC')->where('status', 1)->get();
        $project_openers = User::role(['ACCOUNT_MANAGER', 'SALES_EXCUETIVE'])->where('status', 1)->orderBy('id', 'desc')->get();

        $query = Project::query();

        if ($request->sales_manager_id) {
            $query->where('user_id', $request->sales_manager_id);
        }

        if ($request->account_manager_id) {
            $query->where('assigned_to', $request->account_manager_id);
        }

        $startDate = $request->start_date;
        $endDate = $request->end_date;

        if ($request->duration) {
            [$startDate, $endDate] = \App\Helpers\Helper::getDateRangeByDuration($request->duration);
            if ($startDate && $endDate) {
                $query->whereBetween('sale_date', [$startDate, $endDate]);
            }
        } elseif ($startDate && $endDate) {
            $query->whereBetween('sale_date', [$startDate, $endDate]);
        }


        $projects = $query->orderBy('sale_date', 'desc')->paginate(15);

        return view('admin.project.list')->with(compact('projects', 'sales_managers', 'users', 'account_managers', 'project_openers', 'startDate', 'endDate'));

    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::role(['SALES_MANAGER', 'ACCOUNT_MANAGER', 'SALES_EXCUETIVE'])->where('status', 1)->orderBy('id', 'desc')->get();
        $sales_managers = User::Role(['SALES_MANAGER','ACCOUNT_MANAGER'])->orderBy('name', 'DESC')->where('status', 1)->get();
        $project_openers = User::role(['ACCOUNT_MANAGER', 'SALES_EXCUETIVE'])->where('status', 1)->orderBy('id', 'desc')->get();
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
        $project->project_closer = $data['project_closer'] ?? null;
        $project->project_upfront = $data['project_upfront'];
        $project->website = $data['website'] ?? null;
        $project->sale_date = $data['sale_date'] ?? '';
        $project->assigned_date = $project->assigned_to ? date('Y-m-d') : null;
        $project->delivery_tat = $data['delivery_tat'] ?? null;
        $project->comment = $data['comment'] ?? '';
        $project->save();

         if ($data['comment'] && $data['comment'] != null) {
            $follow_up = new Followup();
            $follow_up->user_id = Auth::user()->id;
            $follow_up->project_id = $project->id;
            $follow_up->followup_type = 'other';
            $follow_up->followup_description = $data['comment'];
            $follow_up->save();
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
            $users = User::role(['SALES_MANAGER', 'ACCOUNT_MANAGER', 'SALES_EXCUETIVE'])->where('status', 1)->orderBy('id', 'desc')->get();
            $sales_managers = User::Role(['SALES_MANAGER','ACCOUNT_MANAGER'])->get();
            $account_managers = User::role('ACCOUNT_MANAGER')->orderBy('name', 'DESC')->where('status', 1)->get();
            $project_openers = User::role(['ACCOUNT_MANAGER', 'SALES_EXCUETIVE'])->where('status', 1)->orderBy('id', 'desc')->get();
            $project = Project::find($id);
            $followups = Followup::where('project_id', $id)->get();
            // return $project->projectTypes()->pluck('type');
            $type = true;
            return response()->json(['view' => view('admin.project.edit', compact('followups','project', 'sales_managers', 'users', 'type','account_managers','project_openers'))->render()]);
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
        $project->project_closer = $data['project_closer'] ?? null;
        $project->project_upfront = $data['project_upfront'];
        $project->website = $data['website'] ?? null;
        $project->sale_date = $data['sale_date'] ?? '';

        if ($project->assigned_to != ($data['assigned_to'] ?? null)) {
            $project->assigned_date = date('Y-m-d');
        }

        $project->assigned_to = $data['assigned_to'] ?? null;
        $project->delivery_tat = $data['delivery_tat'] ?? null;
        $project->comment = $data['comment'] ?? '';
        $project->save();

        ProjectType::where('project_id', $id)->delete();


        if (isset($data['project_type'])) {
            foreach ($data['project_type'] as $key => $project_type) {
                $update_project_type = new ProjectType();
                $update_project_type->project_id = $project->id;
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

        Session::put('page_number',$request->page_no);
        $update_success = Session::put('update_success',true);
        // $url = '/admin/sales-project/?page=' . $page_no;
        // return redirect()->to($url);

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
            $sort_by = $request->get('sortby') ?? 'sale_date';
            $sort_type = $request->get('sorttype') ?? 'desc';
            $query_str = $request->get('query');
            $start_date = $request->get('start_date');
            $end_date = $request->get('end_date');

            $projects = Project::query();

            if ($query_str) {
                $query_str = str_replace(" ", "%", $query_str);
                $projects->where(function($q) use ($query_str) {
                    $q->where('id', 'like', '%' . $query_str . '%')
                        ->orWhere('sale_date', 'like', '%' . $query_str . '%')
                        ->orWhere('client_name', 'like', '%' . $query_str . '%')
                        ->orWhere('business_name', 'like', '%' . $query_str . '%')
                        ->orWhere('client_phone', 'like', '%' . $query_str . '%')
                        ->orWhere('project_value', 'like', '%' . $query_str . '%')
                        ->orWhere('project_upfront', 'like', '%' . $query_str . '%')
                        ->orWhere('currency', 'like', '%' . $query_str . '%')
                        ->orWhere('payment_mode', 'like', '%' . $query_str . '%')
                        ->orWhereHas('salesManager', function ($q2) use ($query_str) {
                            $q2->where('name', 'like', '%' . $query_str . '%');
                        });
                });
            }

            if ($start_date && $end_date) {
                $projects->whereBetween('sale_date', [$start_date, $end_date]);
            }

            $projects = $projects->orderBy($sort_by, $sort_type)->paginate(15);

            Session::put('call_status', $request->get('call_status'));
            if ($request->get('call_status') == '') {
                Session::put('page_number', 1);
            }
            if (Session::get('call_status') == 'Yes') {
                Session::put('call_status', "");
                Session::put('update_success', false);
            }

            return response()->json(['data' => view('admin.project.table', compact('projects'))->render()]);
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
