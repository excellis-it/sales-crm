<?php

namespace App\Http\Controllers\AccountManager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Project;
use App\Models\ProjectMilestone;
use Illuminate\Support\Facades\View;
use PDF;

class PaymentsController extends Controller
{
    //

    public function accountManagerPayments()
    {
        
        $project_milestones = ProjectMilestone::where('payment_status', 'Paid')
                ->whereHas('project', function ($query) {
                    // Add a condition to check if the project's user_id matches the authenticated user's ID
                    $query->where('user_id', Auth::user()->id);
                })
                ->with('project')
                ->paginate(10);
  
        return view('account_manager.payments.list', compact('project_milestones'));

    }

    public function accountManagerPaymentsFilter(Request $request)
    {
        
        if ($request->ajax()) {
            $sort_by = $request->get('sortby');
            $sort_type = $request->get('sorttype');
            $query = $request->get('query');
            $query = str_replace(" ", "%", $query);

            $project_milestones = ProjectMilestone::where('payment_status', 'Paid')
                ->whereHas('project', function ($query) {
                    // Add a condition to check if the project's user_id matches the authenticated user's ID
                    $query->where('user_id', Auth::user()->id);
                })
                ->orWhere('milestone_name', 'like', '%' . $query . '%')
                ->orWhere('milestone_value', 'like', '%' . $query . '%')
                ->orWhere('payment_mode', 'like', '%' . $query . '%')
                ->orWhere('payment_date', 'like', '%' . $query . '%')
                ->paginate(10);    
            
        }

        return response()->json(['data' => view('account_manager.payments.table', compact('project_milestones'))->render()]);
    }

    public function accountManagerInvoicedownload()
    {
        
        return view('account_manager.payments.invoice_download', compact('project_milestones'));
    }
}
