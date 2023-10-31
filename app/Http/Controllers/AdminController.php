<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Issue;
use App\Models\Pledge;
use App\Models\Report;
use App\Models\Campaign;
use App\Mail\SendOtpMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    //
    //view admin Login
    public function login()
    {
        if(!auth()->check()) {
           
            return view("admin.auth.login");
        }
        else {
            return redirect('/')->with('message', 'You are already logged in. Please log out first before accessing the login page.');
        }
    
      
    }
    
    public function verify() {
        return view("admin.auth.logOTP");
    }


//view user management page

public function usermanagement(){
    $allusers = User::all();
    return view("admin.pages.user-management")->with(
        [
            'users' => $allusers,
        ]
    );
}

public function campaignmanagement(){
    $allcampaigns =Campaign::all();
    return view("admin.pages.campaign-management")->with(
        [
            'campaigns' => $allcampaigns,
        ]
        );
}


public function userdetails($id) {
    $user = User::find($id);

    if (!$user) {
        return redirect()->back()->with('error', 'User not found');
    }

    return view("admin.pages.user-details", compact('user'));
}


public function campaigndetails($id) {
    $campaign = Campaign::find($id);
    if (!$campaign) {
        return redirect()->back()->with('error', 'Campaign does not exist');
    }

    $PledgeCount = $campaign->pledges->pluck('user_id')->count();
    $campaign->PledgeCount = $PledgeCount;

    $totalPledged = $campaign->pledges->sum('amount');
    $campaign->totalPledged = $totalPledged;

    return view("admin.pages.campaign-details", compact('campaign'));
}


public function viewReports() {
    $reports = Report::with('reporter', 'reportedUser')->get();
    $issues = Issue::with('user')->get();
    return view('admin.pages.reports', compact('reports', 'issues'));
}

public function viewReport($reportId){
    $report = Report::with('reporter', 'reportedUser')->findOrFail($reportId);

    // Return the detailed report view
    return view('admin.pages.report-details', compact('report'));
}

