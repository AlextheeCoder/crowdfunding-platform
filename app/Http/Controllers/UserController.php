<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    //
    public function login(){
        return view("auth.login");
    }
    
    public function register(){
        return view("auth.register");
    }

    public function store(Request $request) {
        $formFields = $request->validate([
            'name' => ['required', 'min:3'],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => 'required'
        ]);

        //get profile image
        if ($request->hasFile('profile')) {
            $formFields['profile'] = $request->file('profile')->store('profiles', 'public');
        }

        // Hash Password
        $formFields['password'] = bcrypt($formFields['password']);

        // Create User
        $user = User::create($formFields);

        // Login
        auth()->login($user);

        return redirect('/')->with('message', 'User created and logged in');
    }

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
    

    public function authenticate(Request $request) {
        $formFields = $request->validate([
            'email' => ['required', 'email'],
            'password' => 'required'
        ]);

        if(auth()->attempt($formFields)) {
            $request->session()->regenerate();

            return redirect('/')->with('message', 'You are now logged in!');
        }
        else{
              return redirect('/login')->with('message', 'Wrong credentials!!');
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
            return redirect('/profile')->with('message', 'User not found.');
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
