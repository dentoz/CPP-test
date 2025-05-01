<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use App\Http\Requests\NewChatRoomRequest;
use App\Models\ChatRoom;
use App\Models\ChatRoomUser;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ChatRoomController extends Controller
{
    public function store(NewChatRoomRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            $user = User::where('google_id', $validated['google_id'])->first();
            $user->update([
                'nick_name' => $validated['nickname']
            ]);
            $chatRoom = ChatRoom::create([
                'name' => $validated['topic'],
                'created_by' => $user->id,
                'is_active' => 1
            ]);

            ChatRoomUser::create([
                'chat_room_id' => $chatRoom->id,
                'user_id' => $request->user()->id,
            ]);
            
            DB::commit();
            return response()->json([
                'status' => 200,
                'message' => 'ok',
                'error' => null,
                'data' => $chatRoom
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
            'status' => 500,
            'message' => 'An error occurred while processing your request.',
            'error' => $e->getMessage(),
            ], 500);
        }
    }
}

