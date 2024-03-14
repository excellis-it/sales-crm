<?php

namespace App\Http\Controllers\AccountManager;

use App\Http\Controllers\Controller;
use App\Models\Followup;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class FollowupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $followups = Followup::where('user_id', auth()->user()->id)->get()->groupBy('project_id'); 
        $projects = Project::whereIn('id', $followups->keys())->paginate(10);
        $data = Project::where('assigned_to', auth()->user()->id)->get();
        return view('account_manager.followup.list', compact('projects', 'followups', 'data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            "project_id" => "required",
            "followup_type" => "required",
            "followup_description" => "required",
        ]);

        $followup = new Followup();
        $followup->project_id = $request->project_id;
        $followup->user_id = auth()->user()->id;
        $followup->followup_type = $request->followup_type;
        $followup->followup_description = $request->followup_description;
        $followup->followup_date = date('Y-m-d H:i:s');
        $followup->save();

        return response()->json([
            'success' => true,
            'message' => 'Followup added successfully',
            'data' => $followup
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $followups = Followup::where('project_id', $id)->get();
        $isThat = true;
        return response()->json(['view' => (string)View::make('account_manager.followup.show-details')->with(compact('followups','isThat'))]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
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

    public function accountManagerFollowupProject(Request $request)
    {
        $sort_by = $request->get('sortby');
        $sort_type = $request->get('sorttype');
        $query = $request->get('query');
        $query = str_replace(" ", "%", $query);

        $followups = Followup::where('user_id', auth()->user()->id)->get()->groupBy('project_id');
        $projects = Project::whereIn('id', $followups->keys())->orderBy($sort_by, $sort_type)->where(function ($q) use ($query) {
            $q->orWhere('business_name', 'like', '%' . $query . '%')
                ->orWhere('client_name', 'like', '%' . $query . '%')
                ->orWhere('client_phone', 'like', '%' . $query . '%');

        })->paginate(10);
        return response()->json(['data' => view('account_manager.followup.table', compact('projects'))->render()]);
    }
}
