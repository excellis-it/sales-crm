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
        $year = date('Y');
        $month = date('m');
        $project_milestones = ProjectMilestone::where('payment_status', 'Paid')
            ->whereYear('payment_date', $year)
            ->whereMonth('payment_date', $month)
            ->with(['project', 'tenderProject', 'bdmProject'])
            ->orderBy('id', 'DESC')
            ->paginate(10);
        return view('admin.payments.list', compact('project_milestones'));
    }

    public function adminInvoicedownload($id)
    {
        $milestone_detail = ProjectMilestone::where('id', $id)->with('project', 'project.salesManager', 'tenderProject', 'bdmProject')->first();
        $pdf = PDF::loadView('admin.invoicePdf', array('milestone_detail' => $milestone_detail));

        return $pdf->download('admin-invoice.pdf');
    }

    public function adminPaymentFilter(Request $request)
    {
        if ($request->ajax()) {
            $query = $request->get('query');
            $year = $request->get('year');
            $month = $request->get('month');
            $query = str_replace(" ", "%", $query);

            $project_milestones = ProjectMilestone::query()
                ->where('payment_status', 'Paid');

            if ($year) {
                $project_milestones->whereYear('payment_date', $year);
            }
            if ($month) {
                $project_milestones->whereMonth('payment_date', $month);
            }

            if ($query) {
                $project_milestones->where(function ($q) use ($query) {
                    $q->orWhere('milestone_name', 'like', '%' . $query . '%')
                        ->orWhere('milestone_value', 'like', '%' . $query . '%')
                        ->orWhere('payment_mode', 'like', '%' . $query . '%')
                        ->orWhere('payment_date', 'like', '%' . $query . '%')
                        ->orWhereHas('project', function ($q) use ($query) {
                            $q->where('business_name', 'like', '%' . $query . '%');
                        })
                        ->orWhereHas('tenderProject', function ($q) use ($query) {
                            $q->where('tender_name', 'like', '%' . $query . '%');
                        })
                        ->orWhereHas('bdmProject', function ($q) use ($query) {
                            $q->where('business_name', 'like', '%' . $query . '%');
                        });
                });
            }

            $project_milestones = $project_milestones->with(['project', 'tenderProject', 'bdmProject'])
                ->orderBy('id', 'DESC')
                ->paginate(10);

            return response()->json(['data' => view('admin.payments.table', compact('project_milestones'))->render()]);
        }
    }
}
