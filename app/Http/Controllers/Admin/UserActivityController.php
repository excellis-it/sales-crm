<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BdmFollowup;
use App\Models\Followup;
use App\Models\User;
use Illuminate\Http\Request;

class UserActivityController extends Controller
{
    public function index()
    {
        $users = User::whereHas('roles')->orderBy('name')->get();

        return view('admin.user-activity.index', compact('users'));
    }

    public function filter(Request $request)
    {
        $userId = $request->user_id;
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $source = $request->source; // 'all', 'telesales', 'bdm'

        $followups = collect();
        $bdmFollowups = collect();

        // Telesales followups (from followups table)
        if ($source !== 'bdm') {
            $query = Followup::with(['user', 'project', 'prospect'])
                ->orderBy('created_at', 'desc');

            if ($userId) {
                $query->where('user_id', $userId);
            }
            if ($startDate) {
                $query->whereDate('created_at', '>=', $startDate);
            }
            if ($endDate) {
                $query->whereDate('created_at', '<=', $endDate);
            }

            $followups = $query->get()->map(function ($f) {
                return [
                    'id' => $f->id,
                    'source' => 'Tele Sales',
                    'user_name' => $f->user->name ?? 'N/A',
                    'type' => $f->project_id ? 'Project' : 'Prospect',
                    'reference_name' => $f->project_id
                        ? ($f->project->business_name ?? 'N/A')
                        : ($f->prospect->business_name ?? $f->prospect->client_name ?? 'N/A'),
                    'description' => $f->followup_description,
                    'followup_type' => $f->followup_type,
                    'status' => $f->status,
                    'last_call_status' => $f->last_call_status,
                    'next_followup_date' => $f->next_followup_date,
                    'created_at' => $f->created_at,
                ];
            });
        }

        // BDM followups (from bdm_followups table)
        if ($source !== 'telesales') {
            $bdmQuery = BdmFollowup::with(['user', 'bdmProject', 'bdmProspect'])
                ->orderBy('created_at', 'desc');

            if ($userId) {
                $bdmQuery->where('user_id', $userId);
            }
            if ($startDate) {
                $bdmQuery->whereDate('created_at', '>=', $startDate);
            }
            if ($endDate) {
                $bdmQuery->whereDate('created_at', '<=', $endDate);
            }

            $bdmFollowups = $bdmQuery->get()->map(function ($f) {
                return [
                    'id' => $f->id,
                    'source' => 'BDM',
                    'user_name' => $f->user->name ?? 'N/A',
                    'type' => $f->bdm_project_id ? 'Project' : 'Prospect',
                    'reference_name' => $f->bdm_project_id
                        ? ($f->bdmProject->business_name ?? 'N/A')
                        : ($f->bdmProspect->business_name ?? $f->bdmProspect->client_name ?? 'N/A'),
                    'description' => $f->remark,
                    'followup_type' => null,
                    'status' => $f->status,
                    'last_call_status' => $f->last_call_status,
                    'next_followup_date' => $f->next_followup_date,
                    'created_at' => $f->created_at,
                ];
            });
        }

        // Merge and sort by created_at desc
        $activities = $followups->merge($bdmFollowups)
            ->sortByDesc('created_at')
            ->values();

        $totalCount = $activities->count();

        return response()->json([
            'view' => view('admin.user-activity.table', compact('activities'))->render(),
            'total' => $totalCount,
        ]);
    }
}
