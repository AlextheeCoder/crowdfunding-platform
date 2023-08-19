<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Pledge;
use App\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    //
    public function index() {
        $campaignCount = Campaign::count();
        $userCount = User::count();
        $pledgeCount = Pledge::count();
        $dailyPledgeCount = Pledge::whereDate('created_at', '=', Carbon::today())->count();
    
        // Data for the chart
        $dates = Pledge::select(DB::raw('DATE(created_at) as date'))
                       ->groupBy(DB::raw('DATE(created_at)'))
                       ->get()
                       ->pluck('date');
        
        $totalPledgesPerDay = Pledge::select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(amount) as total'))
                                   ->groupBy(DB::raw('DATE(created_at)'))
                                   ->get()
                                   ->pluck('total');
    
        $numberOfPledgesPerDay = Pledge::select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(id) as count'))
                                      ->groupBy(DB::raw('DATE(created_at)'))
                                      ->get()
                                      ->pluck('count');
    
        return view('admin.index', [
            'campaignCount' => $campaignCount,
            'userCount' => $userCount,
            'pledgeCount' => $pledgeCount,
            'dailyPledgeCount' => $dailyPledgeCount,
            'chartData' => [
                'dates' => $dates,
                'totalPledgesPerDay' => $totalPledgesPerDay,
                'numberOfPledgesPerDay' => $numberOfPledgesPerDay,
            ]
        ]);
    }
    
}
