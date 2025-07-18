<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\uiController\homeController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\TutorRegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\StudentProfileController;
use App\Http\Controllers\TutorSessionController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\StudentSessionController;
use App\Http\Controllers\MessageController;
use Illuminate\Support\Facades\Broadcast;

Route::get('/', [homeController::class, 'homePage'])->name('home');

Route::get('/signup', [homeController::class, 'signupPage'])->name('signup');

Route::get('/signup/tutor', function () {
    return view('tutor-signup');
})->name('tutor.signup');

Route::post('/register/student', [RegisterController::class, 'studentRegister'])->name('register.student');
Route::post('/register/tutor', [TutorRegisterController::class, 'tutorRegister'])->name('register.tutor');

Route::get('/login', [homeController::class, 'loginPage'])->name('login');
Route::get('/select-role', [homeController::class, 'selectRolePage'])->name('select-role');
Route::get('/select-role-login', function () {
    return view('select-role-login');
})->name('select-role-login');

Route::middleware('web')->group(function () {
    Route::get('/login/student', function () {
        return view('loginStudent');
    })->name('login.student');

    Route::post('/login/student', [LoginController::class, 'studentLogin'])->name('login.student.submit');

    Route::get('/login/tutor', function () {
        return view('loginTutor');
    })->name('login.tutor');

    Route::post('/login/tutor', [LoginController::class, 'tutorLogin'])->name('login.tutor.submit');
});

// Password reset routes
Route::get('/forgot-password', [App\Http\Controllers\PasswordResetController::class, 'showForgotPassword'])->name('password.request');

Route::post('/forgot-password', [App\Http\Controllers\PasswordResetController::class, 'sendResetCode'])->name('password.email');

Route::get('/verify-code', [App\Http\Controllers\PasswordResetController::class, 'showVerifyCode'])->name('password.verify');

Route::post('/verify-code', [App\Http\Controllers\PasswordResetController::class, 'verifyCode'])->name('password.verify.submit');

Route::get('/reset-password', [App\Http\Controllers\PasswordResetController::class, 'showResetPassword'])->name('password.reset');

Route::post('/reset-password', [App\Http\Controllers\PasswordResetController::class, 'resetPassword'])->name('password.update');

// Protected student routes
Route::middleware(['auth:student'])->group(function () {
    Route::get('/student/dashboard', function () {
        return view('student-dashboard');
    })->name('student.dashboard');

    Route::get('/student/find-tutor', function () {
        return view('find-tutor');
    })->name('Findtutor');

    Route::get('/student/tutors', function () {
        return view('student.tutors.index');
    })->name('student.tutors.index');

    Route::get('/student/profile/edit', [StudentProfileController::class, 'edit'])->name('student.profile.edit');

    Route::put('/student/profile/update', [StudentProfileController::class, 'update'])->name('student.profile.update');

    Route::post('/student/logout', [LoginController::class, 'studentLogout'])->name('student.logout');

    // Booking routes
    Route::get('/student/book-session', [StudentSessionController::class, 'index'])->name('student.book-session');
    Route::post('/student/book-session/store', [StudentSessionController::class, 'store'])->name('student.book-session.store');
    Route::get('/student/my-bookings', [StudentSessionController::class, 'myBookings'])->name('student.my-bookings');
    Route::get('/student/tutor/{id}/details', [StudentSessionController::class, 'getTutorDetails'])->name('student.tutor.details');
    Route::get('/student/sessions/upcoming', [StudentSessionController::class, 'getUpcomingSessions'])->name('student.sessions.upcoming');

    // Messages route
    Route::get('/student/messages', function () {
        return view('student.chat.student-messages');
    })->name('student.messages');

   
});

// Protected tutor routes
Route::middleware(['auth:tutor'])->group(function () {
    Route::get('/tutor/dashboard', function () {
        return view('tutor-dashboard', [
            'tutor' => Auth::guard('tutor')->user()
        ]);
    })->name('tutor.dashboard');

    Route::get('/tutor/profile/edit', [App\Http\Controllers\TutorProfileController::class, 'edit'])->name('tutor.profile.edit');
    Route::put('/tutor/profile/update', [App\Http\Controllers\TutorProfileController::class, 'update'])->name('tutor.profile.update');

    // Tutor booking management routes
    Route::get('/tutor/bookings', [TutorSessionController::class, 'index'])->name('tutor.bookings.index');
    Route::get('/tutor/bookings/{id}', [TutorSessionController::class, 'show'])->name('tutor.bookings.show');
    Route::post('/tutor/bookings/{id}/accept', [TutorSessionController::class, 'accept'])->name('tutor.bookings.accept');
    Route::post('/tutor/bookings/{id}/reject', [TutorSessionController::class, 'reject'])->name('tutor.bookings.reject');
    Route::post('/tutor/bookings/{id}/complete', [TutorSessionController::class, 'complete'])->name('tutor.bookings.complete');
    Route::post('/tutor/bookings/{id}/cancel', [TutorSessionController::class, 'cancel'])->name('tutor.bookings.cancel');
    
    // Tutor logout route
    Route::post('/tutor/logout', [LoginController::class, 'tutorLogout'])->name('tutor.logout');
    
    // API routes for dashboard
    Route::get('/tutor/sessions/today', [TutorSessionController::class, 'getTodaysSessions'])->name('tutor.sessions.today');
    Route::get('/tutor/sessions/upcoming', [TutorSessionController::class, 'getUpcomingSessions'])->name('tutor.sessions.upcoming');
    
    // Tutor messages route
    Route::get('/tutor/messages', [TutorSessionController::class, 'messages'])->name('tutor.messages');
});

// Test route to check sessions (remove in production)
Route::get('/test/sessions', function() {
    $sessions = \App\Models\Session::with(['student', 'tutor'])->get();
    return response()->json($sessions);
})->name('test.sessions');

// Test route to check chat system (remove in production)
Route::get('/test/chat', function() {
    $students = \App\Models\Student::count();
    $tutors = \App\Models\Tutor::count();
    $messages = \App\Models\Message::count();
    
    return response()->json([
        'students' => $students,
        'tutors' => $tutors,
        'messages' => $messages,
        'broadcast_driver' => config('broadcasting.default'),
        'pusher_configured' => !empty(config('broadcasting.connections.pusher.key'))
    ]);
})->name('test.chat');

// Static student messages page (for design preview)
Route::get('/chat/student-messages', function () {
    return view('student.chat.student-messages');
});

// Channel for tutors
// Broadcast::channel('chat.tutor.{id}', function ( ,  ) {
//     // Allow if the user is a tutor and their ID matches
//     return $user instanceof \App\Models\Tutor && $user->id == $id;
// });

// Channel for students
// Broadcast::channel('chat.student.{id}', function ( ,  ) {
//     // Allow if the user is a student and their ID matches
//     return $user instanceof \App\Models\Student && $user->id == $id;
// });
