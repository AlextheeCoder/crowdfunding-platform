<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PledgeController;
use App\Http\Controllers\ReportController;
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
Route::get('/create', [CampaignController::class,'show'])->middleware('auth');;

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


//Show login form
Route::get('/login', [UserController::class, 'login'])->name('login');



//Authenticate
Route::post('/users/authenticate', [UserController::class, 'authenticate']);

//Pledge
Route::post('/campaign/{id}/pledge',[CampaignController::class,'pledge'])->name('campaign.pledge');

//Connect wallet
Route::post('/store/address', [UserController::class, 'storeAddress'])->name('user.storeAddress');


//Show profile page
Route::get('/profile',[UserController::class, 'profile'])->middleware('auth');


//Show campaign creator
Route::get('/user/{campaign}', [CampaignController::class,'show_user'])->middleware('auth');;


//Show messages page
Route::get('/message', [MessageController::class,'show'])->middleware('auth');

//Show admin messages
Route::get('/message/admin', [MessageController::class,'showadmin'])->middleware('auth');

//Get Contacts
Route::get('/contacts', [MessageController::class, 'getContacts'])->middleware('auth');;

//Get Messages
Route::get('/messages/{contactId}', [MessageController::class, 'getMessages'])->middleware('auth');;

//Send Messages
Route::post('/send', [MessageController::class, 'sendMessage']);

//Send message to campaign Creator
Route::post('/send-message-to-creator', [MessageController::class, 'sendMessageToCreator'])->name('sendMessageToCreator');

//Send Message to user form the administrator 
Route::post('/sendMessageToUser', [MessageController::class, 'sendMessageToUser'])->name('sendMessageToUser');


//Read receipts
Route::post('/mark-messages-as-read', [MessageController::class, 'markMessagesAsRead']);


//View Certificate
Route::get('/view-certificate/{id}', [PledgeController::class, 'viewCertificate'])->name('view.certificate');


//Admin index page
Route::get('/admin', [AdminController::class,'index'] )->middleware('checkRole:admin');



//OTP ROUTES
Route::get('/verify-registration-otp', [UserController::class, 'regOTP']);
Route::post('/verify-registration-otp', [UserController::class, 'verifyRegistrationOtp']);
Route::get('/verify-login-otp', [UserController::class, 'logOTP']);
Route::post('/verify-login-otp', [UserController::class, 'verifyLoginOtp']);
//Resend OTP
Route::get('/resend-otp', [UserController::class, 'resendOtp'])->name('resend-otp'); 
Route::get('/resend-registration-otp', [UserController::class, 'resendRegOtp'])->name('resendRegOtp');
//Admin Login
Route::get('/admin/login' , [AdminController::class, 'login']);

//Admin OTP
Route::post('/admin/authenticate', [AdminController::class, 'authenticate']);
Route::get('/admin/verify-login-otp', [AdminController::class, 'verify']);
Route::post('/admin/verify-login-otp', [AdminController::class, 'verifyLoginOtp']);


//Admin Log Out
Route::post('/admin/logout', [AdminController::class, 'logout']);

//Routes for forgot password
Route::get('/password/reset', [UserController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/password/email', [UserController::class, 'handleForgotPassword'])->name('password.email');
Route::get('/password/reset/{token}', [UserController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('/password/reset', [UserController::class, 'handleResetPassword'])->name('password.update');
Route::get('/email-sent', [UserController::class, 'showemailwassent'])->name('email.sent');


//Admin Routes for pages
Route::get('/users/manage' , [AdminController::class, 'usermanagement']);
Route::get('/users/manage/user' , [AdminController::class, 'userdetails']);
Route::get('/users/manage/user/{id}', [AdminController::class, 'userdetails'])->name('user.manage');
Route::post('/campaign/{campaign}/comments', [CampaignController::class, 'storeComment'])->name('comments.store');
Route::delete('/user/{user}', [AdminController::class, 'delete'])->name('user.delete');

//Reporting
Route::post('/report/store', [ReportController::class, 'store'])->name('storeReport');
Route::get('/reports', [AdminController::class, 'viewReports']);