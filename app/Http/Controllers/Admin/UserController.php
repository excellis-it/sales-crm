<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\View;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $customers = Customer::orderBy('id','desc')->paginate(15);
        return view('admin.customer.list',compact('customers'));
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
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix',
            'phone' => 'required',
            'address' => 'required',
        ]);

        $customer_add = new Customer();
        $customer_add->customer_name = $request->name;
        $customer_add->customer_email = $request->email;
        $customer_add->customer_phone = $request->phone;
        $customer_add->customer_address = $request->address;
        $customer_add->save();

        return redirect()->back()->with('message','Customer added successfully.');
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
        $customer =  Customer::find($id);
        return response()->json(['customer' => $customer]);
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
            'name' => 'required',
            'email' => 'required|unique:users|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix',
            'phone' => 'required',
            'address' => 'required',
        ]);

        $customer_add = Customer::findOrFail($id);
        $customer_add->customer_name = $request->name;
        $customer_add->customer_email = $request->email;
        $customer_add->customer_phone = $request->phone;
        $customer_add->customer_address = $request->address;
        $customer_add->save();

        return redirect()->back()->with('message','Customer added successfully.');
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
        $user = Customer::findOrFail($id);
        $user->delete();
        return redirect()->route('customers.index')->with('error', 'Customer has been deleted successfully.');
    }

    public function search(Request $request)
    {
        if ($request->ajax()) {
            $customers = Customer::query();
                $columns = ['customer_name','customer_email','customer_phone', 'customer_address'];
                foreach ($columns as $column) {
                    $customers->orWhere($column, 'LIKE', '%' . $request->text . '%');
                }
            $customers = $customers->orderBy('customer_name', 'desc')->paginate(15);
            return response()->json(['view' => (string)View::make('admin.customer.table')->with(compact('customers'))]);
        }
    }
}
