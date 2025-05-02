<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use App\Models\ChatRoom;
use App\Models\ChatRoomUser;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MessageController extends Controller
{
    public function index(Request $request, ChatRoom $chatRoom)
    {
        $isMember = ChatRoomUser::where('chat_room_id', $chatRoom->id)
            ->where('user_id', $request->user()->id)->exists();

        if (!$isMember) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $messages = Message::with('user:id,name')
            ->where('chat_room_id', $chatRoom->id)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'status' => 200,
            'message' => 'ok',
            'error' => null,
            'data' => $messages
        ], 200);
    }

    public function store(Request $request, ChatRoom $chatRoom)
    {
        $request->validate(['message' => 'required|string']);
    
        $isMember = ChatRoomUser::where('chat_room_id', $chatRoom->id)
            ->where('user_id', $request->user()->id)->exists();
    
        if (!$isMember) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
    
        $message = Message::create([
            'chat_room_id' => $chatRoom->id,
            'user_id' => $request->user()->id,
            'message' => $request->message
        ]);

        Http::post('http://localhost:3000/send-message', [
            'chat_room_id' => $chatRoom->id,
            'user' => $request->user()->email,
            'id' => $message->id,
            'message' => $request->message
        ]);

        return response()->json([
            'status' => 200,
            'message' => 'ok',
            'error' => null,
            'data' => $message
        ], 200);
    }
}