<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Google_Client;

class GoogleAuthController extends Controller
{
    public function handleCallback()
    {
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
    
        // Auth::login($user);

        $token = $user->createToken('google-login')->plainTextToken;

        $data = [
            'exists' => false,
            'token' => $token,
            'google_id' => $user->google_id,
            'name' => $user->name,
            'email' => $user->email,
            'avatar' => $user->avatar,
        ];
        if (!$user->wasRecentlyCreated) {
            $data['exists'] = true;
        }

        return redirect('/?verified=true')->with('data', $data);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/');
    }
}