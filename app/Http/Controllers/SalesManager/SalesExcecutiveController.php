<?php

namespace App\Http\Controllers\SalesManager;

use App\Http\Controllers\Controller;
use App\Mail\RegistrationMail;
use App\Models\User;
use App\Traits\ImageTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Storage;

class SalesExcecutiveController extends Controller
{
    use ImageTrait;

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $sales_excecutives = User::role('SALES_EXCUETIVE')->where('sales_manager_id', Auth::user()->id)->orderBy('id', 'desc')->paginate(15);
        if ($request->ajax()) {
            return response()->json(['view' => (string)View::make('sales_manager.sales_excecutive.table')->with(compact('sales_excecutives'))]);
        }
        return view('sales_manager.sales_excecutive.list')->with(compact('sales_excecutives'));
    }

    public function salesExecutiveSerach(Request $request)
    {
        if ($request->ajax()) {
            $sales_excecutives = User::query();
            $text = $request->text;
            if ($text) {
                $sales_excecutives->where(function($query) use ($text) {
                    $query->where('name', 'LIKE', '%' . $text . '%')
                          ->orWhere('email', 'LIKE', '%' . $text . '%')
                          ->orWhere('phone', 'LIKE', '%' . $text . '%')
                          ->orWhere('employee_id', 'LIKE', '%' . $text . '%')
                          ->orWhere('date_of_joining', 'LIKE', '%' . $text . '%');
                });
            }
            $sales_excecutives = $sales_excecutives->Role('SALES_EXCUETIVE')
                ->where('sales_manager_id', Auth::user()->id)
                ->orderBy('name', 'desc')
                ->paginate(15);

            // Append search text to pagination links
            $sales_excecutives->appends(['text' => $text]);

            return response()->json(['view' => (string)View::make('sales_manager.sales_excecutive.table')->with(compact('sales_excecutives'))]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('sales_manager.sales_excecutive.create');
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
        $data->sales_manager_id = Auth::user()->id;
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
        try {
            Mail::to($request->email)->send(new RegistrationMail($maildata));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
        return response()->json(['success' => 'Sales excecutive created successfully.']);
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
        $sales_excecutive = User::find($id);
        return response()->json(['sales_excecutive' => $sales_excecutive]);
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
        // dd($request->all());
        $request->validate([
            'name' => 'required',
            'email' => 'required|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix|unique:users,email,' . $id,
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
            $data->profile_picture = $this->imageUpload($request->file('profile_picture'), 'sales_excecutive');
        }
        $data->save();
        session()->flash('message', 'Sales excecutive updated successfully.');
        return response()->json(['success' => 'Sales excecutive updated successfully.']);
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
        return redirect()->route('sales-manager.sales-excecutive.index')->with('error', 'Sales excecutive has been deleted successfully.');
    }
}
