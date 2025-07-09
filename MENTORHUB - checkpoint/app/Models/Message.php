<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'sender_type',
        'receiver_id',
        'receiver_type',
        'message',
        'is_read'
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    // Relationship to sender (Student or Tutor)
    public function sender()
    {
        return $this->morphTo();
    }

    // Relationship to receiver (Student or Tutor)
    public function receiver()
    {
        return $this->morphTo();
    }

    // Scope to get messages between two users
    public function scopeBetweenUsers($query, $user1Id, $user1Type, $user2Id, $user2Type)
    {
        return $query->where(function ($q) use ($user1Id, $user1Type, $user2Id, $user2Type) {
            $q->where(function ($subQ) use ($user1Id, $user1Type, $user2Id, $user2Type) {
                $subQ->where('sender_id', $user1Id)
                     ->where('sender_type', $user1Type)
                     ->where('receiver_id', $user2Id)
                     ->where('receiver_type', $user2Type);
            })->orWhere(function ($subQ) use ($user1Id, $user1Type, $user2Id, $user2Type) {
                $subQ->where('sender_id', $user2Id)
                     ->where('sender_type', $user2Type)
                     ->where('receiver_id', $user1Id)
                     ->where('receiver_type', $user1Type);
            });
        });
    }

    // Scope to get unread messages for a user
    public function scopeUnreadFor($query, $userId, $userType)
    {
        return $query->where('receiver_id', $userId)
                     ->where('receiver_type', $userType)
                     ->where('is_read', false);
    }
}
