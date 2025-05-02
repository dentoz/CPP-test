<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatRoom extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'created_by', 'is_active'];

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function chatRoomUsers()
    {
        return $this->belongsToMany(User::class, 'chat_room_users');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
