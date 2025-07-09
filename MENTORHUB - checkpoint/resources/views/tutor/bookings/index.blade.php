<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings | MentorHub</title>
    <link rel="stylesheet" href="{{ asset('style/dashboard.css') }}">
    <style>
        .bookings-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        .tabs {
            display: flex;
            border-bottom: 2px solid #ddd;
            margin-bottom: 2rem;
        }
        .tab-link {
            padding: 1rem 1.5rem;
            cursor: pointer;
            border: none;
            background-color: transparent;
            font-size: 1rem;
            font-weight: 500;
            color: #666;
            position: relative;
            top: 2px;
        }
        .tab-link.active {
            color: #4a90e2;
            border-bottom: 2px solid #4a90e2;
            font-weight: 600;
        }
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
        }
        .booking-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .booking-details {
            flex: 1;
            min-width: 250px;
        }
        .booking-student {
            font-weight: 600;
            font-size: 1.1rem;
        }
        .booking-info {
            color: #666;
            margin-top: 0.5rem;
        }
        .booking-actions {
            display: flex;
            gap: 1rem;
        }
        .btn {
            padding: 0.5rem 1rem;
            border-radius: 50px;
            text-decoration: none;
            font-size: 0.9rem;
            cursor: pointer;
            border: none;
            transition: all 0.3s;
        }
        .btn-primary {
            background-color: #4a90e2;
            color: white;
        }
        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }
        .status-badge {
            display: inline-block;
            padding: 0.3em 0.7em;
            font-size: 0.75em;
            font-weight: 700;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 0.25rem;
            color: #fff;
        }
        .status-rejected { background-color: #dc3545; }
        .status-completed { background-color: #28a745; }
        .status-cancelled { background-color: #6c757d; }
    </style>
</head>
<body>
    @include('layouts.tutor-header')

    <main>
        <div class="bookings-container">
            <h1>My Bookings</h1>

            @if(session('success'))
                <div class="alert alert-success" style="background-color: #d4edda; color: #155724; padding: 1rem; border-radius: 5px; margin-bottom: 1rem; border: 1px solid #c3e6cb;">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-error" style="background-color: #f8d7da; color: #721c24; padding: 1rem; border-radius: 5px; margin-bottom: 1rem; border: 1px solid #f5c6cb;">
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-error" style="background-color: #f8d7da; color: #721c24; padding: 1rem; border-radius: 5px; margin-bottom: 1rem; border: 1px solid #f5c6cb;">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="tabs">
                <button class="tab-link active" onclick="openTab(event, 'pending')">Pending Requests ({{ $pendingBookings->count() }})</button>
                <button class="tab-link" onclick="openTab(event, 'accepted')">Upcoming Sessions ({{ $acceptedBookings->count() }})</button>
                <button class="tab-link" onclick="openTab(event, 'history')">History</button>
            </div>

            <div id="pending" class="tab-content active">
                <h2>Pending Requests</h2>
                @forelse($pendingBookings as $booking)
                    <div class="booking-card">
                        <div class="booking-details">
                            <div class="booking-student">{{ $booking->student->first_name }} {{ $booking->student->last_name }}</div>
                            <div class="booking-info">
                                <strong>Date:</strong> {{ $booking->formatted_date }} | 
                                <strong>Time:</strong> {{ $booking->formatted_start_time }} - {{ $booking->formatted_end_time }} |
                                <strong>Type:</strong> {{ ucwords(str_replace('_', ' ', $booking->session_type)) }}
                            </div>
                        </div>
                        <div class="booking-actions">
                            <a href="{{ route('tutor.bookings.show', $booking->id) }}" class="btn btn-primary">View Details</a>
                        </div>
                    </div>
                @empty
                    <p>No pending booking requests.</p>
                @endforelse
            </div>

            <div id="accepted" class="tab-content">
                <h2>Upcoming Sessions</h2>
                @forelse($acceptedBookings as $booking)
                    <div class="booking-card">
                        <div class="booking-details">
                            <div class="booking-student">{{ $booking->student->first_name }} {{ $booking->student->last_name }}</div>
                            <div class="booking-info">
                                <strong>Date:</strong> {{ $booking->formatted_date }} | 
                                <strong>Time:</strong> {{ $booking->formatted_start_time }} - {{ $booking->formatted_end_time }} |
                                <strong>Type:</strong> {{ ucwords(str_replace('_', ' ', $booking->session_type)) }}
                            </div>
                        </div>
                         <div class="booking-actions">
                            <a href="{{ route('tutor.bookings.show', $booking->id) }}" class="btn btn-secondary">View Details</a>
                        </div>
                    </div>
                @empty
                    <p>No upcoming sessions scheduled.</p>
                @endforelse
            </div>

            <div id="history" class="tab-content">
                <h2>Booking History</h2>
                @forelse($rejectedBookings->merge($completedBookings) as $booking)
                     <div class="booking-card">
                        <div class="booking-details">
                            <div class="booking-student">{{ $booking->student->first_name }} {{ $booking->student->last_name }}</div>
                            <div class="booking-info">
                                <strong>Date:</strong> {{ $booking->formatted_date }} | 
                                <strong>Status:</strong> <span class="status-badge status-{{ strtolower($booking->status) }}">{{ ucfirst($booking->status) }}</span>
                            </div>
                        </div>
                         <div class="booking-actions">
                            <a href="{{ route('tutor.bookings.show', $booking->id) }}" class="btn btn-secondary">View Details</a>
                        </div>
                    </div>
                @empty
                     <p>No booking history.</p>
                @endforelse
            </div>
        </div>
    </main>

    @include('layouts.footer')

    <script>
        function openTab(evt, tabName) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tab-content");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }
            tablinks = document.getElementsByClassName("tab-link");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }
            document.getElementById(tabName).style.display = "block";
            evt.currentTarget.className += " active";
        }
    </script>
</body>
</html> 