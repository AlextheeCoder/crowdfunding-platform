<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Campaign;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteCampaignTest extends TestCase
{
   /** @test */
   public function delete_method_removes_campaign()
   {
       $user = User::factory()->create();
       $campaign = Campaign::factory()->create(['user_id' => $user->id]);
       $this->actingAs($user);

       // Update route to match the correct case as defined in routes
       $response = $this->delete('/Campaign/' . $campaign->id); // Updated route

       $response->assertRedirect('/profile');
       $this->assertDatabaseMissing('campaigns', ['id' => $campaign->id]);
   }
}
