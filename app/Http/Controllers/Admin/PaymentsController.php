<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Project;
use App\Models\ProjectMilestone;
use PDF;

class PaymentsController extends Controller
{

    public function adminPayments()
    {
        $project_milestones = ProjectMilestone::where('payment_status', 'Paid')
                ->whereHas('project', function ($query) {
                    // Add a condition to check if the project's user_id matches the authenticated user's ID
                    $query->orderBy('id', 'DESC');
                })
                ->with('project')
                ->paginate(10);
        return view('admin.payments.list',compact('project_milestones'));
    }

    public function adminInvoicedownload($id)
    {
        $milestone_detail = ProjectMilestone::where('id', $id)->with('project','project.salesManager')->first();
        $pdf = PDF::loadView('admin.invoicePdf',array('milestone_detail' => $milestone_detail));
    
        return $pdf->download('admin-invoice.pdf');
    }

    public function adminPaymentFilter(Request $request)
    {
        if ($request->ajax()) {
            $query = $request->get('query');
            $query = str_replace(" ", "%", $query);
            $project_milestones = ProjectMilestone::query();
            
            $project_milestones = $project_milestones->where('payment_status', 'Paid')->where(function ($q) use ($query) {
                $q->orWhere('milestone_name', 'like', '%' . $query . '%')
                    ->orWhere('milestone_value', 'like', '%' . $query . '%')
                    ->orWhere('payment_mode', 'like', '%' . $query . '%')
                    ->orWhere('payment_status', 'like', '%' . $query . '%')
                    ->orWhere('payment_date', 'like', '%' . $query . '%')
                    ->orWhereHas('project', function ($q) use ($query) {
                        $q->where('business_name', 'like', '%' . $query . '%');
                    });
            })
            ->paginate(10);
            
            return response()->json(['data' => view('admin.payments.table', compact('project_milestones'))->render()]);
        }
    }
}
