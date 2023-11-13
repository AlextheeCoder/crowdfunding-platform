<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Pledge;
use App\Models\Campaign;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PledgeControllerTest extends TestCase
{
   /** @test */
/** @test */
public function view_certificate_returns_pdf_for_pledge()
{
    $pledge = Pledge::factory()->create();

    $response = $this->get("/view-certificate/{$pledge->id}");

    $response->assertStatus(200); // Assuming a 200 status code for successful PDF generation
    $this->assertEquals('application/pdf', $response->headers->get('Content-Type'));
    // Optionally, you can add more assertions to validate the content of the PDF
}


}
