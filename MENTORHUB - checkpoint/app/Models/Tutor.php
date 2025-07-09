<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Session; // Added this import

class Tutor extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'tutor_id',
        'email',
        'password',
        'specialization',
        'phone',
        'bio',
        'profile_picture',
        'session_rate',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    // Relationships
    public function sessions()
    {
        return $this->hasMany(Session::class);
    }

    // Message relationships
    public function sentMessages()
    {
        return $this->morphMany(Message::class, 'sender');
    }

    public function receivedMessages()
    {
        return $this->morphMany(Message::class, 'receiver');
    }

    public function messages()
    {
        return $this->morphMany(Message::class, 'sender');
    }
}
