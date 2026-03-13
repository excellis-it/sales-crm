<?php

namespace App\Http\Controllers\TenderUser;

use App\Http\Controllers\Controller;
use App\Models\ProjectMilestone;
use App\Models\TenderFollowup;
use App\Models\TenderProject;
use App\Models\TenderStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TenderProjectController extends Controller
{
    public function index(Request $request)
    {
        $tender_projects = TenderProject::with(['tenderStatus', 'tenderUser'])
            ->where('tender_user_id', Auth::id());

        if ($request->search) {
            $tender_projects = $tender_projects->where(function ($query) use ($request) {
                $query->where('tender_name', 'like', '%' . $request->search . '%')
                    ->orWhere('tender_id_ref_no', 'like', '%' . $request->search . '%')
                    ->orWhere('department_org', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->status) {
            $tender_projects = $tender_projects->where('status', $request->status);
        }

        $tender_projects = $tender_projects->orderBy('id', 'desc')->paginate(15);
        $statuses = TenderStatus::where('status', 1)->get();

        if ($request->ajax()) {
            return view('tender_user.tender_project.table', compact('tender_projects', 'statuses'));
        }

        return view('tender_user.tender_project.list', compact('tender_projects', 'statuses'));
    }

    public function create()
    {
        $statuses = TenderStatus::where('status', 1)->get();
        return view('tender_user.tender_project.create', compact('statuses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tender_name' => 'required|string|max:255',
            'tender_id_ref_no' => 'required|string|max:255|unique:tender_projects,tender_id_ref_no',
            'department_org' => 'required|string|max:255',
            'category' => 'required|in:Hardware,AMC,Software',
            'category_title' => 'required_with:category|string|max:255',
            'tender_value_lakhs' => 'nullable|numeric|min:0',
            'emd' => 'nullable|string|max:255',
            'delivery_date' => 'nullable|date',
            'status' => 'required|exists:tender_statuses,id',
            'l1_quoted_value' => 'nullable|numeric|min:0',
            'excellis_it_quoted_price' => 'nullable|numeric|min:0',
            'remarks' => 'nullable|string',
            'contact_authority_name' => 'nullable|string|max:255',
            'contact_authority_phone' => 'nullable|string|max:20',
            'contact_authority_email' => 'nullable|email|max:255',
            'milestones' => 'nullable|array',
            'milestones.*.milestone_name' => 'required_with:milestones|string|max:255',
            'milestones.*.milestone_value' => 'nullable|numeric|min:0',
            'milestones.*.payment_status' => 'nullable|in:Due,Paid',
            'milestones.*.payment_date' => 'nullable|date',
            'milestones.*.milestone_comment' => 'nullable|string',
            'milestones.*.payment_mode' => 'nullable|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            $data = $request->all();
            $data['tender_user_id'] = Auth::id();
            $tender = TenderProject::create($data);

            // Save Remarks to Followups
            if ($request->remarks) {
                TenderFollowup::create([
                    'tender_project_id' => $tender->id,
                    'comment' => $request->remarks,
                    'user_id' => Auth::id(),
                    'type' => 'Remark',
                ]);
            }

            // Save Milestones
            if ($request->milestones) {
                foreach ($request->milestones as $milestone) {
                    if (!empty($milestone['milestone_name'])) {
                        ProjectMilestone::create([
                            'tender_project_id' => $tender->id,
                            'milestone_name' => $milestone['milestone_name'],
                            'milestone_value' => $milestone['milestone_value'] ?? null,
                            'payment_status' => $milestone['payment_status'] ?? 'Due',
                            'payment_date' => $milestone['payment_date'] ?? null,
                            'milestone_comment' => $milestone['milestone_comment'] ?? null,
                            'payment_mode' => $milestone['payment_mode'] ?? null,
                        ]);

                        // Save Milestone Comment to Followups
                        if (!empty($milestone['milestone_comment'])) {
                            TenderFollowup::create([
                                'tender_project_id' => $tender->id,
                                'comment' => $milestone['milestone_comment'],
                                'user_id' => Auth::id(),
                                'type' => 'Milestone Comment',
                            ]);
                        }
                    }
                }
            }

            DB::commit();
            return response()->json(['success' => 'Tender Project created successfully.']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function edit(string $id)
    {
        $tender = TenderProject::with('milestones')->where('tender_user_id', Auth::id())->findOrFail($id);
        $statuses = TenderStatus::where('status', 1)->get();
        return view('tender_user.tender_project.edit', compact('tender', 'statuses'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'tender_name' => 'required|string|max:255',
            'tender_id_ref_no' => 'required|string|max:255|unique:tender_projects,tender_id_ref_no,' . $id,
            'department_org' => 'required|string|max:255',
            'category' => 'required|in:Hardware,AMC,Software',
            'category_title' => 'required_with:category|string|max:255',
            'tender_value_lakhs' => 'nullable|numeric|min:0',
            'emd' => 'nullable|string|max:255',
            'delivery_date' => 'nullable|date',
            'status' => 'required|exists:tender_statuses,id',
            'l1_quoted_value' => 'nullable|numeric|min:0',
            'excellis_it_quoted_price' => 'nullable|numeric|min:0',
            'contact_authority_name' => 'nullable|string|max:255',
            'contact_authority_phone' => 'nullable|string|max:20',
            'contact_authority_email' => 'nullable|email|max:255',
            'milestones' => 'nullable|array',
            'milestones.*.milestone_name' => 'required_with:milestones|string|max:255',
            'milestones.*.milestone_value' => 'nullable|numeric|min:0',
            'milestones.*.payment_status' => 'nullable|in:Due,Paid',
            'milestones.*.payment_date' => 'nullable|date',
            'milestones.*.milestone_comment' => 'nullable|string',
            'milestones.*.payment_mode' => 'nullable|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            $tender = TenderProject::where('tender_user_id', Auth::id())->findOrFail($id);
            $tender->update($request->all());

            // Update Milestones
            ProjectMilestone::where('tender_project_id', $id)->delete();
            if ($request->milestones) {
                foreach ($request->milestones as $milestone) {
                    if (!empty($milestone['milestone_name'])) {
                        ProjectMilestone::create([
                            'tender_project_id' => $tender->id,
                            'milestone_name' => $milestone['milestone_name'],
                            'milestone_value' => $milestone['milestone_value'] ?? null,
                            'payment_status' => $milestone['payment_status'] ?? 'Due',
                            'payment_date' => $milestone['payment_date'] ?? null,
                            'milestone_comment' => $milestone['milestone_comment'] ?? null,
                            'payment_mode' => $milestone['payment_mode'] ?? null,
                        ]);
                    }
                }
            }

            DB::commit();
            return response()->json(['success' => 'Tender Project updated successfully.']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy(string $id)
    {
        $tender = TenderProject::where('tender_user_id', Auth::id())->findOrFail($id);
        $tender->delete();
        return response()->json(['success' => 'Tender Project deleted successfully.']);
    }

    public function getFollowups($id)
    {
        $tender = TenderProject::where('tender_user_id', Auth::id())->findOrFail($id);
        $followups = TenderFollowup::with('user')
            ->where('tender_project_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json([
            'view' => view('tender_user.tender_project.followups_modal_content', compact('followups'))->render()
        ]);
    }

    public function addFollowup(Request $request)
    {
        $request->validate([
            'tender_project_id' => 'required|exists:tender_projects,id',
            'comment' => 'required|string',
        ]);

        // Check ownership
        TenderProject::where('tender_user_id', Auth::id())->findOrFail($request->tender_project_id);

        TenderFollowup::create([
            'tender_project_id' => $request->tender_project_id,
            'comment' => $request->comment,
            'user_id' => Auth::id(),
            'type' => 'Remark',
        ]);

        return response()->json(['success' => 'Follow-up added successfully.']);
    }
}
