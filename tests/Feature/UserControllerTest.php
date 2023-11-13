<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    /** @test */
    public function store_creates_new_user_and_redirects()
    {
        // Prepare user data
        $userData = [
            'firstname' => 'John',
            'sirname' => 'Doe',
            'gender' => 'Male',
            'dob' => '1990-01-01',
            'email' => 'johsadasdndoe@example.com',
            'password' => 'secret',
        ];

        // Send a POST request to the user registration route and assert it redirects
        $response = $this->post('/users', $userData);
        $response->assertRedirect('/verify-registration-otp');

        // Assert a user was created with the given email
        $this->assertDatabaseHas('users', ['email' => 'johndoe@example.com']);
    }
}
