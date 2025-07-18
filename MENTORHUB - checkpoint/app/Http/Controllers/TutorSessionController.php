<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\Message;

class TutorSessionController extends Controller
{
    // Show all bookings for the tutor
    public function index()
    {
        try {
            $tutorId = Auth::guard('tutor')->id();
            
            if (!$tutorId) {
                return redirect()->route('login.tutor')->with('error', 'Please log in to view your bookings.');
            }

            $bookings = Session::where('tutor_id', $tutorId)
                ->with('student')
                ->orderBy('date', 'desc')
                ->orderBy('start_time', 'desc')
                ->get();

            $pendingBookings = $bookings->where('status', 'pending');
            $acceptedBookings = $bookings->where('status', 'accepted');
            $rejectedBookings = $bookings->where('status', 'rejected');
            $completedBookings = $bookings->where('status', 'completed');

            return view('tutor.bookings.index', compact('pendingBookings', 'acceptedBookings', 'rejectedBookings', 'completedBookings'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while loading your bookings. Please try again.');
        }
    }

    // Show booking details
    public function show($id)
    {
        $booking = Session::where('tutor_id', Auth::guard('tutor')->id())
            ->where('id', $id)
            ->with('student')
            ->firstOrFail();

        return view('tutor.bookings.show', compact('booking'));
    }

    // Accept booking
    public function accept(Request $request, $id)
    {
        $booking = Session::where('tutor_id', Auth::guard('tutor')->id())
            ->where('id', $id)
            ->where('status', 'pending')
            ->firstOrFail();

        $booking->update([
            'status' => 'accepted',
            'notes' => $request->input('notes', $booking->notes)
        ]);

        return redirect()->route('tutor.bookings.index')->with('success', 'Booking accepted successfully!');
    }

    // Reject booking
    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        $booking = Session::where('tutor_id', Auth::guard('tutor')->id())
            ->where('id', $id)
            ->where('status', 'pending')
            ->firstOrFail();

        $booking->update([
            'status' => 'rejected',
            'notes' => $request->input('rejection_reason')
        ]);

        return redirect()->route('tutor.bookings.index')->with('success', 'Booking rejected successfully!');
    }

    // Complete booking
    public function complete($id)
    {
        $booking = Session::where('tutor_id', Auth::guard('tutor')->id())
            ->where('id', $id)
            ->where('status', 'accepted')
            ->firstOrFail();

        $booking->update(['status' => 'completed']);

        return redirect()->route('tutor.bookings.index')->with('success', 'Session marked as completed!');
    }

    // Cancel booking
    public function cancel(Request $request, $id)
    {
        $request->validate([
            'cancellation_reason' => 'required|string|max:500'
        ]);

        $booking = Session::where('tutor_id', Auth::guard('tutor')->id())
            ->where('id', $id)
            ->whereIn('status', ['pending', 'accepted'])
            ->firstOrFail();

        $booking->update([
            'status' => 'cancelled',
            'notes' => $request->input('cancellation_reason')
        ]);

        return redirect()->route('tutor.bookings.index')->with('success', 'Booking cancelled successfully!');
    }

    // Get today's sessions for dashboard
    public function getTodaysSessions(Request $request)
    {
        try {
            $request->validate([
                'date' => 'required|date_format:Y-m-d',
            ]);

            $tutorId = Auth::guard('tutor')->id();
            
            if (!$tutorId) {
                return response()->json(['error' => 'Tutor not authenticated'], 401);
            }

            $sessions = Session::where('tutor_id', $tutorId)
                ->where('date', $request->date)
                ->where('status', 'accepted')
                ->with('student')
                ->orderBy('start_time')
                ->get();

            return response()->json($sessions);
        } catch (\Exception $e) {
            \Log::error('Error in getTodaysSessions: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while loading sessions.'], 500);
        }
    }

    // Get upcoming sessions for dashboard
    public function getUpcomingSessions()
    {
        $sessions = Session::where('tutor_id', Auth::guard('tutor')->id())
            ->where('date', '>=', today())
            ->whereIn('status', ['accepted', 'pending'])
            ->with('student')
            ->orderBy('date')
            ->orderBy('start_time')
            ->limit(5)
            ->get();

        return response()->json($sessions);
    }

    public function messages()
    {
        return view('tutor.chat.messages');
    }
} 