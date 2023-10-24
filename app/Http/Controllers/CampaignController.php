<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Pledge;
use App\Models\Comment;
use App\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        // Get the search query, category, and sort option from the request
        $searchQuery = $request->input('search');
        $category = $request->input('category');
        $sortBy = $request->input('sort_by');
    
        // Fetch campaigns with pledges using eager loading
        $query = Campaign::with('pledges');
    
        // Apply the category filter if the category is present and not "all"
        if ($category && $category != 'all') {
            $query->where('category', $category);
        }
    
        // Apply the search filter if the search query is present
        if ($searchQuery) {
            $query->where('title', 'like', '%' . $searchQuery . '%')
                ->orWhere('description', 'like', '%' . $searchQuery . '%')
                ->orWhere('category', 'like', '%' . $searchQuery . '%');
        }
    
        // Apply sorting based on the selected sort option
        // Apply sorting based on the selected sort option
    switch ($sortBy) {
        case 'latest':
            $query->orderBy('created_at', 'desc');
            break;
        case 'popular':
            // Sort by the number of unique investors
            $query->withCount(['pledges as unique_investors_count' => function ($query) {
                $query->select(DB::raw('count(DISTINCT user_id)'));
            }])->orderBy('unique_investors_count', 'desc');            
            break;
        case 'price_low_to_high':
            $query->orderBy('target', 'asc');
            break;
        case 'price_high_to_low':
            $query->orderBy('target', 'desc');
            break;
        // Add other cases if needed
        default:
            // Default sorting logic if needed
            break;
    }

    
        // Get the filtered campaigns
        $campaigns = $query->paginate(4);
        $allCampaigns = Campaign::all();
    
        // Calculate the number of unique investors and total amount pledged for each campaign
        foreach ($campaigns->items() as $campaign) {
            $uniqueInvestorsCount = $campaign->pledges->pluck('user_id')->unique()->count();
            $campaign->uniqueInvestorsCount = $uniqueInvestorsCount;
        
            $totalPledged = $campaign->pledges->sum('amount');
            $campaign->totalPledged = $totalPledged;
        }
    
        return view("pages.discover")->with([
            'campaigns' => $campaigns,
            'allCampaigns' => $allCampaigns,
        ]);
    }
    

    public function single(Campaign $campaign)
    {
        // Load comments for the campaign
        $comments = $campaign->comments()->whereNull('parent_id')->with('user')->latest()->get();
 // "with('user')" assumes each comment is related to a user and you want to load that user's data alongside each comment. Remove it if not needed. "latest()" will order comments with the most recent ones first.
    
        // Count of distinct investors for the campaign
        $investorsCount = $campaign->pledges()->distinct('user_id')->count();
    
        // Calculate the amount raised so far for the campaign
        $amountRaised = $campaign->pledges()->sum('amount');
        //
        $hasBacked = $campaign->pledges->contains('user_id', auth()->id());
        $isCreator = $campaign->user_id == auth()->id();

        return view('pages.single-campaign', [
            'campaign' => $campaign,
            'investorsCount' => $investorsCount,
            'amountRaised' => $amountRaised,
            'hasBacked' => $hasBacked,
            'isCreator' => $isCreator,
            'comments' => $comments,  // Pass the comments to the view
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
            'offering_type' => 'required|string',
            'asset_type' => 'nullable|string',
            'price_per_share' => 'nullable|numeric',
            'valuation' => 'nullable|numeric',
            'min_investment' => 'nullable|numeric',
            
            
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
        
        // No need to update the target value in the database as it remains unchanged
        
        // Create an instance of the PledgeController and call its process method
        $pledgeController = new PledgeController();
        $pledgeController->process($id, $request, $campaign->ethereum_address);
        
        // Calculate the amount raised so far
        $amountRaised = Pledge::where('campaign_id', $id)->sum('amount');
        
        // Return a response indicating the successful update
        return response()->json([
            'message' => 'Pledge made successfully.',
            'initial_target' => $campaign->target,
            'amount_raised' => $amountRaised,
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


    public function storeComment(Request $request, Campaign $campaign)
    {
        $request->validate([
            'body' => 'required|max:1000', 
            'parent_id' => 'nullable|exists:comments,id' // This ensures the provided parent_id exists in the comments table, or it's null
        ]);
    
        // Create a new comment instance
        $comment = new Comment;
        $comment->body = $request->body;
        $comment->user_id = auth()->id();
        
        // Check if a parent_id (indicating a reply) was provided and set it
        if ($request->filled('parent_id')) {
           
            $comment->parent_id = $request->parent_id;
        } 
       

    
        // Save the comment to the campaign
        $comment->campaign_id = $campaign->id;  // manually associate the campaign ID
        $comment->save();

    
        return redirect()->back()->with('success', 'Comment added successfully!');
    }
    

}
