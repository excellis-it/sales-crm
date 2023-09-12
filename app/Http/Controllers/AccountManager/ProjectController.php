<?php

namespace App\Http\Controllers\AccountManager;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectMilestone;
use App\Models\ProjectType;
use App\Traits\ImageTrait;
use App\Models\ProjectDocument;
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
        $projects = Project::where('assigned_to', Auth::user()->id)->orderBy('id', 'desc')->get();
        return view('account_manager.project.list', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('account_manager.project.create');
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

        $project = new Project();
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
        $project->project_opener = $data['project_opener'];
        $project->project_closer = $data['project_closer'];
        $project->project_upfront = $data['project_upfront'];
        $project->website = $data['website'];
        $project->sale_date = $data['sale_date'];
        $project->assigned_date = date('Y-m-d');
        $project->assigned_to = Auth::user()->id;
        $project->save();

        foreach ($data['project_type'] as $key => $value) {
            $project_type = new ProjectType();
            $project_type->project_id = $project->id;
            $project_type->type = $value;
            if ($value == 'Other') {
                $project_type->name = $data['other_value'];
            } else {
                $project_type->name = $value;
            }
            $project_type->type = $value;
            $project_type->save();
        }

        foreach ($data['milestone_value'] as $key => $milestone) {
            $project_milestone = new ProjectMilestone();
            $project_milestone->project_id = $project->id;
            $project_milestone->milestone_name = $data['milestone_name'][$key];
            $project_milestone->milestone_value = $milestone;
            $project_milestone->save();
        }

        foreach ($data['pdf'] as $key => $pdfFile) {
            if ($pdfFile) {
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
        return view('account_manager.project.view', compact('project','documents'));
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
            $project = Project::find($id);
            return view('account_manager.project.edit')->with(compact('project'));
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
        $project->project_opener = $data['project_opener'];
        $project->project_closer = $data['project_closer'];
        $project->project_upfront = $data['project_upfront'];
        $project->website = $data['website'];
        $project->sale_date = $data['sale_date'];
        $project->assigned_date = date('Y-m-d');
        $project->assigned_to = Auth::user()->id;
        $project->save();

        ProjectType::where('project_id', $id)->delete();
        foreach ($data['project_type'] as $key => $value) {
            $project_type = new ProjectType();
            $project_type->project_id = $project->id;
            $project_type->type = $value;
            if ($value == 'Other') {
                $project_type->name = $data['other_value'];
            } else {
                $project_type->name = $value;
            }
            $project_type->type = $value;
            $project_type->save();
        }

        ProjectMilestone::where('project_id', $id)->delete();
        foreach ($data['milestone_value'] as $key => $milestone) {
            $project_milestone = new ProjectMilestone();
            $project_milestone->project_id = $project->id;
            $project_milestone->milestone_name = $data['milestone_name'][$key];
            $project_milestone->milestone_value = $milestone;
            $project_milestone->save();
        }
        
        foreach ($data['pdf'] as $key => $pdfFile) {
            
            if ($pdfFile) {
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
    public function destroy($id)
    {
        //
    }
}
