<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PledgeController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\FeaturedController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//Getting to the index Page
Route::get('/',[FeaturedController::class, 'index']);

//Getting  unread messages
Route::get('/unread-messages-count', [MessageController::class, 'getUnreadMessageCount']);

//Showing Create canpaign page
Route::get('/create', [CampaignController::class,'show']);

//Creating Campaign
Route::post('/campaign/create', [CampaignController::class, 'createCampaign'])->name('campaign.create');

//Showing Discover Page
Route::get('/discover', [CampaignController::class,'discover']);

//Showing Single campaign page
Route::get('/discover/{campaign}', [CampaignController::class, 'single']);

//Storing users in databse
Route::post('/users', [UserController::class, 'store']);

//Logging users out
Route::post('/logout', [UserController::class, 'logout']);

//Showing the register form
Route::get('/register', [UserController::class, 'register']);

// Edit user info
Route::put('/users/{user}', [UserController::class, 'update'])->name('user.update');


// Show Edit Form gor campaigns
Route::get('/campaign/{id}/edit', [CampaignController::class, 'edit'])->name('campaign.edit');

// Update Campaign
Route::put('/campaigns/{campaign}', [CampaignController::class, 'update'])->name('campaign.update');



// Delete Listing
Route::delete('/Campaign/{campaign}', [CampaignController::class, 'delete'])->name('campaign.delete');


//LShow login form
Route::get('/login', [UserController::class, 'login']);



//Authenticate
Route::post('/users/authenticate', [UserController::class, 'authenticate']);

//Pledge
Route::post('/campaign/{id}/pledge',[CampaignController::class,'pledge'])->name('campaign.pledge');

//Connect wallet
Route::post('/store/address', [UserController::class, 'storeAddress'])->name('user.storeAddress');


//Show profile page
Route::get('/profile',[UserController::class, 'profile']);


//Show campaign creator
Route::get('/user/{campaign}', [CampaignController::class,'show_user']);


//Show messages page
Route::get('/message', [MessageController::class,'show']);

//Get Contacts
Route::get('/contacts', [MessageController::class, 'getContacts']);

//Get Messages
Route::get('/messages/{contactId}', [MessageController::class, 'getMessages']);

//Send Messages
Route::post('/send', [MessageController::class, 'sendMessage']);

//Send message to campaign Creator
Route::post('/send-message-to-creator', [MessageController::class, 'sendMessageToCreator'])->name('sendMessageToCreator');

//Read receipts
Route::post('/mark-messages-as-read', [MessageController::class, 'markMessagesAsRead']);


//View Certificate
Route::get('/view-certificate/{id}', [PledgeController::class, 'viewCertificate'])->name('view.certificate');