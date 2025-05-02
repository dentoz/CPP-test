<?php

use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Broadcast\BroadcastAuthController;
use App\Http\Controllers\chat\ChatRoomController;
use App\Http\Controllers\Chat\MessageController;
use App\Http\Controllers\Chat\InvitationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/user/availability', [UserController::class, 'availability'])->name('user.availability');
    Route::get('/user/dashboard', [UserController::class, 'dashboard'])->name('user.dashboard');
    Route::post('/chat-rooms', [ChatRoomController::class, 'store']);
    Route::post('/chat-rooms/{chatRoom}/invite', [InvitationController::class, 'invite']);
    Route::post('/invitations/{invitation}/respond', [InvitationController::class, 'respond']);
    Route::get('/chat-rooms/{chatRoom}/messages', [MessageController::class, 'index']);
    Route::post('/chat-rooms/{chatRoom}/messages', [MessageController::class, 'store']);
    Route::post('/chat-rooms/new', [MessageController::class, 'newChat']);
    Route::post('/broadcasting/auth', [BroadcastAuthController::class, 'authenticate']);
    Route::get('/logout', [GoogleAuthController::class, 'logout']);
});
    