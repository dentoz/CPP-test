<?php

namespace App\Http\Controllers\Broadcast;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Broadcast;

class BroadcastAuthController extends Controller
{
    public function authenticate(Request $request)
    {
        return response()->json([
            'auth' => 'dummy_auth_token'
        ]);
    }
}
