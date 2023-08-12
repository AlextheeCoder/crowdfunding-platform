<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Contact;
use App\Models\Message;
use App\Models\Campaign;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MessageController extends Controller
{
    //
    public function show()
    {
        // Get the currently authenticated user
        $user = auth()->user();
    
        // Fetch contacts of the user (users who sent messages to the user or received messages from the user)
        $contacts = User::whereIn('id', function ($query) use ($user) {
            $query->select('sender_id')
                ->from('messages')
                ->where('receiver_id', $user->id)
                ->groupBy('sender_id');
        })->orWhereIn('id', function ($query) use ($user) {
            $query->select('receiver_id')
                ->from('messages')
                ->where('sender_id', $user->id)
                ->groupBy('receiver_id');
        })->get();
    
        // Fetch the latest message for each contact
        $contacts->each(function ($contact) use ($user) {
            $latestMessage = Message::where(function ($query) use ($user, $contact) {
                $query->where('sender_id', $user->id)
                      ->where('receiver_id', $contact->id);
            })->orWhere(function ($query) use ($user, $contact) {
                $query->where('sender_id', $contact->id)
                      ->where('receiver_id', $user->id);
            })->latest()->first();
    
            // Add the latest message timestamp to the contact
            $contact->latestMessageTimestamp = optional($latestMessage)->created_at;
        });
    
        // Sort the contacts based on the latest message timestamp in descending order
        $contacts = $contacts->sortByDesc('latestMessageTimestamp');
    
        return view('pages.messages', [
            'contacts' => $contacts,
        ]);
    }
    
    

    public function getContacts()
    {
        // Get the currently authenticated user
        $user = auth()->user();
    
        // Fetch contacts of the user (other users who sent messages to the user or received messages from the user)
        $contacts = $user->contacts;
    
        return response()->json($contacts);
    }
    

    public function getMessages($contactId)
    {
        // Get the currently authenticated user
        $user = auth()->user();
    
        // Fetch messages exchanged between the authenticated user and the specified contact
        $messages = Message::where(function ($query) use ($user, $contactId) {
            $query->whereIn('sender_id', [$user->id, $contactId])
                  ->whereIn('receiver_id', [$user->id, $contactId]);
        })->get();
    
        return response()->json($messages);
    }
    
    

    public function sendMessage(Request $request)
    {
        // Validate the request data here if needed
    
        // Create a new message
        $message = new Message();
        $message->sender_id = auth()->user()->id;
        $message->receiver_id = $request->input('receiver_id');
        $message->content = $request->input('content'); // Update 'content' to 'message'
        $message->save();
    
        return response()->json(['success' => true]);
    }
    


    public function sendMessageToCreator(Request $request)
{
    // Validate the form data
    $request->validate([
        'message' => 'required|string',
        // Add any other validation rules if needed
    ]);

    // Get the currently authenticated user (sender)
    $sender = auth()->user();

    // Get the campaign creator (receiver)
    $campaignId = $request->input('campaign_id');
    $campaign = Campaign::findOrFail($campaignId);
    $receiver = $campaign->user;

    // Create a new message
    $message = new Message();
    $message->sender_id = $sender->id;
    $message->receiver_id = $receiver->id;
    $message->content = $request->input('message');
    $message->save();

    // Optionally, you can add the sender and receiver as contacts in the contacts table
    // if they are not already connected. You should have a check here to avoid duplication.
    $contact1 = new Contact();
    $contact1->user_id = $sender->id;
    $contact1->contact_id = $receiver->id;
    $contact1->save();

    $contact2 = new Contact();
    $contact2->user_id = $receiver->id;
    $contact2->contact_id = $sender->id;
    $contact2->save();

    // Optionally, you can send a response back to the form submitter
    return response()->json(['success' => true]);
}

public function markMessagesAsRead(Request $request)
{
    $user = auth()->user();
    $contactId = $request->input('contact_id');

    // Mark messages as read in the database
    Message::where('sender_id', $contactId)
        ->where('receiver_id', $user->id)
        ->update(['read' => true]);

    return response()->json(['success' => true]);
}

public function getUnreadMessageCount()
{
    // Get the currently authenticated user
    $user = auth()->user();

    // Count the number of unread messages for the user
    $unreadCount = Message::where('receiver_id', $user->id)
        ->where('read', false) // Assuming you added the 'read' column to the messages table
        ->count();

    return response()->json(['unread_count' => $unreadCount]);
}
    
}
