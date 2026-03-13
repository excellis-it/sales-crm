<?php

namespace App\Http\Controllers\TenderUser;

use App\Http\Controllers\Controller;
use App\Models\TenderStatus;
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
        return view('tender_user.tender_status.list', compact('tender_statuses'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tender_status = TenderStatus::findOrFail($id);
        return response()->json(['tender_status' => $tender_status]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
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
        return redirect()->route('tender-user.tender-statuses.index')->with('error', 'Tender Status deleted successfully.');
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
            return response()->json(['view' => (string)View::make('tender_user.tender_status.table', compact('tender_statuses'))]);
        }
    }
}
