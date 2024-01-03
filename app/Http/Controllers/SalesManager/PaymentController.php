<?php

namespace App\Http\Controllers\SalesManager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Project;
use App\Models\User;
use App\Models\ProjectMilestone;
use PDF;


class PaymentController extends Controller
{
    //

    public function salesManagerPayments()
    {
        
        $project_milestones = ProjectMilestone::where('payment_status', 'Paid')
        ->whereHas('project', function ($query) {
            $query->where('user_id', auth()->id())  
                ->orderBy('id', 'DESC');
        })
        ->with('project')
        ->paginate(10);
            
        return view('sales_manager.payments.list',compact('project_milestones'));
    }

    public function salesManagerInvoicedownload($id)
    {
        $milestone_detail = ProjectMilestone::where('id', $id)->with('project','user')->first();
        $pdf = PDF::loadView('sales_manager.invoicePdf',array('milestone_detail' => $milestone_detail));
    
        return $pdf->download('sales-manager-invoice.pdf');
    }

    public function salesManagerPaymentFilter(Request $request)
    {
        if ($request->ajax()) {
            $query = $request->get('query');
            $query = str_replace(" ", "%", $query);
            $project_milestones = ProjectMilestone::query();
            
            $project_milestones = $project_milestones->where('payment_status', 'Paid')->whereHas('project', function ($query) {
                $query->where('user_id', auth()->id())  
                    ->orderBy('id', 'DESC');
            })->where(function ($q) use ($query) {
                $q->orWhere('milestone_name', 'like', '%' . $query . '%')
                    ->orWhere('milestone_value', 'like', '%' . $query . '%')
                    ->orWhere('payment_mode', 'like', '%' . $query . '%')
                    ->orWhere('payment_status', 'like', '%' . $query . '%')
                    ->orWhere('payment_date', 'like', '%' . $query . '%')
                    ->orWhereHas('project', function ($q) use ($query) {
                        $q->where('user_id', auth()->id())  
                            ->where('business_name', 'like', '%' . $query . '%');
                    });
            })
            ->paginate(10);
            
            return response()->json(['data' => view('admin.payments.table', compact('project_milestones'))->render()]);
        }
    }
}
