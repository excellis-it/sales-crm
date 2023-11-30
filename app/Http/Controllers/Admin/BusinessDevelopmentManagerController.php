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

class BusinessDevelopmentManagerController extends Controller
{
    use ImageTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $business_development_managers = User::Role('BUSINESS_DEVELOPMENT_MANAGER')->orderBy('name', 'desc')->paginate(15);
        return view('admin.business_development_manager.list')->with(compact('business_development_managers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.business_development_manager.create');
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
        $data->employee_id = $request->employee_id;
        $data->date_of_joining = $request->date_of_joining;
        $data->status = $request->status;
        if ($request->hasFile('profile_picture')) {
            $request->validate([
                'profile_picture' => 'image|mimes:jpg,png,jpeg,gif,svg',
            ]);
            $data->profile_picture = $this->imageUpload($request->file('profile_picture'), 'business_development_manager');
        }

        $data->save();
        $data->assignRole('BUSINESS_DEVELOPMENT_MANAGER');
        $maildata = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'type' => 'Business Development Manager',
        ];

        Mail::to($request->email)->send(new RegistrationMail($maildata));
        return response()->json(['success' => 'Business Development Manager created successfully.']);
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
        $business_development_manager = User::findOrFail($id);
        return response()->json(['business_development_manager' => $business_development_manager]);
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
            $data->profile_picture = $this->imageUpload($request->file('profile_picture'), 'business_development_manager');
        }
        $data->save();
        return response()->json(['success' => 'Business Development Manager updated successfully.']);
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

    public function changeBusinessDevelopmentManagerStatus(Request $request)
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
        return redirect()->route('business-development-managers.index')->with('error', 'Business Development Manager has been deleted successfully.');
    }

    public function search(Request $request)
    {
        if ($request->ajax()) {
            $business_development_managers = User::query();
                $columns = ['name','email','phone', 'employee_id', 'date_of_joining'];
                foreach ($columns as $column) {
                    $business_development_managers->orWhere($column, 'LIKE', '%' . $request->text . '%');
                }
                // serch by sales manager
                $business_development_managers->orWhereHas('report_to', function ($query) use ($request) {
                    $query->where('name', 'LIKE', '%' . $request->text . '%');
                });

            $business_development_managers = $business_development_managers->Role('BUSINESS_DEVELOPMENT_MANAGER')->orderBy('name', 'desc')->paginate(15);
            return response()->json(['view' => (string)View::make('admin.business_development_manager.table')->with(compact('business_development_managers'))]);
        }
    }
}
