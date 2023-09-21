<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $count['sales_managers'] = User::Role('SALES_MANAGER')->count();
        $count['account_managers'] = User::Role('ACCOUNT_MANAGER')->count();
        $count['sales_excecutive'] = User::Role('SALES_EXCUETIVE')->count();
        $count['projects'] = Project::count();
        $count['prospects'] = Prospect
        return view('admin.dashboard')->with(compact('count'));
    }
}
