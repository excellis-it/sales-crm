<?php

namespace App\Http\Controllers\TenderUser;

use App\Http\Controllers\Controller;
use App\Models\ProjectMilestone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = ProjectMilestone::with('tenderProject')
            ->whereNotNull('tender_project_id')
            ->whereHas('tenderProject', function ($q) {
                $q->where('tender_user_id', Auth::id());
            });

        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('milestone_name', 'like', '%' . $search . '%')
                    ->orWhere('milestone_value', 'like', '%' . $search . '%')
                    ->orWhere('payment_mode', 'like', '%' . $search . '%')
                    ->orWhere('payment_status', 'like', '%' . $search . '%')
                    ->orWhereHas('tenderProject', function ($q) use ($search) {
                        $q->where('tender_name', 'like', '%' . $search . '%')
                          ->orWhere('tender_id_ref_no', 'like', '%' . $search . '%');
                    });
            });
        }

        if ($request->payment_status) {
            $query->where('payment_status', $request->payment_status);
        }

        $payments = $query->orderBy('payment_date', 'desc')->paginate(15);

        if ($request->ajax()) {
            return view('tender_user.payment.table', compact('payments'))->render();
        }

        return view('tender_user.payment.list', compact('payments'));
    }

    public function downloadInvoice($id)
    {
        $milestone_detail = ProjectMilestone::where('id', $id)
            ->whereHas('tenderProject', function ($q) {
                $q->where('tender_user_id', Auth::id());
            })
            ->with('tenderProject', 'tenderProject.tenderUser')
            ->firstOrFail();

        $pdf = Pdf::loadView('tender_user.payment.invoice_pdf', compact('milestone_detail'));

        return $pdf->download('invoice-' . ($milestone_detail->tenderProject->tender_id_ref_no ?? $id) . '.pdf');
    }
}
