<?php

namespace App\Http\Controllers\BDE;

use App\Http\Controllers\Controller;
use App\Models\Goal;
use App\Models\Project;
use App\Models\Prospect;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //

    public function index()
    {
        return view('bde.dashboard');
    }
}