public function viewissue($id) {
    $issue = Issue::with('user')->find($id);
    if (!$issue) {
        return redirect()->route('admin.issues.index')->with('error', 'Issue not found.');
    }
    return view('admin.pages.issue-detail', compact('issue'));
}




    public function index() {
        $campaignCount = Campaign::count();
        $userCount = User::count();
        $pledgeCount = Pledge::count();
        $dailyPledgeCount = Pledge::whereDate('created_at', '=', Carbon::today())->count();
    
        // Data for the total pledge amounts chart
        $dates = Pledge::select(DB::raw('DATE(created_at) as date'))
                       ->groupBy(DB::raw('DATE(created_at)'))
                       ->get()
                       ->pluck('date');
        
        $totalPledgesPerDay = Pledge::select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(amount) as total'))
                                   ->groupBy(DB::raw('DATE(created_at)'))
                                   ->get()
                                   ->pluck('total');
    
        // Data for daily transactions (number of pledges per day)
        $dailyTransactionCounts = Pledge::select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(id) as count'))
                                        ->groupBy(DB::raw('DATE(created_at)'))
                                        ->get()
                                        ->pluck('count');
        
 
        // Fetch the latest 8 users
        $latestUsers = User::orderBy('created_at', 'desc')->take(8)->get();
        //Users joint today
        // Count users who have joined today
        $todayUsersCount = User::whereDate('created_at', Carbon::today())->count();
        $todayCamapignsCount = Campaign::whereDate('created_at', Carbon::today())->count();
        $todayPledgeCount = Pledge::whereDate('created_at', Carbon::today())->count();

        //user increase/decrease:
        $totalUsersUntilYesterday = User::whereDate('created_at', '<', Carbon::today())->count();
        $totalCamapignsUntilYesterday = Campaign::whereDate('created_at', '<', Carbon::today())->count();
        $totalPledgesUntilYesterday = Pledge::whereDate('created_at', '<', Carbon::today())->count();
    
        $percentageIncrease = 0;
        if ($totalUsersUntilYesterday > 0) { // Avoid division by zero
            $percentageIncrease = round(($todayUsersCount / $totalUsersUntilYesterday) * 100);

        }
        $CampaignpercentageIncrease = 0;
        if ($totalCamapignsUntilYesterday > 0) { // Avoid division by zero
            $CampaignpercentageIncrease = round(($todayCamapignsCount / $totalCamapignsUntilYesterday) * 100);

        }
        $PledgepercentageIncrease = 0;
        if ($totalPledgesUntilYesterday > 0) { // Avoid division by zero
            $PledgepercentageIncrease = round(($todayPledgeCount / $totalPledgesUntilYesterday) * 100);

        }
        


    
        return view('admin.index', [
            'campaignCount' => $campaignCount,
            'userCount' => $userCount,
            'pledgeCount' => $pledgeCount,
            'dailyPledgeCount' => $dailyPledgeCount,
            'dates' => $dates,
            'totalPledgesPerDay' => $totalPledgesPerDay,
            'dailyTransactionCounts' => $dailyTransactionCounts, 
            'latestUsers' => $latestUsers,
            'todayUsersCount' => $todayUsersCount,
            'userpercent' => $percentageIncrease,
            'campaignpercent' => $CampaignpercentageIncrease,
            'todaycampaigncount' => $todayCamapignsCount,
            'pledgepercent' => $PledgepercentageIncrease,
            'todaypledgecount' =>$todayPledgeCount,
        ]);
    }
    
    

    public function authenticate(Request $request)
    {
        $formFields = $request->validate([
            'email' => ['required', 'email'],
            'password' => 'required'
        ]);
    
        if(auth()->validate($formFields)) {
            $user = User::where('email', $formFields['email'])->first();
    
            if($user->role !== 'admin') {
                return redirect('/admin/login')->with('message', 'Not an admin user.');
            }
    
            // Generate OTP
            $otp = rand(100000, 999999);
            $user->otp_code = $otp;
            $user->save();
    
            // Store the email in the session
            session(['email' => $formFields['email']]);
    
            // Send OTP to admin's email
            Mail::to($user->email)->send(new SendOtpMail($otp));
    
            // Redirect to OTP verification page
            return redirect('/admin/verify-login-otp');
        } else {
            return redirect('/admin/login')->with('message', 'Wrong credentials!!');
        }
    }
    

    public function verifyLoginOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric',
        ]);
    
        $email = session('email');
        $user = User::where('email', $email)->first();
    
        if ($user && $request->otp == $user->otp_code) {
            // OTP is valid, complete the login process
            $user->otp_code = null; // Clear the OTP code
            $user->save();
    
            // Log the user in
            auth()->login($user);
    
            // Clear the email from the session
            $request->session()->forget('email');
    
            return redirect('/admin')->with('message', 'You are now logged in!');
        } else {
            // OTP is invalid, redirect back with an error message
            return redirect('/verify-login-otp')->with('message', 'Invalid OTP.');
        }
    }
    

    function logout(Request $request){
        // Perform the regular logout actions
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    
        return redirect('/')->with('message', 'You have been logged out!');
    }





//Delete user
public function suspend($id) {
    $user = User::find($id);
    if ($user) {
        $user->suspended = !$user->suspended; // toggle the state
        $user->save();

        // Check if the authenticated user is the same as the user being suspended
        if(Auth::id() === $user->id) {
            Auth::logout();
            return redirect('/login')->with('message', 'Your account has been suspended.');
        }

        return redirect()->back()->with('message', 'User suspension status updated!');
    }
    return redirect()->back()->with('error', 'User not found.');
}



//Suspend Campaign
public function suspendCamapign($id) {
    $camapign = Campaign::find($id);
    if ($camapign) {
        $camapign->suspended = !$camapign->suspended; // toggle the state
        $camapign->save();

        // Check if the authenticated user is the same as the user being suspended
       

        return redirect()->back()->with('message', 'camapign suspension status updated!');
    }
    return redirect()->back()->with('error', 'camapign not found.');
}






}
