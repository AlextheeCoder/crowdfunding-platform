<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Campaign;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreatCampaignTest extends TestCase
{
    /**
     * A basic feature test example.
     */

     /**
 * @test
 */
public function create_campaign_method_stores_new_campaign(){
    $user = User::factory()->create(['ethereum_address' => '0x434857da4a20593ac6a018588376fab69f3ecf1e']);
    $this->actingAs($user);

    // Add the 'date' field to your campaign data
    $campaignData = Campaign::factory()->make([
        'date' => '2023-01-01' // Example date, adjust as needed
    ])->toArray();

    $response = $this->post('/campaign/create', $campaignData);

    $response->assertRedirect('/');
    $this->assertDatabaseHas('campaigns', ['title' => $campaignData['title']]);
}

}
