<?php

namespace App\Http\Controllers\SalesExcecutive;

use App\Http\Controllers\Controller;
use App\Models\Prospect;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $count['prospects'] = Prospect::where('user_id', auth()->user()->id)->count();
        $count['win'] = Prospect::where('user_id', auth()->user()->id)->where('status', 'Win')->count();
        $count['follow_up'] = Prospect::where('user_id', auth()->user()->id)->where('status', 'Follow Up')->count();
        $count['close'] = Prospect::where('user_id', auth()->user()->id)->where('status', 'Close')->count();
        return view('sales_excecutive.dashboard')->with(compact('count'));
    }
}
