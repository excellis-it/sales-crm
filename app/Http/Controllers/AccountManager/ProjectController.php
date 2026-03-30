<?php

namespace App\Http\Controllers\AccountManager;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Followup;
use App\Models\Goal;
use App\Models\Project;
use App\Models\ProjectMilestone;
use App\Models\ProjectType;
use App\Traits\ImageTrait;
use App\Models\ProjectDocument;
use App\Models\User;
use Illuminate\Support\Facades\Session;
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
    public function index(Request $request)
    {
        // URL query params take priority (for dashboard redirects), then session
        $query      = $request->get('query', Session::get('am_project_filter_query', ''));
        $start_date = $request->get('start_date', Session::get('am_project_filter_start_date', ''));
        $end_date   = $request->get('end_date', Session::get('am_project_filter_end_date', ''));

        // Handle role filter from dashboard
        if ($request->role) {
            Session::put('am_project_filter_role', $request->role);
            Session::forget('am_project_filter_query');
            $query = '';
        }
        $filterRole = Session::get('am_project_filter_role');

        // Persist to session so AJAX filter calls keep the same dates
        if ($request->has('start_date') || $request->has('end_date')) {
            Session::put('am_project_filter_start_date', $start_date);
            Session::put('am_project_filter_end_date', $end_date);
            Session::put('am_project_filter_query', $query);
        }

        $query_p    = str_replace(" ", "%", $query);
        $date_query = null;
        if (preg_match('/^\d{1,2}-\d{1,2}-\d{4}$/', $query)) {
            $date_query = date('Y-m-d', strtotime($query));
        }

        $userId = Auth::user()->id;
        $projects = Project::with(['upsales', 'allProjectMilestones', 'projectMilestones', 'projectTypes', 'projectOpener', 'projectCloser'])
            ->where('assigned_to', $userId);

        // AM Revenue filter — match Helper::getUserAchievementDateRange() for ACCOUNT_MANAGER
        // Revenue comes from: sale_date (gross/upfront) + milestone payment_date (net)
        if ($filterRole == 'account_manager' && $start_date && $end_date) {
            $projects->where(function ($q) use ($start_date, $end_date, $userId) {
                // Gross: sale_date in range
                $q->whereBetween('sale_date', [$start_date, $end_date]);
                // Net milestones: paid milestones (non-upfront) with payment_date in range
                $q->orWhereHas('allProjectMilestones', function ($mq) use ($start_date, $end_date) {
                    $mq->where('payment_status', 'Paid')
                       ->where('milestone_type', '!=', 'upfront')
                       ->whereBetween('payment_date', [$start_date, $end_date]);
                });
            });
        } elseif ($start_date && $end_date) {
            $projects->whereBetween('sale_date', [$start_date, $end_date]);
        }

        if ($query) {
            $projects->where(function ($q) use ($query_p, $date_query) {
                $q->orWhere('sale_date', 'like', '%' . $query_p . '%')
                  ->orWhere('business_name', 'like', '%' . $query_p . '%')
                  ->orWhere('client_name', 'like', '%' . $query_p . '%')
                  ->orWhere('client_phone', 'like', '%' . $query_p . '%')
                  ->orWhere('project_value', 'like', '%' . $query_p . '%')
                  ->orWhere('project_upfront', 'like', '%' . $query_p . '%');
                if ($date_query) $q->orWhere('sale_date', 'like', '%' . $date_query . '%');
            });
        }

        $projects = $projects->orderBy('id', 'desc')->paginate(15);
        $users    = User::role(['SALES_MANAGER', 'ACCOUNT_MANAGER', 'SALES_EXCUETIVE'])->orderBy('id', 'desc')->get();

        return view('account_manager.project.list', compact('projects', 'users', 'query', 'start_date', 'end_date', 'filterRole'));
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
        $project->delivery_tat = $data['delivery_tat'] ?? '';
        $project->comment = $data['comment'];
        $project->save();

        if ($data['project_upfront'] > 0) {
            $upfront = ProjectMilestone::where('project_id', $project->id)->where('milestone_type', 'upfront')->first();
            if ($upfront) {
                $upfront->milestone_value = $data['project_upfront'];
                $upfront->save();
            } else {
                $upfront = new ProjectMilestone();
                $upfront->project_id = $project->id;
                $upfront->milestone_name = 'Upfront';
                $upfront->milestone_type = 'upfront';
                $upfront->milestone_value = $data['project_upfront'];
                $upfront->payment_status = 'Paid';
                $upfront->payment_mode = $data['payment_mode'] ?? null;
                $upfront->payment_date = date('Y-m-d');
                $upfront->save();
            }
        }

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
                    // $project_milestone->payment_date = ($data['payment_status'][$key] == 'Paid') ? date('Y-m-d') : '';
                    $project_milestone->milestone_comment = $data['milestone_comment'][$key];
                    $project_milestone->payment_mode = $data['milestone_payment_mode'][$key];
                    $project_milestone->payment_date = $data['milestone_payment_date'][$key];
                    $project_milestone->save();

                    if ($data['payment_status'][$key] == 'Paid') {
                        $net_goals_t = Goal::where('user_id', Auth::user()->id)->whereMonth('goals_date', date('m', strtotime($data['milestone_payment_date'][$key])))->whereYear('goals_date', date('Y', strtotime($data['milestone_payment_date'][$key])))->where('goals_type', 2)->first();
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
        $project->delivery_tat = $data['delivery_tat'] ?? '';
        // $project->comment = $data['comment'];
        $project->save();



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

        if (isset($data['project_type'])) {
            ProjectType::where('project_id', $id)->delete();
            foreach ($data['project_type'] as $key => $project_type) {
                $update_project_type = new ProjectType();
                $update_project_type->project_id = $project->id;
                $update_project_type->type = $project_type;
                if ($project_type == 'Other') {
                    $update_project_type->name = $data['other_value'];
                } else {
                    $update_project_type->name = $project_type;
                }
                $update_project_type->save();
            }
        }

        $previous_milestone_value = ProjectMilestone::where(['project_id' => $id, 'payment_status' => 'Paid'])->get();

        // $net_goals_t = Goal::where('user_id', Auth::user()->id)->whereMonth('goals_date', date('m', strtotime($data['sale_date'])))->whereYear('goals_date', date('Y', strtotime($data['sale_date'])))->where('goals_type', 2)->first();
        foreach ($previous_milestone_value as $key => $value) {
            $net_goals_t = Goal::where('user_id', Auth::user()->id)->whereMonth('goals_date', date('m', strtotime($value->payment_date)))->whereYear('goals_date', date('Y', strtotime($value->payment_date)))->where('goals_type', 2)->first();
            if ($net_goals_t) {
                $net_goals_t->goals_achieve = $net_goals_t->goals_achieve - $value->milestone_value;
                $net_goals_t->save();
            }
        }

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
                    $project_milestone->payment_mode = $data['milestone_payment_mode'][$key] ?? null;
                    $project_milestone->payment_date = $data['milestone_payment_date'][$key] ?? null;
                    $project_milestone->save();

                    if ($data['payment_status'][$key] == 'Paid' && $project_milestone->payment_date != null) {
                        $net_goals_t = Goal::where('user_id', Auth::user()->id)->whereMonth('goals_date', date('m', strtotime($data['milestone_payment_date'][$key])))->whereYear('goals_date', date('Y', strtotime($data['milestone_payment_date'][$key])))->where('goals_type', 2)->first();
                        if ($net_goals_t) {
                            $net_goals_t->goals_achieve = $net_goals_t->goals_achieve + $data['milestone_value'][$key];
                            $net_goals_t->save();
                        }
                    }
                }
            }
        }

        if ($data['project_upfront'] > 0) {
            $upfront = ProjectMilestone::where('project_id', $project->id)->where('milestone_type', 'upfront')->first();
            if ($upfront) {
                $upfront->milestone_value = $data['project_upfront'];
                $upfront->save();
            } else {
                $upfront = new ProjectMilestone();
                $upfront->project_id = $project->id;
                $upfront->milestone_name = 'Upfront';
                $upfront->milestone_type = 'upfront';
                $upfront->milestone_value = $data['project_upfront'];
                $upfront->payment_status = 'Paid';
                $upfront->payment_mode = $data['payment_mode'] ?? null;
                $upfront->payment_date = date('Y-m-d');
                $upfront->save();
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

        Session::put('page_number', $request->page_no);
        $update_success = Session::put('update_success', true);

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
            $sort_by = $request->get('sortby', 'id');
            $sort_type = $request->get('sorttype', 'desc');
            $query = $request->get('query', '');
            
            // Ensure sort_type is valid
            if (!in_array(strtolower($sort_type), ['asc', 'desc'])) {
                $sort_type = 'desc';
            }
            
            // Try to transform query if it looks like d-m-Y date
            $date_query = null;
            if (preg_match('/^\d{1,2}-\d{1,2}-\d{4}$/', $query)) {
                $date_query = date('Y-m-d', strtotime($query));
            }

            $query_p = str_replace(" ", "%", $query);

            $start_date = $request->get('start_date');
            $end_date = $request->get('end_date');

            // Handle reset
            if ($request->get('reset')) {
                Session::forget('am_project_filter_start_date');
                Session::forget('am_project_filter_end_date');
                Session::forget('am_project_filter_query');
                Session::forget('am_project_filter_role');
            }

            $projects = Project::with(['upsales', 'allProjectMilestones', 'projectMilestones', 'projectTypes', 'projectOpener', 'projectCloser'])
                ->withSum('upsales as total_upsale_value', 'upsale_value')
                ->withSum('upsales as total_upsale_upfront', 'upsale_upfront')
                ->where('assigned_to', Auth::user()->id);

            // AM Revenue filter — match Helper logic (sale_date + milestone payment_date)
            $filterRole = Session::get('am_project_filter_role');
            if ($filterRole == 'account_manager' && $start_date && $end_date) {
                $projects->where(function ($q) use ($start_date, $end_date) {
                    $q->whereBetween('sale_date', [$start_date, $end_date]);
                    $q->orWhereHas('allProjectMilestones', function ($mq) use ($start_date, $end_date) {
                        $mq->where('payment_status', 'Paid')
                           ->where('milestone_type', '!=', 'upfront')
                           ->whereBetween('payment_date', [$start_date, $end_date]);
                    });
                });
            } elseif ($start_date && $end_date) {
                $projects->whereBetween('sale_date', [$start_date, $end_date]);
            }

            $projects->where(function ($q) use ($query_p, $date_query) {
                $q->orWhere('sale_date', 'like', '%' . $query_p . '%')
                    ->orWhere('business_name', 'like', '%' . $query_p . '%')
                    ->orWhere('client_name', 'like', '%' . $query_p . '%')
                    ->orWhere('client_phone', 'like', '%' . $query_p . '%')
                    ->orWhere('project_value', 'like', '%' . $query_p . '%')
                    ->orWhere('project_upfront', 'like', '%' . $query_p . '%')
                    ->orWhere('currency', 'like', '%' . $query_p . '%')
                    ->orWhere('payment_mode', 'like', '%' . $query_p . '%')
                    ->orWhereHas('projectTypes', function ($sq) use ($query_p) {
                        $sq->where('type', 'like', '%' . $query_p . '%');
                    })
                    ->orWhereHas('projectOpener', function ($sq) use ($query_p) {
                        $sq->where('name', 'like', '%' . $query_p . '%');
                    });
                
                if ($date_query) {
                    $q->orWhere('sale_date', 'like', '%' . $date_query . '%');
                }

                // Searching by Total Grand Total (Base + Upsale)
                $q->orWhereRaw("(project_value + (SELECT COALESCE(SUM(upsale_value), 0) FROM upsales WHERE upsales.project_id = projects.id)) LIKE ?", ["%{$query_p}%"]);
                
                // Searching by Total Upfront (Base + Upsale Upfront)
                $q->orWhereRaw("(project_upfront + (SELECT COALESCE(SUM(upsale_upfront), 0) FROM upsales WHERE upsales.project_id = projects.id)) LIKE ?", ["%{$query_p}%"]);

                // Searching by Milestone Received (Paid milestones excluding upfronts)
                $paidMilestoneSubquery = "(SELECT COALESCE(SUM(milestone_value), 0) FROM project_milestones 
                                            WHERE project_milestones.project_id = projects.id 
                                            AND payment_status = 'Paid' 
                                            AND milestone_type NOT IN ('upfront', 'upsale_upfront') 
                                            AND milestone_name NOT IN ('Upfront', 'Upsale Upfront'))";
                
                $q->orWhereRaw("{$paidMilestoneSubquery} LIKE ?", ["%{$query_p}%"]);

                // Searching by Balance Due
                $grandTotalSubquery = "(project_value + (SELECT COALESCE(SUM(upsale_value), 0) FROM upsales WHERE upsales.project_id = projects.id))";
                $totalUpfrontSubquery = "(project_upfront + (SELECT COALESCE(SUM(upsale_upfront), 0) FROM upsales WHERE upsales.project_id = projects.id))";
                
                $q->orWhereRaw("({$grandTotalSubquery} - {$totalUpfrontSubquery} - {$paidMilestoneSubquery}) LIKE ?", ["%{$query_p}%"]);
            });

            // Handle sorting by calculated totals
            if ($sort_by == 'project_value') {
                $projects = $projects->orderByRaw('project_value + COALESCE(total_upsale_value, 0) ' . $sort_type);
            } elseif ($sort_by == 'project_upfront') {
                $projects = $projects->orderByRaw('project_upfront + COALESCE(total_upsale_upfront, 0) ' . $sort_type);
            } else {
                $projects = $projects->orderBy($sort_by, $sort_type);
            }

            $projects = $projects->paginate(15);

            Session::put('am_project_filter_query', $query);
            Session::put('am_project_filter_start_date', $start_date);
            Session::put('am_project_filter_end_date', $end_date);

            Session::put('call_status', $request->get('call_status'));
            if ($request->get('call_status') == '') {
                $page = Session::put('page_number', 1);
            }
            if (Session::get('call_status') == 'Yes') {
                Session::put('call_status', "");
                Session::put('update_success', false);
            }

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
