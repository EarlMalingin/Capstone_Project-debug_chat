# MentorHub Real-Time Chat System

## Overview
This document describes the real-time chat system implemented for MentorHub, allowing students and tutors to communicate in real-time.

## Features
- **Real-time messaging** between students and tutors
- **Dynamic conversation sidebar** showing available users
- **Search functionality** to find specific users
- **Message history** with timestamps
- **Responsive design** for mobile and desktop
- **Authentication-based access** with separate guards for students and tutors

## System Architecture

### Database Structure
The chat system uses a `messages` table with the following structure:
- `id` - Primary key
- `sender_id` - ID of the message sender
- `sender_type` - Type of sender ('student' or 'tutor')
- `receiver_id` - ID of the message receiver
- `receiver_type` - Type of receiver ('student' or 'tutor')
- `message` - The message content
- `created_at` - Timestamp
- `updated_at` - Timestamp

### Authentication
- **Students**: Use `auth:student` middleware
- **Tutors**: Use `auth:tutor` middleware
- Each user type has separate authentication guards

### Broadcasting
The system uses Pusher for real-time broadcasting:
- Private channels: `chat.{user_type}.{user_id}`
- Events: `MessageSent`

## API Endpoints

### For Students
- `GET /chat/tutors` - Get all available tutors
- `GET /chat/messages/{userId}` - Get messages with a specific tutor
- `POST /chat/messages` - Send a message to a tutor
- `GET /chat/conversations` - Get all conversations

### For Tutors
- `GET /chat/students` - Get all available students
- `GET /chat/messages/{userId}` - Get messages with a specific student
- `POST /chat/messages` - Send a message to a student
- `GET /chat/conversations` - Get all conversations

## Frontend Implementation

### Student Messages Page
- **Route**: `/student/messages`
- **View**: `resources/views/student/chat/student-messages.blade.php`
- **Features**: 
  - Sidebar with tutor list
  - Real-time message updates
  - Search functionality
  - Responsive design

### Tutor Messages Page
- **Route**: `/tutor/messages`
- **View**: `resources/views/tutor/messages.blade.php`
- **Features**:
  - Sidebar with student list
  - Real-time message updates
  - Search functionality
  - Responsive design

## Setup Instructions

### 1. Environment Configuration
Add the following to your `.env` file:
```env
BROADCAST_DRIVER=pusher
PUSHER_APP_KEY=your_pusher_key
PUSHER_APP_SECRET=your_pusher_secret
PUSHER_APP_ID=your_pusher_app_id
PUSHER_APP_CLUSTER=your_pusher_cluster
```

### 2. Database Migration
Run the migration to add the required columns:
```bash
php artisan migrate
```

### 3. Broadcasting Configuration
Ensure broadcasting is properly configured in `config/broadcasting.php`

### 4. Queue Configuration (Optional)
For better performance, configure queues:
```bash
php artisan queue:work
```

## Usage

### For Students
1. Log in to the student dashboard
2. Click on "Messages" in the quick links
3. Select a tutor from the sidebar
4. Start typing and sending messages

### For Tutors
1. Log in to the tutor dashboard
2. Click on "Messages" in the navigation
3. Select a student from the sidebar
4. Start typing and sending messages

## Testing

### Test Route
Visit `/test/chat` to check:
- Number of students and tutors in the database
- Number of messages
- Broadcasting configuration
- Pusher credentials

### Manual Testing
1. Open two browser windows
2. Log in as a student in one window
3. Log in as a tutor in another window
4. Navigate to messages in both windows
5. Start a conversation and verify real-time updates

## Troubleshooting

### Common Issues

1. **Messages not appearing in real-time**
   - Check Pusher credentials in `.env`
   - Verify `BROADCAST_DRIVER=pusher`
   - Check browser console for JavaScript errors

2. **Authentication errors**
   - Ensure users are logged in with correct guard
   - Check middleware configuration in routes

3. **Database errors**
   - Run `php artisan migrate` to ensure all tables exist
   - Check database connection

4. **JavaScript errors**
   - Verify Pusher and Laravel Echo are loaded
   - Check CSRF token is present in meta tags

### Debug Mode
Enable debug mode in `.env`:
```env
APP_DEBUG=true
```

## Security Considerations

1. **Authentication**: All chat routes are protected by authentication middleware
2. **Authorization**: Users can only access conversations they're part of
3. **CSRF Protection**: All POST requests include CSRF tokens
4. **Private Channels**: Broadcasting uses private channels for security

## Performance Optimization

1. **Database Indexing**: Consider adding indexes on `sender_id`, `receiver_id`, and `created_at`
2. **Message Pagination**: Implement pagination for large message histories
3. **Caching**: Cache user lists and conversation metadata
4. **Queue Processing**: Use queues for message broadcasting

## Future Enhancements

1. **File Attachments**: Support for sending files and images
2. **Message Status**: Read receipts and typing indicators
3. **Group Chats**: Support for group conversations
4. **Message Search**: Search within conversations
5. **Push Notifications**: Mobile push notifications for new messages
6. **Voice/Video Calls**: Integration with WebRTC for calls

## Support

For issues or questions about the chat system, please refer to:
- Laravel Broadcasting Documentation
- Pusher Documentation
- Laravel Echo Documentation 