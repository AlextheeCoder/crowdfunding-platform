<?php

namespace App\Http\Controllers;

use Dompdf\Dompdf;
use App\Models\User;
use App\Models\Pledge;
use App\Models\Campaign;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Response;

class PledgeController extends Controller
{
    //
    public function process($id, Request $request, $ethereumAddress)
    {
        // Validate the pledge amount (you can add other validation rules as needed)
        $request->validate([
            'pledge' => 'required|numeric',
        ]);
    
        // Retrieve the pledge amount from the request
        $pledgeAmount = $request->input('pledge');
    
        // Get the connected user's ID
        $user = $request->user();
        $userId = $user->id;
    
        // Create a new Pledge record in the database
        Pledge::create([
            'user_id' => $userId,
            'campaign_id' => $id,
            'amount' => $pledgeAmount,
        ]);

        // Get campaign and user details
        $campaign = Campaign::findOrFail($id);
        $investor = User::findOrFail($userId);

        // Generate the PDF content using the Blade view
        $pdfHtml = View::make('pages.view-certificate', [
            'title' => 'Your Certificate of Investment',
            'campaign' => $campaign, // Pass the $campaign variable to the view
            'campaignTitle' => $campaign->title,
            'investorName' => $investor->dob,
            'amountEth' => $pledgeAmount,
            'campaignAddress' => $campaign->ethereum_address,
            'investorAddress' => $investor->ethereum_address,
            'pledgeDate' => now()->toDateString(),
        ])->render();

        // Initialize Dompdf
        $dompdf = new Dompdf();

        // Load HTML content into Dompdf
        $dompdf->loadHtml($pdfHtml);

        $dompdf->setPaper('A4', 'portrait');

        // Render the PDF
        $dompdf->render();

        // Output the generated PDF
        return $dompdf->stream('certificate_of_investment.pdf');
    }

    public function viewCertificate($id)
    {
        // Fetch the required data to generate the PDF
        $pledge = Pledge::findOrFail($id);

        // Retrieve the campaign and investor details
        $campaign = $pledge->campaign;
        $investor = $pledge->user;

        // Generate the PDF content using the Blade view
        $pdfHtml = View::make('pages.view-certificate', [
            'title' => 'Your Certificate of Investment',
            'campaign' => $campaign, // Pass the $campaign variable to the view
            'campaignTitle' => $campaign->title,
            'investorName' => $investor->name,
            'amountEth' => $pledge->amount,
            'campaignAddress' => $campaign->ethereum_address,
            'investorAddress' => $investor->ethereum_address,
            'investorprofile' =>$investor->profile,
            'pledgeDate' => $pledge->created_at->toDateString(),
        ])->render();

        // Initialize Dompdf
        $dompdf = new Dompdf();

        // Load HTML content into Dompdf
        $dompdf->loadHtml($pdfHtml);

       
        $dompdf->setPaper('A4', 'portrait');

        // Render the PDF
        $dompdf->render();

        // Output the generated PDF
        return Response::make($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="certificate_of_investment.pdf"',
        ]);
    }
    
}
