<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class viewCampaignTest extends TestCase
{
    /** @test */
    public function show_method_displays_create_page_for_authenticated_user_with_ethereum_address()
    {
        // Create a user with an Ethereum address
        $user = User::factory()->create(['ethereum_address' => '0x434857da4a20593ac6a018588376fab69f3ecf1e']);

        // Act as this user
        $this->actingAs($user);

        // Call the show method
        $response = $this->get('/create'); // Updated route

        // Assert the response status and view
        $response->assertStatus(200);
        $response->assertViewIs('pages.create');
    }
}
