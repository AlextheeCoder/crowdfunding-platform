<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class IssueController extends Controller
{
    //
    public function store(Request $request)
    {
        $request->validate([
            'issue_type' => 'required',
            'description' => 'required',
        ]);

        Issue::create([
            'user_id' => Auth::id(),
            'issue_type' => $request->input('issue_type'),
            'description' => $request->input('description'),
            'status' => 'pending', 
        ]);

        return redirect('/')->with('message', 'Issue reported successfully.');
    }
    public function suspend(Issue $issue)
{
    // Find the user associated with the issue
    $user = User::find($issue->user_id);

    // Set the user's suspended status to true
    $user->suspended = true;
    $user->save();

    // Set the issue's status to completed
    $issue->status = 'completed';
    $issue->save();

    return redirect()->back()->with('message', 'User suspended and issue marked as completed.');
}

public function reinstate(Issue $issue)
{
    // Find the user associated with the issue
    $user = User::find($issue->user_id);

    // Set the user's suspended status to false
    $user->suspended = false;
    $user->save();

    // Set the issue's status to completed
    $issue->status = 'completed';
    $issue->save();

    return redirect()->back()->with('message', 'User reinstated and issue marked as completed.');
}

}
