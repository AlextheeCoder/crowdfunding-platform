<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Campaign;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class CampaignController extends Controller
{
    // 
    public function show()
    {
        $user = auth()->user();
        if ($user && $user->ethereum_address) {
            return view("pages.create");
        } else {
            return redirect('/')->with('message', 'Please connect your wallet to access the create page');
        }
    }

    public function discover(Request $request)
    {
        // Get the search query from the request
        $searchQuery = $request->input('search');
    
        // Fetch campaigns with pledges using eager loading
        $query = Campaign::with('pledges');
    
        // Apply the search filter if the search query is present
        if ($searchQuery) {
            $query->where('title', 'like', '%' . $searchQuery . '%')
                ->orWhere('description', 'like', '%' . $searchQuery . '%')
                ->orWhere('category', 'like', '%' . $searchQuery . '%');
        }
    
        // Get the filtered campaigns
        $campaigns = $query->get();
        $allCampaigns = Campaign::all();
    
        // Calculate the number of unique investors and total amount pledged for each campaign
        $campaigns->each(function ($campaign) {
            $uniqueInvestorsCount = $campaign->pledges->pluck('user_id')->unique()->count();
            $campaign->uniqueInvestorsCount = $uniqueInvestorsCount;
    
            $totalPledged = $campaign->pledges->sum('amount');
            $campaign->totalPledged = $totalPledged;
        });
    
        return view("pages.discover")->with([
            'campaigns' => $campaigns,
            'allCampaigns' => $allCampaigns,
        ]);
    }
    

    public function single(Campaign $campaign){

        $investorsCount = $campaign->pledges()->distinct('user_id')->count();
        return view('pages.single-campaign', [
            'campaign' => $campaign,
            'investorsCount' => $investorsCount,
        ]);
    }

   
    public function createCampaign(Request $request)
    {
        $formFields = $request->validate([
            'title' => 'required|string',
            'category'=>'required',
            'description' => 'required|string',
            'target' => 'required',
            'date' => 'required|date',
            
            
        ]);
    
        if ($request->hasFile('image')) {
            $formFields['image'] = $request->file('image')->store('logos', 'public');
        }
    
        
    
        // Get the connected user's Ethereum address and ID
        $user = $request->user();
        $ethereumAddress = $user->ethereum_address;
        $userId = $user->id;
    
        // Add the Ethereum address and user_id to the form fields
        $formFields['ethereum_address'] = $ethereumAddress;
        $formFields['user_id'] = $userId;
    
        // Make sure the 'deadline' field is provided and is a valid date
        $formFields['deadline'] = Carbon::parse($formFields['date'])->toDateString();
    
        Campaign::create($formFields);

        return redirect('/');
    }
    
    

        public function pledge($id, Request $request)
        {
            // Retrieve the campaign by ID
            $campaign = Campaign::findOrFail($id);
            
            // Validate the pledge amount
            $request->validate([
                'pledge' => 'required|numeric',
            ]);
            
            // Retrieve the pledge amount from the request
            $pledgeAmount = $request->input('pledge');
            
            // Subtract the pledge amount from the current target
            $newTarget = $campaign->target - $pledgeAmount;
            
            // Update the target value in the database
            $campaign->target = $newTarget;
            $campaign->save();
            
            // Create an instance of the PledgeController and call its process method
            $pledgeController = new PledgeController();
            $pledgeController->process($id, $request, $campaign->ethereum_address); // Pass ethereum_address here
            
            // Return a response indicating the successful update
            return response()->json([
                'message' => 'Target amount updated successfully.',
                'new_target' => $newTarget,
            ]);
        }


        public function delete(Campaign $campaign){
            
            if($campaign->user_id != auth()->id()) {
                abort(403, 'Unauthorized Action');
            }
            
            if($campaign->image && Storage::disk('public')->exists($campaign->image)) {
                Storage::disk('public')->delete($campaign->image);
            }
            $campaign->delete();
            return redirect('/profile')->with('message', 'Listing deleted successfully');
            
        }


        public function edit($id)
    {
        $campaign = Campaign::findOrFail($id);

        // Check if the authenticated user is the owner of the campaign
        if ($campaign->user_id != auth()->user()->id) {
            return redirect()->route('/')->with('message', 'You are not authorized to edit this campaign.');
        }

        return view('pages.edit', compact('campaign'));
    }

    
    public function update(Request $request, $id)
    {
        $campaign = Campaign::findOrFail($id);

        // Check if the authenticated user is the owner of the campaign
        if ($campaign->user_id != auth()->user()->id) {
            return redirect()->route('/')->with('error', 'You are not authorized to edit this campaign.');
        }

        // Validate the form data
        $request->validate([
            'title' => 'required|string',
            'category'=>'required',
            'description' => 'required|string',
            'target' => 'required',
            'date' => 'required|date',
        ]);

        // Update the campaign data
        $campaign->title = $request->title;
        $campaign->description = $request->description;
        $campaign->target = $request->target;
        $campaign->deadline = $request->date;

        // Check if a new image file was uploaded
        if ($request->hasFile('image')) {
            $campaign->image = $request->file('image')->store('logos', 'public');
        }

        $campaign->save();

        return redirect('/profile')->with('message', 'Campaign updated successfully.');
    }

    public function show_user(Campaign $campaign){

        $investorsCount = $campaign->pledges()->distinct('user_id')->count();
    
        return view('pages.user-info', [
            'campaign' => $campaign,
            'investorsCount' => $investorsCount,
        ]);

    }
}
