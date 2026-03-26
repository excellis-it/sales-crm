<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Goal;
use App\Models\Project;
use App\Models\ProjectMilestone;
use App\Models\Upsale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UpsaleController extends Controller
{
    public function upsaleForm($projectId)
    {
        $project = Project::with(['upsales.milestones', 'upsales.upfrontMilestone'])->findOrFail($projectId);
        $account_managers = \App\Models\User::role('ACCOUNT_MANAGER')->where('status', 1)->orderBy('name', 'ASC')->get();
        return response()->json([
            'view' => view('admin.upsale.panel', compact('project', 'account_managers'))->render()
        ]);
    }

    public function store(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'project_id'            => 'required|exists:projects,id',
            'upsale_project_type'   => 'required|array',
            'upsale_value'          => 'required|numeric|min:0',
            'upsale_upfront'        => 'nullable|numeric|min:0',
            'upsale_currency'       => 'required|string',
            'upsale_payment_method' => 'required|string',
            'upsale_date'           => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
        }

        $data = $request->all();

        $project = Project::findOrFail($data['project_id']);
        $goalUserId = $request->assigned_to ?: $project->assigned_to; // Use selected AM or default to project's AM

        $upsale = new Upsale();
        $upsale->project_id        = $data['project_id'];
        $upsale->user_id           = $goalUserId; // Attribution goes to AM as user_id
        $upsale->upsale_project_type = isset($data['upsale_project_type']) ? $data['upsale_project_type'] : [];
        $upsale->other_project_type  = $data['other_project_type'] ?? null;
        $upsale->upsale_value        = $data['upsale_value'];
        $upsale->upsale_upfront      = $data['upsale_upfront'] ?? 0;
        $upsale->upsale_currency     = $data['upsale_currency'];
        $upsale->upsale_payment_method = $data['upsale_payment_method'];
        $upsale->upsale_date         = $data['upsale_date'];
        $upsale->save();

        // Save upsale upfront as a project_milestone with type 'upsale_upfront'
        if ($upsale->upsale_upfront > 0) {
            $upfront = new ProjectMilestone();
            $upfront->project_id      = $upsale->project_id;
            $upfront->upsale_id       = $upsale->id;
            $upfront->milestone_name  = 'Upsale Upfront';
            $upfront->milestone_type  = 'upsale_upfront';
            $upfront->milestone_value = $upsale->upsale_upfront;
            $upfront->payment_status  = 'Paid';
            $upfront->payment_mode    = $upsale->upsale_payment_method;
            $upfront->payment_date    = $upsale->upsale_date;
            $upfront->save();

            // Update goals achievement for AM
            if ($goalUserId) {
                $net_goals = Goal::where('user_id', $goalUserId)
                    ->whereMonth('goals_date', date('m', strtotime($upsale->upsale_date)))
                    ->whereYear('goals_date', date('Y', strtotime($upsale->upsale_date)))
                    ->where('goals_type', 2)
                    ->first();
                if ($net_goals) {
                    $net_goals->goals_achieve = $net_goals->goals_achieve + $upsale->upsale_upfront;
                    $net_goals->save();
                }
            }
        }

        // Save upsale milestones
        if (isset($data['milestone_name'])) {
            foreach ($data['milestone_name'] as $key => $name) {
                if (!empty($name)) {
                    $milestone = new ProjectMilestone();
                    $milestone->project_id       = $upsale->project_id;
                    $milestone->upsale_id        = $upsale->id;
                    $milestone->milestone_name   = $name;
                    $milestone->milestone_type   = 'upsale_milestone';
                    $milestone->milestone_value  = $data['milestone_value'][$key];
                    $milestone->payment_status   = $data['payment_status'][$key];
                    $milestone->payment_mode     = $data['milestone_payment_mode'][$key] ?? null;
                    $milestone->payment_date     = $data['milestone_payment_date'][$key] ?? null;
                    $milestone->milestone_comment = $data['milestone_comment'][$key] ?? null;
                    $milestone->save();

                    if ($data['payment_status'][$key] == 'Paid' && !empty($data['milestone_payment_date'][$key]) && $goalUserId) {
                        $net_goals = Goal::where('user_id', $goalUserId)
                            ->whereMonth('goals_date', date('m', strtotime($data['milestone_payment_date'][$key])))
                            ->whereYear('goals_date', date('Y', strtotime($data['milestone_payment_date'][$key])))
                            ->where('goals_type', 2)
                            ->first();
                        if ($net_goals) {
                            $net_goals->goals_achieve = $net_goals->goals_achieve + $data['milestone_value'][$key];
                            $net_goals->save();
                        }
                    }
                }
            }
        }

        return response()->json(['status' => 'success', 'message' => 'Upsale added successfully.']);
    }

    public function edit($id)
    {
        $upsale = Upsale::with('milestones', 'upfrontMilestone')->findOrFail($id);
        $project = $upsale->project;
        $account_managers = \App\Models\User::role('ACCOUNT_MANAGER')->where('status', 1)->orderBy('name', 'ASC')->get();
        return response()->json([
            'view' => view('admin.upsale.edit', compact('upsale', 'project', 'account_managers'))->render()
        ]);
    }

    public function update(Request $request, $id)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'upsale_project_type'   => 'required|array',
            'upsale_value'          => 'required|numeric|min:0',
            'upsale_upfront'        => 'nullable|numeric|min:0',
            'upsale_currency'       => 'required|string',
            'upsale_payment_method' => 'required|string',
            'upsale_date'           => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()], 422);
        }

        $data = $request->all();
        $upsale = Upsale::findOrFail($id);
        $project = Project::findOrFail($upsale->project_id);
        $oldGoalUserId = $upsale->user_id; // Capture the old user_id for goal reversal
        $goalUserId = $request->assigned_to ?: $project->assigned_to; 

        // Reverse old milestone goals from the OLD user
        if ($oldGoalUserId) {
            $oldMilestones = ProjectMilestone::where('upsale_id', $upsale->id)->where('payment_status', 'Paid')->get();
            foreach ($oldMilestones as $om) {
                if ($om->payment_date) {
                    $goal = Goal::where('user_id', $oldGoalUserId)
                        ->whereMonth('goals_date', date('m', strtotime($om->payment_date)))
                        ->whereYear('goals_date', date('Y', strtotime($om->payment_date)))
                        ->where('goals_type', 2)
                        ->first();
                    if ($goal) {
                        $goal->goals_achieve = $goal->goals_achieve - $om->milestone_value;
                        $goal->save();
                    }
                }
            }
        }

        $upsale->user_id               = $goalUserId; // Update attribution to the new AM
        $upsale->upsale_project_type   = isset($data['upsale_project_type']) ? $data['upsale_project_type'] : [];
        $upsale->other_project_type    = $data['other_project_type'] ?? null;
        $upsale->upsale_value          = $data['upsale_value'];
        $upsale->upsale_upfront        = $data['upsale_upfront'] ?? 0;
        $upsale->upsale_currency       = $data['upsale_currency'];
        $upsale->upsale_payment_method = $data['upsale_payment_method'];
        $upsale->upsale_date           = $data['upsale_date'];
        $upsale->save();

        // Reverse old milestone goals
        if ($goalUserId) {
            $oldMilestones = ProjectMilestone::where('upsale_id', $upsale->id)->where('payment_status', 'Paid')->get();
            foreach ($oldMilestones as $om) {
                if ($om->payment_date) {
                    $goal = Goal::where('user_id', $goalUserId)
                        ->whereMonth('goals_date', date('m', strtotime($om->payment_date)))
                        ->whereYear('goals_date', date('Y', strtotime($om->payment_date)))
                        ->where('goals_type', 2)
                        ->first();
                    if ($goal) {
                        $goal->goals_achieve = $goal->goals_achieve - $om->milestone_value;
                        $goal->save();
                    }
                }
            }
        }

        // Delete all old upsale milestones & upfront, then recreate
        ProjectMilestone::where('upsale_id', $upsale->id)->delete();

        // Recreate upfront
        if ($upsale->upsale_upfront > 0) {
            $upfront = new ProjectMilestone();
            $upfront->project_id      = $upsale->project_id;
            $upfront->upsale_id       = $upsale->id;
            $upfront->milestone_name  = 'Upsale Upfront';
            $upfront->milestone_type  = 'upsale_upfront';
            $upfront->milestone_value = $upsale->upsale_upfront;
            $upfront->payment_status  = 'Paid';
            $upfront->payment_mode    = $upsale->upsale_payment_method;
            $upfront->payment_date    = $upsale->upsale_date;
            $upfront->save();

            if ($goalUserId) {
                $net_goals = Goal::where('user_id', $goalUserId)
                    ->whereMonth('goals_date', date('m', strtotime($upsale->upsale_date)))
                    ->whereYear('goals_date', date('Y', strtotime($upsale->upsale_date)))
                    ->where('goals_type', 2)
                    ->first();
                if ($net_goals) {
                    $net_goals->goals_achieve = $net_goals->goals_achieve + $upsale->upsale_upfront;
                    $net_goals->save();
                }
            }
        }

        // Recreate milestones
        if (isset($data['milestone_name'])) {
            foreach ($data['milestone_name'] as $key => $name) {
                if (!empty($name)) {
                    $milestone = new ProjectMilestone();
                    $milestone->project_id        = $upsale->project_id;
                    $milestone->upsale_id         = $upsale->id;
                    $milestone->milestone_name    = $name;
                    $milestone->milestone_type    = 'upsale_milestone';
                    $milestone->milestone_value   = $data['milestone_value'][$key];
                    $milestone->payment_status    = $data['payment_status'][$key];
                    $milestone->payment_mode      = $data['milestone_payment_mode'][$key] ?? null;
                    $milestone->payment_date      = $data['milestone_payment_date'][$key] ?? null;
                    $milestone->milestone_comment = $data['milestone_comment'][$key] ?? null;
                    $milestone->save();

                    if ($data['payment_status'][$key] == 'Paid' && !empty($data['milestone_payment_date'][$key]) && $goalUserId) {
                        $net_goals = Goal::where('user_id', $goalUserId)
                            ->whereMonth('goals_date', date('m', strtotime($data['milestone_payment_date'][$key])))
                            ->whereYear('goals_date', date('Y', strtotime($data['milestone_payment_date'][$key])))
                            ->where('goals_type', 2)
                            ->first();
                        if ($net_goals) {
                            $net_goals->goals_achieve = $net_goals->goals_achieve + $data['milestone_value'][$key];
                            $net_goals->save();
                        }
                    }
                }
            }
        }

        return response()->json(['status' => 'success', 'message' => 'Upsale updated successfully.']);
    }

    public function destroy($id)
    {
        $upsale = Upsale::findOrFail($id);
        $project = Project::findOrFail($upsale->project_id);
        $goalUserId = $upsale->user_id; // Goal achievement was attributed to this AM

        // Reverse goals from paid milestones
        if ($goalUserId) {
            $milestones = ProjectMilestone::where('upsale_id', $upsale->id)->where('payment_status', 'Paid')->get();
            foreach ($milestones as $m) {
                if ($m->payment_date) {
                    $goal = Goal::where('user_id', $goalUserId)
                        ->whereMonth('goals_date', date('m', strtotime($m->payment_date)))
                        ->whereYear('goals_date', date('Y', strtotime($m->payment_date)))
                        ->where('goals_type', 2)
                        ->first();
                    if ($goal) {
                        $goal->goals_achieve = $goal->goals_achieve - $m->milestone_value;
                        $goal->save();
                    }
                }
            }
        }

        // Milestones deleted via cascade from FK
        $upsale->delete();

        return redirect()->route('sales-projects.index')
            ->with('message', 'Upsale deleted successfully.');
    }
}
