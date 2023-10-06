<?php

namespace App\Http\Controllers\SalesExcecutive;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Prospect;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
class ProspectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($type = null)
    {
        $type = request()->get('type');

        if ($type) {
            // return $type;
            $prospects = Prospect::where(['user_id'=> Auth::user()->id, 'status'=>$type])->orderBy('id', 'desc')->get();
        } else {
            $prospects = Prospect::where('user_id', Auth::user()->id)->orderBy('id', 'desc')->get();
        }

        return view('sales_excecutive.prospect.list')->with(compact('prospects'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('sales_excecutive.prospect.create');
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
        $prospect->user_id = Auth::user()->id;
        $prospect->report_to = Auth::user()->sales_manager_id;
        $prospect->client_name = $data['client_name'];
        $prospect->business_name = $data['business_name'];
        $prospect->followup_time = $data['followup_time'];
        $prospect->client_email = $data['client_email'];
        $prospect->client_phone = $data['client_phone'];
        $prospect->business_address = $data['business_address'];
        $prospect->website = $data['website'];
        $prospect->status = $data['status'];
        $prospect->followup_date = $data['followup_date'];
        $prospect->next_followup_date = $data['next_followup_date'];
        $prospect->comments = $data['comments'];
        $prospect->price_quote = $data['price_quote'];
        $prospect->offered_for = $data['offered_for'];
        $prospect->transfer_token_by = $data['transfer_token_by'];
        // date add in created_at and updated_at
        $prospect->created_at = date('Y-m-d H:i:s', strtotime($data['date']));
        $prospect->save();

        return redirect()->route('prospects.index')->with('message', 'Prospect created successfully.');
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
            return view('sales_excecutive.prospect.view')->with(compact('prospect'));
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
            return view('sales_excecutive.prospect.edit')->with(compact('prospect'));
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
        $prospect->user_id = Auth::user()->id;
        $prospect->report_to = Auth::user()->sales_manager_id;
        $prospect->client_name = $data['client_name'];
        $prospect->business_name = $data['business_name'];
        $prospect->followup_time = $data['followup_time'];
        $prospect->client_email = $data['client_email'];
        $prospect->client_phone = $data['client_phone'];
        $prospect->business_address = $data['business_address'];
        $prospect->website = $data['website'];
        $prospect->status = $data['status'];
        $prospect->followup_date = $data['followup_date'];
        $prospect->next_followup_date = $data['next_followup_date'];
        $prospect->comments = $data['comments'];
        $prospect->price_quote = $data['price_quote'];
        $prospect->offered_for = $data['offered_for'];
        $prospect->transfer_token_by = $data['transfer_token_by'];
        $prospect->created_at = date('Y-m-d H:i:s', strtotime($data['date']));
        $prospect->save();
        return redirect()->route('prospects.index')->with('message', 'Prospect updated successfully.');
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
}
