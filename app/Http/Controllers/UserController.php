<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Mail\SendOtpMail;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
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
            'name' => ['required', 'min:3'],
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
        session(['user_details' => $formFields, 'otp_code' => $otp, 'email' => $formFields['email']]);
    
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

    if ($userDetails && $request->otp == $otp_code) {
        // OTP is valid, complete the registration process

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
            $user->save();
    
            // Store email in session for OTP verification
            session(['email' => $formFields['email']]);
    
            // Send OTP to user's email
            Mail::to($user->email)->send(new SendOtpMail($otp));
    
            // Redirect to OTP verification page
            return redirect('/verify-login-otp');
        } else {
            return redirect('/login')->with('error', 'Wrong credentials!!');
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
    
            return redirect('/')->with('message', 'You are now logged in!');
        } else {
            // OTP is invalid, redirect back with an error message
            return redirect('/verify-login-otp')->with('error', 'Invalid OTP.');
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
