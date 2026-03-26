<?php

namespace App\Http\Controllers\AccountManager;

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
        return response()->json([
            'view' => view('account_manager.upsale.panel', compact('project'))->render()
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->all();

        $upsale = new Upsale();
        $upsale->project_id        = $data['project_id'];
        $upsale->user_id           = Auth::user()->id;
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

            // Update goals achievement
            $net_goals = Goal::where('user_id', Auth::user()->id)
                ->whereMonth('goals_date', date('m', strtotime($upsale->upsale_date)))
                ->whereYear('goals_date', date('Y', strtotime($upsale->upsale_date)))
                ->where('goals_type', 2)
                ->first();
            if ($net_goals) {
                $net_goals->goals_achieve = $net_goals->goals_achieve + $upsale->upsale_upfront;
                $net_goals->save();
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

                    if ($data['payment_status'][$key] == 'Paid' && !empty($data['milestone_payment_date'][$key])) {
                        $net_goals = Goal::where('user_id', Auth::user()->id)
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

        return redirect()->route('account-manager.projects.index')
            ->with('message', 'Upsale added successfully.');
    }

    public function edit($id)
    {
        $upsale = Upsale::with('milestones', 'upfrontMilestone')->findOrFail($id);
        $project = $upsale->project;
        return response()->json([
            'view' => view('account_manager.upsale.edit', compact('upsale', 'project'))->render()
        ]);
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        $upsale = Upsale::findOrFail($id);

        $upsale->upsale_project_type   = isset($data['upsale_project_type']) ? $data['upsale_project_type'] : [];
        $upsale->other_project_type    = $data['other_project_type'] ?? null;
        $upsale->upsale_value          = $data['upsale_value'];
        $upsale->upsale_upfront        = $data['upsale_upfront'] ?? 0;
        $upsale->upsale_currency       = $data['upsale_currency'];
        $upsale->upsale_payment_method = $data['upsale_payment_method'];
        $upsale->upsale_date           = $data['upsale_date'];
        $upsale->save();

        // Reverse old milestone goals
        $oldMilestones = ProjectMilestone::where('upsale_id', $upsale->id)->where('payment_status', 'Paid')->get();
        foreach ($oldMilestones as $om) {
            if ($om->payment_date) {
                $goal = Goal::where('user_id', Auth::user()->id)
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

            $net_goals = Goal::where('user_id', Auth::user()->id)
                ->whereMonth('goals_date', date('m', strtotime($upsale->upsale_date)))
                ->whereYear('goals_date', date('Y', strtotime($upsale->upsale_date)))
                ->where('goals_type', 2)
                ->first();
            if ($net_goals) {
                $net_goals->goals_achieve = $net_goals->goals_achieve + $upsale->upsale_upfront;
                $net_goals->save();
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

                    if ($data['payment_status'][$key] == 'Paid' && !empty($data['milestone_payment_date'][$key])) {
                        $net_goals = Goal::where('user_id', Auth::user()->id)
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

        return redirect()->route('account-manager.projects.index')
            ->with('message', 'Upsale updated successfully.');
    }

    public function destroy($id)
    {
        $upsale = Upsale::findOrFail($id);
        $projectId = $upsale->project_id;

        // Reverse goals from paid milestones
        $milestones = ProjectMilestone::where('upsale_id', $upsale->id)->where('payment_status', 'Paid')->get();
        foreach ($milestones as $m) {
            if ($m->payment_date) {
                $goal = Goal::where('user_id', Auth::user()->id)
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

        // Milestones deleted via cascade from FK
        $upsale->delete();

        return redirect()->route('account-manager.projects.index')
            ->with('message', 'Upsale deleted successfully.');
    }
}
