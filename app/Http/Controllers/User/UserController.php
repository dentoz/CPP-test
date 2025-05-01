<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \App\Models\User;
use \App\Models\ChatRoom;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function availability(\App\Http\Requests\UserAvailabilityRequest $request)
    {
        $validated = $request->validated();
        $isAvailable = User::where('nick_name', $validated['nickname'])->exists();

        DB::beginTransaction();
        try {
            if (!$isAvailable) {
            User::where('google_id', $validated['google_id'])->update([
                'nick_name' => $validated['nickname'],
                'fcm_token' => $validated['fcmToken']
            ]);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
            'status' => 500,
            'message' => 'An error occurred while processing your request.',
            'error' => $e->getMessage(),
            ], 500);
        }
        

        return response()->json([
            'status' => 200,
            'message' => 'ok',
            'error' => null,
            'data' => [
                'nickname' => $validated['nickname'],
                'available' => $isAvailable,
            ],
        ], 200);
    }

    public function dashboard(Request $request)
    {
        $totalUser = User::count();
        $totalActiveUser = User::where('active', 1)->count();
        $activeCHatRooms = ChatRoom::where('is_active', true)->count();
        return response()->json([
            'status' => 200,
            'message' => 'ok',
            'error' => null,
            'data' => [
                'total_user' => $totalUser,
                'total_active_user' => $totalActiveUser,
                'active_chat_rooms' => $activeCHatRooms,
            ],
        ], 200);
    }
}
