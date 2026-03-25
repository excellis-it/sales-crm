<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Followup;
use App\Models\Project;
use App\Models\Prospect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommonFollowupController extends Controller
{
    public function getProspectFollowups(Request $request)
    {
        $followups = Followup::with('user')
            ->where('prospect_id', $request->id)
            ->orderBy('created_at', 'desc')
            ->get();
        $project = false;
        return response()->json([
            'view' => view('common.followups_modal_content', compact('followups', 'project'))->render()
        ]);
    }

    public function addProspectFollowup(Request $request)
    {
        $request->validate([
            'prospect_id' => 'required|exists:prospects,id',
            'comment' => 'required|string',
            'next_followup_date' => 'nullable|date',
        ]);

        Followup::create([
            'prospect_id' => $request->prospect_id,
            'followup_description' => $request->comment,
            'next_followup_date' => $request->next_followup_date,
            'user_id' => Auth::id(),
            'followup_date' => now(),
            'followup_type' => 'other'
        ]);

        // Update prospect followup date
        if ($request->next_followup_date) {
            $prospect = Prospect::find($request->prospect_id);
            if ($prospect) {
                $prospect->followup_date = $request->next_followup_date;
                $prospect->save();
            }
        }

        return response()->json(['success' => 'Follow-up added successfully.']);
    }

    public function getProjectFollowups(Request $request)
    {
        $followups = Followup::with('user')
            ->where('project_id', $request->id)
            ->orderBy('created_at', 'desc')
            ->get();
        $project = true;
        $id = $request->id;
        $type = $request->type;
        $prefix = $request->url;
        $add_url = $request->add_url;
        $id_field = $request->id_field;
        return response()->json([
            'view' => view('common.followups_modal_content', compact('followups', 'project', 'id', 'type', 'prefix', 'add_url', 'id_field'  ))->render()
        ]);
    }

    public function addProjectFollowup(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'comment' => 'required|string',
            'next_followup_date' => 'nullable|date',
        ]);

        Followup::create([
            'project_id' => $request->project_id,
            'followup_description' => $request->comment,
            'next_followup_date' => $request->next_followup_date,
            'user_id' => Auth::id(),
            'followup_date' => now(),
            'followup_type' => 'other'
        ]);

        return response()->json(['success' => 'Follow-up added successfully.']);
    }
}
