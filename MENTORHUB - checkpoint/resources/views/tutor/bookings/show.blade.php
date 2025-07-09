<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Details | MentorHub</title>
    <link rel="stylesheet" href="{{ asset('style/dashboard.css') }}">
    <style>
        .details-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 2rem;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
        }
        .details-header {
            border-bottom: 1px solid #eee;
            padding-bottom: 1rem;
            margin-bottom: 1.5rem;
        }
        .student-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .student-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background-color: #4a90e2;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.5rem;
        }
        .student-name {
            font-size: 1.5rem;
            font-weight: 600;
        }
        .details-body .detail-item {
            display: flex;
            justify-content: space-between;
            padding: 0.8rem 0;
            border-bottom: 1px solid #f5f5f5;
        }
        .detail-item strong {
            color: #333;
        }
        .detail-item span {
            color: #666;
        }
        .student-notes {
            background-color: #f8f9fa;
            border-radius: 5px;
            padding: 1rem;
            margin-top: 1.5rem;
        }
        .actions {
            margin-top: 2rem;
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
        }
        .btn {
            padding: 0.8rem 1.5rem;
            border-radius: 50px;
            text-decoration: none;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: all 0.3s;
        }
        .btn-success { background-color: #28a745; color: white; }
        .btn-danger { background-color: #dc3545; color: white; }
        .btn-secondary { background-color: #6c757d; color: white; }
        .btn-primary { background-color: #4a90e2; color: white; }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            border-radius: 8px;
        }

        .close-button {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close-button:hover,
        .close-button:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            margin-top: 1rem;
        }

        textarea {
            width: 100%;
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #ddd;
            margin-top: 1rem;
            resize: vertical;
        }
    </style>
</head>
<body>
    @include('layouts.tutor-header')

    <main>
        <div class="details-container">
            <div class="details-header">
                <div class="student-info">
                    <div class="student-avatar">
                        @if($booking->student->profile_picture)
                            <img src="{{ asset('storage/' . $booking->student->profile_picture) }}?{{ time() }}" alt="Student Profile Picture" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                        @else
                            {{ substr($booking->student->first_name, 0, 1) }}{{ substr($booking->student->last_name, 0, 1) }}
                        @endif
                    </div>
                    <div>
                        <div class="student-name">{{ $booking->student->first_name }} {{ $booking->student->last_name }}</div>
                        <a href="mailto:{{ $booking->student->email }}" style="color: #4a90e2;">{{ $booking->student->email }}</a>
                    </div>
                </div>
            </div>

            <div class="details-body">
                <h3>Session Details</h3>
                <div class="detail-item">
                    <strong>Status:</strong>
                    <span>{{ ucfirst($booking->status) }}</span>
                </div>
                <div class="detail-item">
                    <strong>Date:</strong>
                    <span>{{ $booking->formatted_date }}</span>
                </div>
                <div class="detail-item">
                    <strong>Time:</strong>
                    <span>{{ $booking->formatted_start_time }} - {{ $booking->formatted_end_time }} ({{ $booking->duration }})</span>
                </div>
                <div class="detail-item">
                    <strong>Session Type:</strong>
                    <span>{{ ucwords(str_replace('_', ' ', $booking->session_type)) }}</span>
                </div>
                <div class="detail-item">
                    <strong>Session Rate:</strong>
                    <span>${{ number_format($booking->rate, 2) }}/hour</span>
                </div>

            </div>

            @if($booking->notes)
            <div class="student-notes">
                <strong>Student's Notes:</strong>
                <p>{{ $booking->notes }}</p>
            </div>
            @endif

            @if($booking->status == 'pending')
            <div class="actions">
                <button type="button" class="btn btn-success" onclick="document.getElementById('acceptModal').style.display='block'">Accept</button>
                <button type="button" class="btn btn-danger" onclick="document.getElementById('rejectModal').style.display='block'">Reject</button>
            </div>
            @endif

             <div class="actions">
                <a href="mailto:{{ $booking->student->email }}" class="btn btn-primary">Message</a>
                <a href="{{ route('tutor.bookings.index') }}" class="btn btn-secondary">Back to Bookings</a>
            </div>
        </div>
    </main>

    <!-- Accept Modal -->
    <div id="acceptModal" class="modal">
        <div class="modal-content">
            <span class="close-button" onclick="document.getElementById('acceptModal').style.display='none'">&times;</span>
            <h3>Accept Booking</h3>
            <p>You are about to accept this booking. You can add an optional message for the student below.</p>
            <form action="{{ route('tutor.bookings.accept', $booking->id) }}" method="POST">
                @csrf
                <textarea name="notes" placeholder="e.g., 'Looking forward to our session! Please come prepared with any questions you have.'">{{ $booking->notes }}</textarea>
                <div class="modal-actions">
                    <button type="button" class="btn btn-secondary" onclick="document.getElementById('acceptModal').style.display='none'">Cancel</button>
                    <button type="submit" class="btn btn-success">Confirm Acceptance</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="modal">
        <div class="modal-content">
            <span class="close-button" onclick="document.getElementById('rejectModal').style.display='none'">&times;</span>
            <h3>Reject Booking</h3>
            <p>Please provide a reason for rejecting this booking. This will be shared with the student.</p>
            <form action="{{ route('tutor.bookings.reject', $booking->id) }}" method="POST">
                @csrf
                <textarea name="rejection_reason" placeholder="e.g., 'I apologize, but I have a schedule conflict at this time. Please feel free to book another available slot.'" required></textarea>
                <div class="modal-actions">
                    <button type="button" class="btn btn-secondary" onclick="document.getElementById('rejectModal').style.display='none'">Cancel</button>
                    <button type="submit" class="btn btn-danger">Confirm Rejection</button>
                </div>
            </form>
        </div>
    </div>

    @include('layouts.footer')
</body>
</html> 