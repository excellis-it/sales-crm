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
    public function getProspectFollowups($id)
    {
        $followups = BdmFollowup::with('user')
            ->where('bdm_prospect_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json([
            'view' => view('bdm.followups_modal_content', compact('followups'))->render()
        ]);
    }

    public function addProspectFollowup(Request $request)
    {
        $request->validate([
            'bdm_prospect_id' => 'required|exists:bdm_prospects,id',
            'comment' => 'required|string',
            'next_followup_date' => 'nullable|date',
        ]);

        BdmFollowup::create([
            'bdm_prospect_id' => $request->bdm_prospect_id,
            'remark' => $request->comment,
            'next_followup_date' => $request->next_followup_date,
            'user_id' => Auth::id(),
        ]);

        // also update prospect followup date if provided
        if ($request->next_followup_date) {
            $prospect = BdmProspect::find($request->bdm_prospect_id);
            if ($prospect) {
                $prospect->followup_date = $request->next_followup_date;
                $prospect->save();
            }
        }

        return response()->json(['success' => 'Follow-up added successfully.']);
    }

    public function getProjectFollowups($id)
    {
        $followups = BdmFollowup::with('user')
            ->where('bdm_project_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json([
            'view' => view('bdm.followups_modal_content', compact('followups'))->render()
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
