<?php

namespace App\Http\Controllers\AccountManager;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $count['projects'] = Project::where('assigned_to', auth()->user()->id)->count();
        return view('account_manager.dashboard')->with(compact('count'));
    }
}
