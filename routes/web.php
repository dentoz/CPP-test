<?php

use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

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
    return Socialite::driver('google')->redirect();
});

// Google callback
Route::get('/auth/google/callback', function () {
    $googleUser = Socialite::driver('google')->stateless()->user();

    // Find or create user
    $user = User::firstOrCreate(
        ['email' => $googleUser->getEmail()],
        [
            'name' => $googleUser->getName(),
            'password' => bcrypt('password'),
            'role' => 'user',
            'google_id' => $googleUser->id,
            'avatar' => $googleUser->avatar,
        ]
    );

    Auth::login($user);

    return redirect('/dashboard'); // or return a token if you use API
});

Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '.*');
