<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - MentorHub</title>
    <link rel="stylesheet" href="{{ asset('style/Dashboard.css') }}">
    <style>
        .logo-img {
            margin-right: 0.5rem;
            width: 100px;
            height: 120px;
            transition: transform 0.3s ease;
        }
        .logo-img:hover {
            transform: scale(1.2);
        }
        .profile-container {
            max-width: 800px;
            margin: 5rem auto 5rem auto;
            padding-top: 5rem !important;
            padding-bottom: 5rem !important;
            padding-left: 2.5rem;
            padding-right: 2.5rem;
            background: white;
            border-radius: 12px 12px 0 0;
            min-height: calc(100vh - 250px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
        }
        html, body {
            background: #f5f7fa;
            line-height: 1.6;
            color: #333;
            background: linear-gradient(rgba(255, 255, 255, 0.85), rgba(255, 255, 255, 0.85)), url('{{ asset('images/Uc-background.jpg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        footer {
            margin-top: -20px;
            border-top: none;
        }

        .profile-icon {
            position: relative;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #4a90e2;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }

        .profile-icon-img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
        }

        .profile-header {
            text-align: center;
            margin-bottom: 2.5rem;
            position: relative;
        }

        .profile-header h1 {
            color: #2c3e50;
            margin-bottom: 0.5rem;
            font-size: 2rem;
            font-weight: 700;
        }

        .profile-header p {
            color: #7f8c8d;
            font-size: 1rem;
        }

        .profile-picture-container {
            position: relative;
            width: 150px;
            height: 150px;
            margin: 0 auto 1.5rem;
            border-radius: 50%;
            overflow: hidden;
            border: 4px solid #e0f2fe;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .profile-picture-container:hover {
            transform: translateY(-3px);
            border-color: #4a90e2;
        }

        .profile-picture {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-picture-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .profile-picture-container:hover .profile-picture-overlay {
            opacity: 1;
        }

        .form-group {
            margin-bottom: 1.75rem;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.75rem;
            font-weight: 600;
            color: #2c3e50;
            font-size: 0.95rem;
        }

        .form-control {
            width: 100%;
            padding: 0.85rem 1rem;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s;
            background-color: #f9fafb;
        }

        .form-control:focus {
            border-color: #4a90e2;
            outline: none;
            box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.2);
            background-color: white;
        }

        .btn {
            padding: 0.85rem 1.75rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 120px;
        }

        .btn-primary {
            background-color: #4a90e2;
            color: white;
            box-shadow: 0 4px 6px rgba(74, 144, 226, 0.2);
        }

        .btn-primary:hover {
            background-color: #3a7bc8;
            transform: translateY(-2px);
            box-shadow: 0 6px 10px rgba(74, 144, 226, 0.3);
        }

        .btn-secondary {
            background-color: white;
            color: #4a90e2;
            border: 1px solid #4a90e2;
            margin-right: 1rem;
        }

        .btn-secondary:hover {
            background-color: #f5f9ff;
            transform: translateY(-2px);
        }

        .password-fields {
            background: #f8fafc;
            padding: 1.75rem;
            border-radius: 10px;
            margin-top: 1.5rem;
            border: 1px solid #e0e0e0;
        }

        .password-fields h3 {
            color: #2c3e50;
            margin-bottom: 0.5rem;
            font-size: 1.25rem;
        }

        .password-fields p {
            color: #7f8c8d;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
        }

        .alert {
            padding: 1rem 1.5rem;
            margin-bottom: 1.75rem;
            border-radius: 8px;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
        }

        .alert-success {
            background-color: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .text-danger {
            color: #ef4444;
            font-size: 0.85rem;
            margin-top: 0.5rem;
            display: block;
            font-weight: 500;
        }

        .action-buttons {
            display: flex;
            justify-content: flex-end;
            margin-top: 2.5rem;
            gap: 1rem;
        }

        #profile_picture_input {
            display: none;
        }

        .form-section {
            margin-bottom: 2.5rem;
            padding-bottom: 2rem;
            border-bottom: 1px solid #f0f0f0;
        }

        .form-section:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .form-section-title {
            font-size: 1.25rem;
            color: #2c3e50;
            margin-bottom: 1.5rem;
            font-weight: 600;
            position: relative;
            padding-bottom: 0.5rem;
        }

        .form-section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 3px;
            background-color: #4a90e2;
            border-radius: 3px;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .profile-container {
                padding: 1.5rem;
                margin: 1rem;
            }
            
            .action-buttons {
                flex-direction: column;
                gap: 0.75rem;
            }
            
            .btn {
                width: 100%;
            }
            
            .btn-secondary {
                margin-right: 0;
                margin-bottom: 0.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="navbar">
            <div class="logo">
                <img src="{{asset('images/MentorHub.png')}}" alt="MentorHub Logo" class="logo-img" style="image-rendering: crisp-edges; width: auto; height: 80px;">
            </div>
            <button class="menu-toggle" id="menu-toggle">☰</button>
            <nav class="nav-links" id="nav-links">
                <a href="{{ route('student.dashboard') }}">Dashboard</a>
                <a href="{{route('Findtutor')}}">Tutors</a>
                <a href="#">Sessions</a>
                <a href="#">Resources</a>
            </nav>
            <div class="profile-icon" id="profile-icon">
                @if(Auth::guard('student')->user()->profile_picture)
                    <img src="{{ asset('storage/' . Auth::guard('student')->user()->profile_picture) }}?{{ time() }}" alt="Profile Picture" class="profile-icon-img">
                @else
                    {{ substr(Auth::guard('student')->user()->first_name, 0, 1) }}{{ substr(Auth::guard('student')->user()->last_name, 0, 1) }}
                @endif
                <div class="dropdown-menu" id="dropdown-menu">
                    <a href="{{ route('student.profile.edit') }}">My Profile</a>
                    <a href="#">Settings</a>
                    <a href="#">Help Center</a>
                    <a href="{{route('home')}}">Logout</a>
                </div>
            </div>
        </div>
    </header>
    
    <!-- Main Content -->
    <main class="profile-container">
        <div class="profile-header">
            <h1>Edit Your Profile</h1>
            <p>Update your personal information and preferences</p>
            
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
        </div>

        <form action="{{ route('student.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-section">
                <h3 class="form-section-title">Profile Picture</h3>
                <div class="form-group text-center">
                    <input type="file" name="profile_picture" id="profile_picture_input" accept="image/*">
                    <label for="profile_picture_input">
                        <div class="profile-picture-container">
                            @if($student->profile_picture)
                                <img src="{{ asset('storage/' . $student->profile_picture) }}?{{ time() }}" alt="Profile Picture" class="profile-picture">
                            @else
                                <div style="background-color: #4a90e2; color: white; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; font-size: 3rem; font-weight: 600;">
                                    {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                                </div>
                            @endif
                            <div class="profile-picture-overlay">
                                Change Photo
                            </div>
                        </div>
                    </label>
                    @error('profile_picture')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-section">
                <h3 class="form-section-title">Personal Information</h3>
                <div class="form-group">
                    <label for="first_name">First Name</label>
                    <input type="text" id="first_name" name="first_name" class="form-control" value="{{ old('first_name', $student->first_name) }}" required>
                    @error('first_name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="last_name">Last Name</label>
                    <input type="text" id="last_name" name="last_name" class="form-control" value="{{ old('last_name', $student->last_name) }}" required>
                    @error('last_name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $student->email) }}" required>
                    @error('email')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="student_id">Student ID</label>
                    <input type="text" id="student_id" name="student_id" class="form-control" value="{{ old('student_id', $student->student_id) }}" required>
                    @error('student_id')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="text" id="phone" name="phone" class="form-control" value="{{ old('phone', $student->phone) }}">
                    @error('phone')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-section">
                <h3 class="form-section-title">Academic Information</h3>
                <div class="form-group">
                    <label for="year_level">Year Level</label>
                    <select id="year_level" name="year_level" class="form-control" required>
                        <option value="">Select Year Level</option>
                        <option value="1st Year" {{ old('year_level', $student->year_level) == '1st Year' ? 'selected' : '' }}>1st Year</option>
                        <option value="2nd Year" {{ old('year_level', $student->year_level) == '2nd Year' ? 'selected' : '' }}>2nd Year</option>
                        <option value="3rd Year" {{ old('year_level', $student->year_level) == '3rd Year' ? 'selected' : '' }}>3rd Year</option>
                        <option value="4th Year" {{ old('year_level', $student->year_level) == '4th Year' ? 'selected' : '' }}>4th Year</option>
                        <option value="5th Year" {{ old('year_level', $student->year_level) == '5th Year' ? 'selected' : '' }}>5th Year</option>
                    </select>
                    @error('year_level')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="course">Course</label>
                    <input type="text" id="course" name="course" class="form-control" value="{{ old('course', $student->course) }}" required>
                    @error('course')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="subjects_interest">Subjects of Interest</label>
                    <textarea id="subjects_interest" name="subjects_interest" class="form-control" rows="3">{{ old('subjects_interest', $student->subjects_interest) }}</textarea>
                    @error('subjects_interest')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-section">
                <div class="password-fields">
                    <h3>Change Password</h3>
                    <p>Leave blank to keep current password</p>

                    <div class="form-group">
                        <label for="current_password">Current Password</label>
                        <input type="password" id="current_password" name="current_password" class="form-control" placeholder="Enter current password">
                        @error('current_password')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="new_password">New Password</label>
                        <input type="password" id="new_password" name="new_password" class="form-control" placeholder="Enter new password">
                        @error('new_password')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="new_password_confirmation">Confirm New Password</label>
                        <input type="password" id="new_password_confirmation" name="new_password_confirmation" class="form-control" placeholder="Confirm new password">
                    </div>
                </div>
            </div>

            <div class="action-buttons">
                <a href="{{ route('student.dashboard') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </main>

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <div class="footer-links">
                <a href="#">Privacy Policy</a>
                <a href="#">Terms of Service</a>
                <a href="#">FAQ</a>
                <a href="#">Contact</a>
            </div>
            <div class="copyright">
                &copy; 2025 MentorHub. All rights reserved.
            </div>
        </div>
    </footer>

    <script>
        // Toggle mobile menu
        document.addEventListener('DOMContentLoaded', function() {
            const menuToggle = document.getElementById('menu-toggle');
            const navLinks = document.getElementById('nav-links');
            
            menuToggle.addEventListener('click', function() {
                navLinks.classList.toggle('active');
            });
            
            // Toggle profile dropdown
            const profileIcon = document.getElementById('profile-icon');
            const dropdownMenu = document.getElementById('dropdown-menu');
            
            profileIcon.addEventListener('click', function(e) {
                e.stopPropagation();
                dropdownMenu.classList.toggle('active');
            });
            
            // Close dropdown when clicking outside
            document.addEventListener('click', function() {
                if (dropdownMenu.classList.contains('active')) {
                    dropdownMenu.classList.remove('active');
                }
            });

            // Preview profile picture when selected
            const profilePictureInput = document.getElementById('profile_picture_input');
            const profilePictureContainer = document.querySelector('.profile-picture-container');
            
            profilePictureInput.addEventListener('change', function(e) {
                if (e.target.files && e.target.files[0]) {
                    const reader = new FileReader();
                    
                    reader.onload = function(event) {
                        const img = profilePictureContainer.querySelector('img');
                        if (img) {
                            img.src = event.target.result;
                        } else {
                            const initialsDiv = profilePictureContainer.querySelector('div');
                            if (initialsDiv) {
                                initialsDiv.style.display = 'none';
                            }
                            const newImg = document.createElement('img');
                            newImg.src = event.target.result;
                            newImg.className = 'profile-picture';
                            profilePictureContainer.insertBefore(newImg, profilePictureContainer.firstChild);
                        }
                    }
                    
                    reader.readAsDataURL(e.target.files[0]);
                }
            });
        });
    </script>
</body>
</html>