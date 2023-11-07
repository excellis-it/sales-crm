<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\RegistrationMail;
use App\Traits\ImageTrait;
use Illuminate\Support\Facades\Storage;
use File;
use Illuminate\Support\Facades\View;

class SalesExcecutiveController extends Controller
{
    use ImageTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->id) {
            $sales_excecutives = User::where('id', $request->id)->Role('SALES_EXCUETIVE')->orderBy('name', 'desc')->paginate(15);
            return view('admin.sales_excecutive.list')->with(compact('sales_excecutives'));
        }
        $sales_excecutives = User::Role('SALES_EXCUETIVE')->orderBy('name', 'desc')->paginate(15);
        return view('admin.sales_excecutive.list')->with(compact('sales_excecutives'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sales_managers = User::Role('SALES_MANAGER')->orderBy('name', 'DESC')->where('status', 1)->get();
        return view('admin.sales_excecutive.create')->with(compact('sales_managers'));
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
            'sales_manager_id' => 'required', // 'required|exists:users,id',
            'name' => 'required',
            'email' => 'required|unique:users|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix',
            'password' => 'required|min:8',
            'confirm_password' => 'required|min:8|same:password',
            'phone' => 'required',
            'status' => 'required',
        ]);

        $data = new User();
        $data->sales_manager_id = $request->sales_manager_id;
        $data->name = $request->name;
        $data->email = $request->email;
        $data->password = bcrypt($request->password);
        $data->phone = $request->phone;
        $data->employee_id = $request->employee_id;
        $data->date_of_joining = $request->date_of_joining;
        $data->status = $request->status;
        if ($request->hasFile('profile_picture')) {
            $request->validate([
                'profile_picture' => 'image|mimes:jpg,png,jpeg,gif,svg',
            ]);
            $data->profile_picture = $this->imageUpload($request->file('profile_picture'), 'sales_excecutive');
        }

        $data->save();
        $data->assignRole('SALES_EXCUETIVE');
        $maildata = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'type' => 'Sales excecutive',
        ];

        Mail::to($request->email)->send(new RegistrationMail($maildata));
        return redirect()->route('sales-excecutive.index')->with('message', 'Sales excecutive created successfully.');
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $sales_managers = User::Role('SALES_MANAGER')->orderBy('name', 'DESC')->where('status', 1)->get();
        $sales_excecutive = User::findOrFail($id);
        return view('admin.sales_excecutive.edit')->with(compact('sales_excecutive','sales_managers'));
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
            'sales_manager_id' => 'required', // 'required|exists:users,id',
            'name' => 'required',
            'email' => 'required|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix',
            'phone' => 'required',
            'status' => 'required',
        ]);
        $data = User::findOrFail($id);
        $data->sales_manager_id = $request->sales_manager_id;
        $data->name = $request->name;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->employee_id = $request->employee_id;
        $data->date_of_joining = $request->date_of_joining;
        $data->status = $request->status;
        if ($request->password != null) {
            $request->validate([
                'password' => 'min:8',
                'confirm_password' => 'min:8|same:password',
            ]);
            $data->password = bcrypt($request->password);
        }
        if ($request->hasFile('profile_picture')) {
            $request->validate([
                'profile_picture' => 'image|mimes:jpg,png,jpeg,gif,svg',
            ]);
            if ($data->profile_picture) {
                $currentImageFilename = $data->profile_picture; // get current image name
                Storage::delete('app/' . $currentImageFilename);
            }
            $data->profile_picture = $this->imageUpload($request->file('profile_picture'), 'sales_excecutive');
        }
        $data->save();
        return redirect()->route('sales-excecutive.index')->with('message', 'Sales manager updated successfully.');
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

    public function changeSalesExcecutiveStatus(Request $request)
    {
        $user = User::find($request->user_id);
        $user->status = $request->status;
        $user->save();
        return response()->json(['success' => 'Status change successfully.']);
    }

    public function delete($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('sales-excecutive.index')->with('error', 'Sales excecutive has been deleted successfully.');
    }

    public function search(Request $request)
    {
        if ($request->ajax()) {
            $sales_excecutives = User::query();
                $columns = ['name','email','phone', 'employee_id', 'date_of_joining'];
                foreach ($columns as $column) {
                    $sales_excecutives->orWhere($column, 'LIKE', '%' . $request->text . '%');
                }
                // serch by sales manager
                $sales_excecutives->orWhereHas('report_to', function ($query) use ($request) {
                    $query->where('name', 'LIKE', '%' . $request->text . '%');
                });

            $sales_excecutives = $sales_excecutives->Role('SALES_EXCUETIVE')->orderBy('name', 'desc')->paginate(15);
            return response()->json(['view' => (string)View::make('admin.sales_excecutive.table')->with(compact('sales_excecutives'))]);
        }
    }
}
