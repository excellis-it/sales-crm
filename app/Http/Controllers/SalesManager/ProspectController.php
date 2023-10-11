<?php

namespace App\Http\Controllers\SalesManager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Prospect;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class ProspectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $count['win'] = Prospect::where('report_to', auth()->user()->id)->where('status', 'Win')->count();
        $count['follow_up'] = Prospect::where('report_to', auth()->user()->id)->where('status', 'Follow Up')->count();
        $count['close'] = Prospect::where('report_to', auth()->user()->id)->where('status', 'Close')->count();
        $count['sent_proposal'] = Prospect::where('report_to', auth()->user()->id)->where('status', 'Sent Proposal')->count();
        $prospects = Prospect::where('report_to', Auth::user()->id)->orderBy('id', 'desc')->get();
        return view('sales_manager.prospect.list')->with(compact('prospects','count'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::role(['SALES_MANAGER', 'ACCOUNT_MANAGER', 'SALES_EXCUETIVE'])->get();
        $sales_executives = User::role('SALES_EXCUETIVE')->where(['status' => 1, 'sales_manager_id' => Auth::user()->id])->orderBy('id', 'desc')->get();
        return view('sales_manager.prospect.create')->with(compact('sales_executives','users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // try {
        $data = $request->all();

        $prospect = new Prospect();
        $prospect->user_id = $data['user_id'];
        $prospect->report_to = Auth::user()->id;
        $prospect->client_name = $data['client_name'];
        $prospect->business_name = $data['business_name'];
        $prospect->client_email = $data['client_email'];
        $prospect->client_phone = $data['client_phone'];
        $prospect->business_address = $data['business_address'];
        $prospect->website = $data['website'];
        $prospect->status = $data['status'];
        $prospect->followup_date = $data['followup_date'];
        $prospect->followup_time = $data['followup_time'];
        $prospect->next_followup_date = $data['next_followup_date'];
        $prospect->comments = $data['comments'];
        $prospect->price_quote = $data['price_quote'];
        if ($data['offered_for'] == 'Other') {
            $prospect->offered_for = $data['other_value'];
        } else {
            $prospect->offered_for = $data['offered_for'];
        }
        $prospect->transfer_token_by = $data['transfer_token_by'];
        $prospect->save();

        return redirect()->route('sales-manager.prospects.index')->with('message', 'Prospect created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $prospect = Prospect::find($id);
            $isThat = 'view';
            return response()->json(['view'=>(String)View::make('sales_manager.prospect.show-details')->with(compact('prospect','isThat'))]);
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $prospect = Prospect::find($id);
            $users = User::role(['SALES_MANAGER', 'ACCOUNT_MANAGER', 'SALES_EXCUETIVE'])->get();
            $sales_executives = User::role('SALES_EXCUETIVE')->where(['status' => 1])->orderBy('id', 'desc')->get();
            return view('sales_manager.prospect.edit')->with(compact('prospect','sales_executives', 'users'));
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
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
        $data = $request->all();
        $prospect = Prospect::findOrfail($id);
        $prospect->user_id = $data['user_id'];
        $prospect->report_to = Auth::user()->id;
        $prospect->client_name = $data['client_name'];
        $prospect->business_name = $data['business_name'];
        $prospect->client_email = $data['client_email'];
        $prospect->client_phone = $data['client_phone'];
        $prospect->business_address = $data['business_address'];
        $prospect->website = $data['website'];
        $prospect->status = $data['status'];
        $prospect->followup_date = $data['followup_date'];
        $prospect->followup_time = $data['followup_time'];
        $prospect->next_followup_date = $data['next_followup_date'];
        $prospect->comments = $data['comments'];
        $prospect->price_quote = $data['price_quote'];
        if ($data['offered_for'] == 'Other') {
            $prospect->offered_for = $data['other_value'];
        } else {
            $prospect->offered_for = $data['offered_for'];
        }
        $prospect->transfer_token_by = $data['transfer_token_by'];
        $prospect->save();
        return redirect()->route('sales-manager.prospects.index')->with('message', 'Prospect updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function delete($id)
    {
        $prospect = Prospect::find($id);
        $prospect->delete();
        return redirect()->back()->with('message', 'Prospect deleted successfully.');
    }

    public function filter(Request $request)
    {
        if ($request->ajax()) {
            $status = $request->status;
            if ($status == 'All') {
                $prospects = Prospect::where(['report_to' => Auth::user()->id])->orderBy('id', 'desc')->get();
            } else {
                $prospects = Prospect::where(['report_to' => Auth::user()->id, 'status' => $status])->orderBy('id', 'desc')->get();
            }

            return response()->json(['view'=>(String)View::make('sales_manager.prospect.table')->with(compact('prospects'))]);
        }
    }
}
