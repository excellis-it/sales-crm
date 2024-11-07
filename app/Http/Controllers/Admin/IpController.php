<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ip;
use Illuminate\Support\Facades\View;

class IpController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ip = Ip::orderBy('id','desc')->paginate(15);
        return view('admin.ip.list',compact('ip'));
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
            'ip' => 'required|unique:ips',
        ]);

        $ip = new Ip();
        $ip->ip = $request->ip;

        $ip->save();

        return redirect()->back()->with('message','Ip added successfully.');
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
        $ip =  Ip::find($id);
        return response()->json(['ip' => $ip]);
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
            'ip' => 'required',
        ]);

        $ip = Ip::findOrFail($id);
        $ip->ip = $request->ip;
        $ip->save();

        return redirect()->back()->with('message','Ip updated successfully.');
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
        $user = Ip::findOrFail($id);
        $user->delete();
        return redirect()->route('ips.index')->with('error', 'Ip has been deleted successfully.');
    }

    public function search(Request $request)
    {
        if ($request->ajax()) {
            $ip = Ip::query();
                $columns = ['ip'];
                foreach ($columns as $column) {
                    $ip->orWhere($column, 'LIKE', '%' . $request->text . '%');
                }
            $ip = $ip->orderBy('ip', 'desc')->paginate(15);
            return response()->json(['view' => (string)View::make('admin.ip.table')->with(compact('ip'))]);
        }
    }
}
