<?php

namespace App\Http\Controllers\SalesExcecutive;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $projects = Project::where('project_opener', auth()->user()->id)->orderBy('created_at', 'desc')->paginate(15);
        return view('sales_excecutive.project.list',compact('projects'));
    }


    public function salesExecutiveProjectFilter(Request $request)
    {
        
        if ($request->ajax()) {
            $sort_by = $request->get('sortby');
            $sort_type = $request->get('sorttype');
            $query = $request->get('query');
            $query = str_replace(" ", "%", $query);

            $projects = Project::where('project_opener', auth()->user()->id)->orderBy($sort_by, $sort_type)->where(function ($q) use ($query) {
                $q->orWhere('sale_date', 'like', '%' . $query . '%')
                    ->orWhere('business_name', 'like', '%' . $query . '%')
                    ->orWhere('business_name', 'like', '%' . $query . '%')
                    ->orWhere('client_name', 'like', '%' . $query . '%')
                    ->orWhere('client_phone', 'like', '%' . $query . '%')
                    ->orWhere('project_value', 'like', '%' . $query . '%')
                    ->orWhere('project_upfront', 'like', '%' . $query . '%')
                    ->orWhere('currency', 'like', '%' . $query . '%')
                    ->orWhere('payment_mode', 'like', '%' . $query . '%')
                    ->orWhereHas('projectTypes', function ($q) use ($query) {
                        $q->Where('type', 'like', '%' . $query . '%');
                    })
                    ->orWhereRaw('project_value - project_upfront like ?', ["%{$query}%"]);
                    
            })->paginate(15);
            
            // ->orWhereHas('projectTypes', function ($q) use ($query) {
            //     $q->orWhere('type', 'like', '%' . $query . '%');
            // })
            // ->orWhereRaw('project_value - project_upfront like ?', ["%{$query}%"])
            // ->orderBy($sort_by, $sort_type)
            // ->paginate(15);            

            return response()->json(['data' => view('sales_excecutive.project.table', compact('projects'))->render()]);
        }
    }

    public function projectAjaxList()
    {
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // Rows display per page

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value

        $totalRecords = Project::where('project_opener', auth()->user()->id)->count();
        $totalRecordswithFilter = Project::where('client_name', 'like', '%' . $searchValue . '%')->where('project_opener', auth()->user()->id)->count();
        $records = Project::orderBy($columnName, $columnSortOrder)->where('client_name', 'like', '%' . $searchValue . '%')->skip($start)->take($rowperpage)->get();

        $data_arr = array();
        foreach ($records as $key => $record) {
            $data_arr[] = array(
                'created_at' => date('d-m-Y', strtotime($record->sale_date)),
                'business_name' => $record->business_name,
                'client_name' => $record->client_name,
                'client_phone' => $record->client_phone,
                'project_value' => $record->project_value,
                'project_upfront' => $record->project_upfront,
                'currency' => $record->currency,
                'sale_by' => $record->salesManager->name,
                'payment_mode' => $record->payment_mode,
                'due_amount' => $record->project_value - $record->project_upfront,
                'assigned_to' => $record->assigned_to ? '<span class="badge bg-success">Assigned</span>' : '<span class="badge bg-danger">Not Assigned</span>',
                'action' => '<a href="' . route('sales-projects.show', $record->id) . '" class="btn btn-sm btn-primary"><i class="fa fa-eye"></i></a> <a href="' . route('sales-projects.edit', $record->id) . '" class="btn btn-sm btn-warning"><i class="fa fa-edit"></i></a> <a href="javascipt:void(0);" data-route="' . route('sales-projects.delete', $record->id) . '" class="btn btn-sm btn-danger" id="delete"><i class="fa fa-trash"></i></a>'
            );
        }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr
         );

         return response()->json($response);
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
        $project = Project::find($id);
        return view('sales_excecutive.project.view', compact('project'));
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
