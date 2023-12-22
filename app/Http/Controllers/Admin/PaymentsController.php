<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Project;
use App\Models\ProjectMilestone;
use Illuminate\Support\Facades\View;
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
        $milestone_detail = ProjectMilestone::where('id', $id)->with('project','user')->first();
        $pdf = PDF::loadView('admin.invoicePdf',array('milestone_detail' => $milestone_detail));
    
        return $pdf->download('admin-invoice.pdf');
    }
}
