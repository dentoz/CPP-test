<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MessageSent implements ShouldBroadcast
{
    use InteractsWithSockets;

    public $message;
    public $user;
    public $chatRoomId;

    public function __construct($message, $user, $chatRoomId)
    {
        $this->message = $message;
        $this->user = $user;
        $this->chatRoomId = $chatRoomId;
    }

    public function broadcastOn()
    {
        return new PrivateChannel("chat.room.{$this->chatRoomId}");
    }

    public function broadcastWith()
    {
        return [
            'message' => $this->message,
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
            ]
        ];
    }
}
