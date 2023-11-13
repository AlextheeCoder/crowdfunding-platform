<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Campaign;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PledgeTest extends TestCase
{
   
    use RefreshDatabase;

    /** @test */
    public function store_creates_new_user_and_redirects()
    {
        Mail::fake(); // Add this line to fake mail sending

        // Prepare user data
        $userData = [
            'firstname' => 'John',
            'sirname' => 'Doe',
            'gender' => 'Male',
            'dob' => '1990-01-01',
            'email' => 'johndoe@example.com',
            'password' => 'secret',
            // Add other required fields as per your User model's validation requirements
        ];

        // Send a POST request to the user registration route and assert it redirects
        $response = $this->post('/users', $userData);
        $response->assertRedirect('/verify-registration-otp');

        // Assert a user was created with the given email
        $this->assertDatabaseHas('users', ['email' => 'johndoe@example.com']);
    }
}
