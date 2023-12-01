<?php

namespace App\Http\Controllers\BDM;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\RegistrationMail;
use App\Traits\ImageTrait;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Storage;
use File;
use Illuminate\Support\Facades\Auth;

class BusinessDevelopmentExcecutiveController extends Controller
{
    use ImageTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $business_development_excecutives  = User::Role('BUSINESS_DEVELOPMENT_EXCECUTIVE')->where('bdm_id', Auth::user()->id)->paginate(15);
        return view('bdm.business_development_excecutive.list')->with(compact('business_development_excecutives'));
    }

    public function bdmBusinessDevelopmentExecutiveSearch(Request $request)
    {
        if ($request->ajax()) {
            $business_development_excecutives = User::query();
                $columns = ['name','email','phone', 'employee_id', 'date_of_joining'];
                foreach ($columns as $column) {
                    $business_development_excecutives->orWhere($column, 'LIKE', '%' . $request->text . '%');
                }
            $business_development_excecutives = $business_development_excecutives->Role('BUSINESS_DEVELOPMENT_EXCECUTIVE')->orderBy('name', 'desc')->paginate(15);
            return response()->json(['view' => (string)View::make('bdm.business_development_excecutive.table')->with(compact('business_development_excecutives'))]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('bdm.business_development_excecutive.create')->with(compact('business_development_managers'));
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
        $data->bdm_id = auth()->user()->id;
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
            $data->profile_picture = $this->imageUpload($request->file('profile_picture'), 'business_development_excecutive');
        }

        $data->save();
        $data->assignRole('BUSINESS_DEVELOPMENT_EXCECUTIVE');
        $maildata = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'type' => 'BDE',
        ];

        Mail::to($request->email)->send(new RegistrationMail($maildata));
        return response()->json(['success' => 'BDE created successfully.']);
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
        $business_development_excecutive = User::findOrFail($id);
        return response()->json(['bde' => $business_development_excecutive]);
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
        $data->bdm_id = auth()->user()->id;
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
            $data->profile_picture = $this->imageUpload($request->file('profile_picture'), 'business_development_excecutive');
        }
        $data->save();
        return response()->json(['success' => 'BDE updated successfully.']);
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

    public function changeBDEStatus(Request $request)
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
        return redirect()->route('bde.index')->with('error', 'BDE has been deleted successfully.');
    }
}
