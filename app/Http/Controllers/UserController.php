<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Mail\SendOtpMail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Mail\SendResetLinkEmail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    //
    public function login(){
        return view("auth.login");
    }
    
    public function register(){
        return view("auth.register");
    }

    public function regOTP(){
        return view("auth.verify-2fa");
    }
    public function logOTP(){
        return view("auth.logOTP");

    }
    public function showForgotPasswordForm(){
        return view('auth.forgot_password');
    }

    public function showResetPasswordForm($token){
        return view('auth.reset_password', ['token' => $token]);
    }

    public function showemailwassent(){
       return view("auth.emeilsent");
    }

    //Log Out
    public function logout(Request $request)
    {
        // Check if the user is authenticated
        if (auth()->check()) {
            // Clear the Ethereum address from the user's database record
            $user = $request->user();
            $user->ethereum_address = null;
            $user->save();
        }
    
        // Perform the regular logout actions
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    
        return redirect('/')->with('message', 'You have been logged out and wallet disconnected!');
    }

    //Register Users
    public function store(Request $request) {
        $formFields = $request->validate([
            'firstname' => ['required', 'min:3'],
            'sirname' => ['required', 'min:3'],
            'gender' => ['required', 'min:3'],
            'dob' => ['required', 'min:3'],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => ['required', 'min:6']
        ]);
    
        //get profile image
        if ($request->hasFile('profile')) {
            $formFields['profile'] = $request->file('profile')->store('profiles', 'public');
        }
    
        // Hash Password
        $formFields['password'] = bcrypt($formFields['password']);
    
        // Generate OTP
        $otp = rand(100000, 999999);
    
        // Store user details and OTP in session for OTP verification
        session([
            'user_details' => $formFields, 
            'otp_code' => $otp, 
            'email' => $formFields['email'], 
            'otp_created_at' => now()
        ]);
    
        // Send OTP to user's email
        Mail::to($formFields['email'])->send(new SendOtpMail($otp));
    
        // Redirect to OTP verification page
        return redirect('/verify-registration-otp');
    }

    public function verifyRegistrationOtp(Request $request)
{
    $request->validate([
        'otp' => 'required|numeric',
    ]);

    $email = session('email');
    $otp_code = session('otp_code');
    $userDetails = session('user_details');
    $otp_created_at = session('otp_created_at');

    if ($userDetails && $request->otp == $otp_code) {
        // OTP is valid, complete the registration process
        if (Carbon::parse($otp_created_at)->addMinutes(2)->isPast()) {
            return redirect('/verify-registration-otp')->with('error', 'OTP has expired. Please resend.');
        }

        else{

             // Create User
        $user = User::create($userDetails);

        // Clear the OTP code
        $user->otp_code = null;
        $user->save();

        // Log the user in
        auth()->login($user);

        // Clear the user details and OTP code from the session
        $request->session()->forget(['email', 'otp_code', 'user_details']);

        return redirect('/')->with('message', 'Registration successful!');
        }
       
    } else {
        // OTP is invalid, redirect back with an error message
        return redirect('/verify-registration-otp')->with('error', 'Invalid OTP.');
    }
}

    
    


    
//Authenticate ---------------------------------------------------------------------
    public function authenticate(Request $request) {
        $formFields = $request->validate([
            'email' => ['required', 'email'],
            'password' => 'required'
        ]);
    
        // Retrieve the user instance directly from the database
        $user = User::where('email', $formFields['email'])->first();
    
        // Check if the user exists and the password is correct
        if ($user && Hash::check($formFields['password'], $user->password)) {
            // Generate OTP
            $otp = rand(100000, 999999);
            $user->otp_code = $otp;
            $user->otp_created_at = now();
            $user->save();
    
            // Store email in session for OTP verification
            session(['email' => $formFields['email']]);
    
            // Send OTP to user's email
            Mail::to($user->email)->send(new SendOtpMail($otp));
    
            // Redirect to OTP verification page
            return redirect('/verify-login-otp');
        } else {
            return redirect('/login')->with('error', 'Wrong credentials!!')->with('showResetLink', true);
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

            //Check if 2 mins
            if (Carbon::parse($user->otp_created_at)->addMinutes(2)->isPast()) {
                return redirect('/verify-login-otp')->with('error', 'OTP has expired. Please resend.');
            }
            else{ 
                $user->otp_code = null; // Clear the OTP code
                $user->save();
        
                // Log the user in
                auth()->login($user);
        
                // Clear the email from the session
                $request->session()->forget('email');
        
                return redirect('/')->with('message', 'You are now logged in!');}
           
        } else {
            // OTP is invalid, redirect back with an error message
            return redirect('/verify-login-otp')->with('error', 'Invalid OTP.');
        }
    }
    
    //Resend OTP
    public function resendOtp()
{
    $email = session('email');
    $user = User::where('email', $email)->first();

    if ($user) {
        // Generate a new OTP and store the generation time
        $otp = rand(100000, 999999);
        $user->otp_code = $otp;
        $user->otp_created_at = now();
        $user->save();

        // Send the new OTP to the user's email
        Mail::to($user->email)->send(new SendOtpMail($otp));

        return redirect('/verify-login-otp')->with('message', 'A new OTP has been sent to your email.');
    } else {
        return redirect('/verify-login-otp')->with('error', 'Error resending OTP. Please try again.');
    }
}


