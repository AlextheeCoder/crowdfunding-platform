<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Pledge;
use App\Models\Campaign;
use App\Mail\SendOtpMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

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
    
        return view('admin.index', [
            'campaignCount' => $campaignCount,
            'userCount' => $userCount,
            'pledgeCount' => $pledgeCount,
            'dailyPledgeCount' => $dailyPledgeCount,
            'dates' => $dates,
            'totalPledgesPerDay' => $totalPledgesPerDay,
            'dailyTransactionCounts' => $dailyTransactionCounts,  // Pass the daily transaction counts to the view
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
}
