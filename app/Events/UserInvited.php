<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Support\Facades\Log;

class UserInvited implements ShouldBroadcastNow
{
    use InteractsWithSockets;

    public $chatRoomId;
    public $user;

    public function __construct($user, $chatRoomId)
    {
        $this->chatRoomId = $chatRoomId;
        $this->user = $user;
    }

    public function broadcastOn()
    {
        Log::info('Broadcasting to chat.room.aja');
        return new Channel("chat.room");
    }

    public function broadcastAs()
    {
        return 'user.invited';
    }

    public function broadcastWith()
    {   
        Log::info('Broadcasting message:', [
            'message' => "{$this->user->name} was invited!",
        ]);

        return [
            'message' => "{$this->user->name} was invited!"
        ];
    }
}
