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

class AccountManagerController extends Controller
{
    use ImageTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $account_managers = User::Role('ACCOUNT_MANAGER')->orderBy('name', 'desc')->paginate(15);
        return view('admin.account_manager.list')->with(compact('account_managers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.account_manager.create');
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
            'name' => 'required',
            'email' => 'required|unique:users|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix',
            'password' => 'required|min:8',
            'confirm_password' => 'required|min:8|same:password',
            'phone' => 'required',
            'status' => 'required',
        ]);

        $data = new User();
        $data->name = $request->name;
        $data->email = $request->email;
        $data->password = bcrypt($request->password);
        $data->phone = $request->phone;
        $data->status = $request->status;
        $data->employee_id = $request->employee_id;
        $data->date_of_joining = $request->date_of_joining;
        if ($request->hasFile('profile_picture')) {
            $request->validate([
                'profile_picture' => 'image|mimes:jpg,png,jpeg,gif,svg',
            ]);
            $data->profile_picture = $this->imageUpload($request->file('profile_picture'), 'account_manager');
        }
        $data->save();
        $data->assignRole('ACCOUNT_MANAGER');
        $maildata = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'type' => 'Account manager',
        ];

        Mail::to($request->email)->send(new RegistrationMail($maildata));
        return response()->json(['success' => 'Account manager created successfully.']);
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
        
        $account_manager = User::findOrFail($id);
        return response()->json(['account_manager' => $account_manager]);
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
            'email' => 'required|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix',
            'phone' => 'required',
            'status' => 'required',
        ]);
        $data = User::findOrFail($id);
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
            $data->profile_picture = $this->imageUpload($request->file('profile_picture'), 'account_manager');
        }
        $data->save();
        return response()->json(['success' => 'Account manager updated successfully.']);
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

    public function changeAccountManagerStatus(Request $request)
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
        return redirect()->route('account_managers.index')->with('error', 'Account manager has been deleted successfully.');
    }

    public function search(Request $request)
    {
        if ($request->ajax()) {
            $account_managers = User::query();
                $columns = ['name','email','phone', 'employee_id', 'date_of_joining'];
                foreach ($columns as $column) {
                    $account_managers->orWhere($column, 'LIKE', '%' . $request->text . '%');
                }
            $account_managers = $account_managers->Role('ACCOUNT_MANAGER')->orderBy('name', 'desc')->paginate(15);
            return response()->json(['view' => (string)View::make('admin.account_manager.table')->with(compact('account_managers'))]);
        }
    }
}
