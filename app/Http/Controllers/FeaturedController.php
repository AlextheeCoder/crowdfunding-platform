<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FeaturedController extends Controller
{
    //
    public function index()
    {
        $campaigns = Campaign::with('pledges')->get();
    
        // Calculate the number of backers for each campaign and add the property to the campaign object
        $campaigns->each(function ($campaign) {
            // Get the number of backers for this campaign
            $backersCount = $campaign->pledges->count();
            $campaign->backersCount = $backersCount;
    
            // Calculate the total amount pledged for this campaign
            $totalPledged = $campaign->pledges->sum('amount');
            $campaign->totalPledged = $totalPledged;
        });
    
        // Sort the campaigns based on the number of backers in descending order
        $mostBackedProjects = $campaigns->sortByDesc('backersCount')->take(4);
    
        return view('index')->with([
            'mostBackedProjects' => $mostBackedProjects,
        ]);
    }
}
