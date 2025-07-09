<?php

use Illuminate\Support\Facades\Broadcast;

// Channel for tutors
Broadcast::channel('chat.tutor.{id}', function ($user, $id) {
    // Allow if the user is a tutor and their ID matches
    return $user instanceof \App\Models\Tutor && $user->id == $id;
});

// Channel for students
Broadcast::channel('chat.student.{id}', function ($user, $id) {
    \Log::info('Student channel auth', [
        'user' => $user,
        'id' => $id,
        'user_class' => is_object($user) ? get_class($user) : null,
        'user_id' => is_object($user) ? $user->id : null,
    ]);
    // Allow if the user is a student and their ID matches
    return $user instanceof \App\Models\Student && $user->id == $id;
}); 