<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Goal;
use App\Models\User;
use Illuminate\Http\Request;

class GoalsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $salesManagerGoals = Goal::with('user')->whereHas('user', function ($query) {
            // where Role SALES_MANAGER
            $query->whereHas('roles', function ($query) {
                $query->where('name', 'SALES_MANAGER');
            });
        })->get();

        $accountManagerGoals = Goal::with('user')->whereHas('user', function ($query) {
            $query->whereHas('roles', function ($query) {
                $query->where('name', 'ACCOUNT_MANAGER');
            });
        })->get();
        $users = User::role(['SALES_MANAGER','ACCOUNT_MANAGER'])->orderBy('name', 'asc')->get();
        return view('admin.goals.list')->with(compact('salesManagerGoals','accountManagerGoals','users'));
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
}
