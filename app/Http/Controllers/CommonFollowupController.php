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
    public function getProspectFollowups($id)
    {
        $followups = Followup::with('user')
            ->where('prospect_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json([
            'view' => view('common.followups_modal_content', compact('followups'))->render()
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

    public function getProjectFollowups($id)
    {
        $followups = Followup::with('user')
            ->where('project_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json([
            'view' => view('common.followups_modal_content', compact('followups'))->render()
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