//Resend Reg Otp
public function resendRegOtp()
{
    $email = session('email');
    $userDetails = session('user_details');

    if ($userDetails) {
        // Generate a new OTP and store the generation time
        $otp = rand(100000, 999999);

        // Update the OTP and OTP creation time in the session
        session(['otp_code' => $otp, 'otp_created_at' => now()]);

        // Send the new OTP to the user's email
        Mail::to($email)->send(new SendOtpMail($otp));

        return redirect('/verify-registration-otp')->with('message', 'A new OTP has been sent to your email.');
    } else {
        return redirect('/verify-registration-otp')->with('error', 'Error resending OTP. Please try again.');
    }
}

/////////////Forgot Password //////////////////////////////////////////////////

public function handleForgotPassword(Request $request)
{
    $request->validate(['email' => 'required|email|exists:users,email']);

    $user = User::where('email', $request->email)->first();
    if($user){
        $token = Str::random(60);
        $user->reset_token = Hash::make($token);
        $user->reset_token_expiry = now()->addMinutes(30);
        $user->save();
    
        // Store email in session
        session(['reset_email' => $request->email]);

        // Send reset link to user's email
        Mail::to($user->email)->send(new SendResetLinkEmail($token));
    }

    return redirect()->route('email.sent')->with('message', 'Reset link sent to your email.');
}


public function handleResetPassword(Request $request)
{
    $request->validate([
        'token' => 'required',
        'email' => 'required|email|exists:users,email',
        'password' => ['required', 'min:6']
    ]);

    $user = User::where('email', $request->email)->first();

    if ($user && Hash::check($request->token, $user->reset_token) && now()->lessThan($user->reset_token_expiry)) {
        $user->password = bcrypt($request->password);
        $user->reset_token = null;
        $user->reset_token_expiry = null;
        $user->save();

        return redirect('/login')->with('message', 'Password reset successful.');
    } else {
        return redirect()->route('password.request')->with('error', 'Invalid or expired reset link.');
    }
}

    


    public function storeAddress(Request $request)
    {
       
        // Retrieve the Ethereum address from the request
        $ethereumAddress = $request->input('ethereum_address');
    
        // Store the Ethereum address in the user's database record
        // You can adapt this logic to match your database structure
        $user = $request->user();
        $user->ethereum_address = $ethereumAddress;
        $user->save();
    
        // Return a response as needed
       
    }
    

    public function profile()
    {
        $user = auth()->user(); 
    
        return view('pages.profile', [
            'user' => $user,
        ]);
    }
    public function update(Request $request, $id)
    {
        // Validate the form data
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
        ]);
    
        // Find the user by $id
        $user = User::find($id);
        if (!$user) {
            // Handle the case where the user with the given $id is not found
            return redirect('/profile')->with('error', 'User not found.');
        }
    
        // Update the user data
        $user->name = $request->name;
        $user->email = $request->email;
    
        // Check if a new image file was uploaded
        if ($request->hasFile('profile')) {
            $user->profile = $request->file('profile')->store('profiles', 'public');
        }
    
        // Save the changes to the database
        $user->save();
    
        return redirect('/profile')->with('message', 'User updated successfully.');
    }
    

}
