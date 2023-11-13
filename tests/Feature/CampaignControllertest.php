<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Campaign;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CampaignControllertest extends TestCase
{
    /**
     * A basic feature test example.
     */
   /** @test */
public function show_method_displays_create_page_for_authenticated_user_with_ethereum_address()
{
    // Create a user with an Ethereum address
    $user = User::factory()->create(['ethereum_address' => '0x434857da4a20593ac6a018588376fab69f3ecf1e']);

    // Act as this user
    $this->actingAs($user);

    // Call the show method
    $response = $this->get('/campaign/show');

    // Assert the response status and view
    $response->assertStatus(200);
    $response->assertViewIs('pages.create');
}



/** @test */
public function create_campaign_method_stores_new_campaign()
{
    $user = User::factory()->create(['ethereum_address' => '0x434857da4a20593ac6a018588376fab69f3ecf1e']);
    $this->actingAs($user);

    $campaignData = Campaign::factory()->make()->toArray();

    $response = $this->post('/campaign/create', $campaignData);

    $response->assertRedirect('/');
    $this->assertDatabaseHas('campaigns', ['title' => $campaignData['title']]);
}

/** @test */
public function delete_method_removes_campaign()
{
    $user = User::factory()->create();
    $campaign = Campaign::factory()->create(['user_id' => $user->id]);
    $this->actingAs($user);

    $response = $this->delete('/campaign/'.$campaign->id);

    $response->assertRedirect('/profile');
    $this->assertDatabaseMissing('campaigns', ['id' => $campaign->id]);
}

/** @test */
public function user_can_pledge_to_a_campaign()
{
    // Create a user and a campaign
    $user = User::factory()->create();
    $campaign = Campaign::factory()->create();

    // Act as this user
    $this->actingAs($user);

    // Define the pledge amount
    $pledgeAmount = 100;

    // Call the pledge method
    $response = $this->post('/campaign/pledge/' . $campaign->id, ['pledge' => $pledgeAmount]);

    // Assert the response is successful
    $response->assertStatus(200);
    $response->assertJson([
        'message' => 'Pledge made successfully.',
        'initial_target' => $campaign->target,
        'amount_raised' => $pledgeAmount,
    ]);

    // Assert the pledge is recorded in the database
    $this->assertDatabaseHas('pledges', [
        'campaign_id' => $campaign->id,
        'user_id' => $user->id,
        'amount' => $pledgeAmount,
    ]);
}

}
