<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TenderStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class TenderStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tender_statuses = TenderStatus::orderBy('name', 'asc')->paginate(15);
        return view('admin.tender_status.list', compact('tender_statuses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Not used as we use offcanvas
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:tender_statuses,name',
            'status' => 'required',
        ]);

        TenderStatus::create([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return response()->json(['success' => 'Tender Status created successfully.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(string $id)
    {
        $tender_status = TenderStatus::findOrFail($id);
        return response()->json(['tender_status' => $tender_status]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|unique:tender_statuses,name,' . $id,
            'status' => 'required',
        ]);

        $tender_status = TenderStatus::findOrFail($id);
        $tender_status->update([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return response()->json(['success' => 'Tender Status updated successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tender_status = TenderStatus::findOrFail($id);
        $tender_status->delete();
        return redirect()->route('admin.tender-statuses.index')->with('error', 'Tender Status deleted successfully.');
    }

    public function changeStatus(Request $request)
    {
        $tender_status = TenderStatus::find($request->id);
        $tender_status->status = $request->status;
        $tender_status->save();
        return response()->json(['success' => 'Status changed successfully.']);
    }

    public function search(Request $request)
    {
        if ($request->ajax()) {
            $tender_statuses = TenderStatus::where('name', 'LIKE', '%' . $request->text . '%')
                ->orderBy('name', 'asc')
                ->paginate(15);
            return response()->json(['view' => (string)View::make('admin.tender_status.table', compact('tender_statuses'))]);
        }
    }
}
