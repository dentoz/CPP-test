<?php

use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\Auth\GoogleAuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Redirect to Google
Route::get('/auth/redirect/google', function () {
    return Socialite::driver('google')->with(['prompt' => 'select_account'])->redirect();
});

// Google callback
Route::get('/auth/google/callback', [GoogleAuthController::class, 'handleCallback']);
Route::get('/logout', [GoogleAuthController::class, 'logout']);

Route::get('/login', function () {
    return response()->json([
        'status' => 403,
        'message' => 'credentials not provided',
        'error' => 'forbidden',
        'data' => []
    ], 403);
})->name('login');

Route::get('/{any}', function () {
    $data = session()->get('data') ?? [];
    return view('welcome', compact('data'));
})->where('any', '.*');
