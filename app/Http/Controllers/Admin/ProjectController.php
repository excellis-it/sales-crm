<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Goal;
use App\Models\Project;
use App\Models\ProjectMilestone;
use App\Models\ProjectType;
use App\Models\ProjectDocument;
use App\Models\User;
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
    public function index(Request $request)
    {
        // if ($request->sales_manager_id) {
        //     $projects = Project::orderBy('id', 'desc')->where('user_id', $request->sales_manager_id)->get();
        //     return view('admin.project.list')->with(compact('projects'));
        // }

        // if ($request->account_manager_id) {
        //     $projects = Project::orderBy('id', 'desc')->where('assigned_to', $request->account_manager_id)->get();
        //     return view('admin.project.list')->with(compact('projects'));
        // }


        // $projects = Project::orderBy('id', 'desc')->get();
        return view('admin.project.list');
    }

    public function ajaxList(Request $request)
    {
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // Rows display per page

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value

        $totalRecords = Project::count();
        $totalRecordswithFilter = Project::where('client_name', 'like', '%' . $searchValue . '%')->count();
        if ($request->sales_manager_id) {
            $records = Project::orderBy($columnName, $columnSortOrder)->where('user_id', $request->sales_manager_id)->where('client_name', 'like', '%' . $searchValue . '%')->skip($start)->take($rowperpage)->get();
        } elseif ($request->account_manager_id) {
            $records = Project::orderBy($columnName, $columnSortOrder)->where('assigned_to', $request->account_manager_id)->where('client_name', 'like', '%' . $searchValue . '%')->skip($start)->take($rowperpage)->get();
        } else {
            $records = Project::orderBy($columnName, $columnSortOrder)->where('client_name', 'like', '%' . $searchValue . '%')->skip($start)->take($rowperpage)->get();
        }
        $data_arr = array();
        foreach ($records as $key => $record) {
            $data_arr[] = array(
                'created_at' => date('d-m-Y', strtotime($record->created_at)),
                'sale_by' => $record->salesManager->name,
                'sales_manager_email' => $record->salesManager->email,
                'client_name' => $record->client_name,
                'client_phone' => $record->client_phone,
                'project_value' => $record->project_value,
                'project_upfront' => $record->project_upfront,
                'currency' => $record->currency,
                'payment_mode' => $record->payment_mode,
                'due_amount' => $record->project_value - $record->project_upfront,
                'assigned_to' => $record->assigned_to ? '<span class="badge bg-success">Assigned</span>' : '<span class="badge bg-danger">Not Assigned</span>',
                'action' => '<a href="' . route('sales-projects.show', $record->id) . '" class="btn btn-sm btn-primary"><i class="fa fa-eye"></i></a> <a href="' . route('sales-projects.edit', $record->id) . '" class="btn btn-sm btn-warning"><i class="fa fa-edit"></i></a> <a href="javascipt:void(0);" data-route="' . route('sales-projects.delete', $record->id) . '" class="btn btn-sm btn-danger" id="delete"><i class="fa fa-trash"></i></a>'
            );
        }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr
         );

         return response()->json($response);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::role(['SALES_MANAGER', 'ACCOUNT_MANAGER', 'SALES_EXCUETIVE'])->orderBy('id', 'desc')->get();
        $sales_managers = User::Role('SALES_MANAGER')->orderBy('name', 'DESC')->where('status', 1)->get();
        return view('admin.project.create')->with(compact('sales_managers', 'users'));
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

        $project = new Project();
        $project->user_id = $data['user_id'];
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
                    $project_milestone->payment_date = $data['payment_date'][$key] ?? '';
                    $project_milestone->milestone_comment = $data['milestone_comment'][$key];
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

        // goals count
        $countGross = Goal::where(['user_id' => $data['user_id'], 'goals_type' => 1])->whereMonth('goals_date', date('m'))->whereYear('goals_date', date('Y'))->count();


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
            $users = User::role(['SALES_MANAGER', 'ACCOUNT_MANAGER', 'SALES_EXCUETIVE'])->orderBy('id', 'desc')->get();
            $sales_managers = User::Role('SALES_MANAGER')->get();
            $project = Project::find($id);
            return view('admin.project.edit')->with(compact('project', 'sales_managers', 'users'));
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
        $project->user_id = $data['user_id'];
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
                    $project_milestone->payment_date = $data['payment_date'][$key] ?? '';
                    $project_milestone->milestone_comment = $data['milestone_comment'][$key];
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

            $countGoal = Goal::where('user_id', $data['assigned_to'])->whereMonth('goals_date', date('m'))->whereYear('goals_date', date('Y'))->count();
            if ($countGoal > 0) {
                $milestone = ProjectMilestone::where(['project_id' => $data['project_id'], 'payment_status' => 'Paid'])->whereMonth('payment_date', date('m'))->whereYear('payment_date', date('Y'))->sum('milestone_value');
                $goal = Goal::where('user_id', $data['assigned_to'])->whereMonth('goals_date', date('m'))->whereYear('goals_date', date('Y'))->first();
                $goal->goals_achieve = $goal->goals_achieve + $milestone;
                $goal->save();
            }
            return response()->json(['status' => 'success', 'message' => 'Project assigned successfully.']);
        }
    }
}
