<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    //
    public function store(Request $request)
    {
        // Validate the incoming request
        $data = $request->validate([
            'reported_user_id' => 'required|integer',
            'message' => 'required|string',
        ]);
    
        // Store the report in the database
        Report::create([
            'reported_user_id' => $data['reported_user_id'],
            'reporter_id' => auth()->user()->id,  
            'message' => $data['message']
        ]);
    
        return response()->json(['success' => true]);
    }
}
