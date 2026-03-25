<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\BdmFollowup;
use App\Models\BdmProject;
use App\Models\BdmProspect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BdmFollowupController extends Controller
{
    public function getProspectFollowups(Request $request)
    {
        $followups = BdmFollowup::with('user')
            ->where('bdm_prospect_id', $request->id)
            ->orderBy('created_at', 'desc')
            ->get();
        $project = false;
        return response()->json([
            'view' => view('bdm.followups_modal_content', compact('followups', 'project',))->render()
        ]);
    }

    public function addProspectFollowup(Request $request)
    {
        $request->validate([
            'bdm_prospect_id' => 'required|exists:bdm_prospects,id',
            'comment' => 'required|string',
            'status' => 'nullable|string',
            'last_call_status' => 'nullable|string',
            'meeting_date' => 'nullable|date',
            'next_followup_date' => 'nullable|date',
        ]);

        $prospect = BdmProspect::find($request->bdm_prospect_id);

        BdmFollowup::create([
            'bdm_prospect_id' => $request->bdm_prospect_id,
            'remark' => $request->comment,
            'status' => $request->status ?? ($prospect ? $prospect->status : null),
            'last_call_status' => $request->last_call_status,
            'meeting_date' => $request->meeting_date ?? ($prospect ? $prospect->meeting_date : null),
            'next_followup_date' => $request->next_followup_date,
            'user_id' => Auth::id(),
        ]);

        // also update prospect followup date if provided
        if ($prospect) {
            if ($request->next_followup_date) {
                $prospect->followup_date = $request->next_followup_date;
            }
            if ($request->status) {
                $prospect->status = $request->status;
            }
            if ($request->meeting_date) {
                $prospect->meeting_date = $request->meeting_date;
            }
            $prospect->save();
        }

        return response()->json(['success' => 'Follow-up added successfully.']);
    }

    public function getProjectFollowups(Request $request)
    {
        $followups = BdmFollowup::with('user')
            ->where('bdm_project_id', $request->id)
            ->orderBy('created_at', 'desc')
            ->get();
        $project = true;
        $id = $request->id;
        $type = $request->type;
        $prefix = $request->url;
        $add_url = $request->add_url;
        $id_field = $request->id_field;
        return response()->json([
            'view' => view('bdm.followups_modal_content', compact('followups', 'project', 'id', 'type', 'prefix', 'add_url', 'id_field'))->render()
        ]);
    }

    public function addProjectFollowup(Request $request)
    {
        $request->validate([
            'bdm_project_id' => 'required|exists:bdm_projects,id',
            'comment' => 'required|string',
            'next_followup_date' => 'nullable|date',
        ]);

        BdmFollowup::create([
            'bdm_project_id' => $request->bdm_project_id,
            'remark' => $request->comment,
            'next_followup_date' => $request->next_followup_date,
            'user_id' => Auth::id(),
        ]);

        return response()->json(['success' => 'Follow-up added successfully.']);
    }
}
