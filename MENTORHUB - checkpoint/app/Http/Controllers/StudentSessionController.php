<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tutor;
use App\Models\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\Message;

class StudentSessionController extends Controller
{
    // Show all tutors and booking form
    public function index()
    {
        $tutors = Tutor::all();
        return view('student.book-session', compact('tutors'));
    }

    // Handle booking submission
    public function store(Request $request)
    {
        try {
            $request->validate([
                'tutor_id' => 'required|exists:tutors,id',
                'session_type' => 'required|in:face_to_face,online',
                'date' => 'required|date|after_or_equal:today',
                'start_time' => 'required',
                'end_time' => 'required|after:start_time',
                'notes' => 'nullable|string|max:500',
            ], [
                'end_time.after' => 'The end time must be after the start time.',
            ]);

            // Additional validation to ensure end_time is after start_time
            if ($request->start_time >= $request->end_time) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['end_time' => 'The end time must be after the start time.']);
            }

            // Get tutor to get their rate
            $tutor = Tutor::findOrFail($request->tutor_id);
            $studentId = Auth::guard('student')->id();

            if (!$studentId) {
                return redirect()->route('login.student')->with('error', 'Please log in to book a session.');
            }

            $session = Session::create([
                'student_id' => $studentId,
                'tutor_id' => $request->tutor_id,
                'session_type' => $request->session_type,
                'date' => $request->date,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'notes' => $request->notes,
                'rate' => $tutor->session_rate,
                'status' => 'pending',
            ]);

            return redirect()->route('student.book-session')->with('success', 'Session booking request sent successfully! The tutor will review and respond to your request.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'An error occurred while creating your booking. Please try again.']);
        }
    }

    // Get tutor details for modal
    public function getTutorDetails($id)
    {
        $tutor = Tutor::findOrFail($id);
        return response()->json($tutor);
    }

    // Get student's booking history
    public function myBookings()
    {
        $bookings = Session::where('student_id', Auth::guard('student')->id())
            ->with('tutor')
            ->orderBy('date', 'desc')
            ->orderBy('start_time', 'desc')
            ->get();

        return view('student.my-bookings', compact('bookings'));
    }

    public function getUpcomingSessions()
    {
        try {
            $studentId = Auth::guard('student')->id();
            if (!$studentId) {
                return response()->json(['error' => 'Student not authenticated'], 401);
            }

            $sessions = Session::where('student_id', $studentId)
                ->where('status', 'accepted')
                ->where('date', '>=', now()->toDateString())
                ->with('tutor')
                ->orderBy('date', 'asc')
                ->orderBy('start_time', 'asc')
                ->limit(5)
                ->get();

            return response()->json($sessions);

        } catch (\Exception $e) {
            \Log::error('Error in getUpcomingSessions for student: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while loading upcoming sessions.'], 500);
        }
    }

    public function messages()
    {
        return view('student.chat.student-messages');
    }
} 