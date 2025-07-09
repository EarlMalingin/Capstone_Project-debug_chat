@vite(['resources/js/app.js'])
<div>
    <!-- BEGIN: All previous content of the file -->
    
    <!-- The rest of your component's HTML goes here. -->
    
    <!-- Existing content starts here -->
    <div class="messaging-container">
        <!-- Conversations Sidebar -->
        <div class="conversations-sidebar">
            <div class="sidebar-header">
                <div class="sidebar-title">Messages</div>
                <div class="search-box">
                    <input type="text" class="search-input" wire:model.live="searchTerm" placeholder="Search Tutors...">
                    <span class="search-icon">🔍</span>
                </div>
            </div>
            <div class="conversations-list" id="conversations-list">
                @forelse($this->filteredTutors as $tutor)
                    <div class="conversation-item {{ $selectedTutorId == $tutor->id ? 'active' : '' }}" 
                         wire:click="selectTutor({{ $tutor->id }})" 
                         style="cursor: pointer;">
                        <div class="conversation-avatar">
                            @if($tutor->profile_picture)
                                <img src="{{ asset('storage/' . $tutor->profile_picture) }}" alt="Profile" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
                            @else
                                {{ substr($tutor->first_name, 0, 1) }}{{ substr($tutor->last_name, 0, 1) }}
                            @endif
                        </div>
                        <div class="conversation-info">
                            <div class="conversation-name">{{ $tutor->first_name }} {{ $tutor->last_name }}</div>
                            <div class="conversation-preview">
                                @if($tutor->last_message)
                                    {{ Str::limit($tutor->last_message->message, 30) }}
                                @else
                                    No messages yet
                                @endif
                            </div>
                        </div>
                        <div class="conversation-meta">
                            @if($tutor->last_message)
                                <div class="conversation-time">{{ $tutor->last_message->created_at->diffForHumans() }}</div>
                            @endif
                            @if($tutor->unread_count > 0)
                                <div class="unread-badge">{{ $tutor->unread_count }}</div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div style="padding: 2rem; text-align: center; color: #666;">
                        No tutors found
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Chat Area -->
        <div class="chat-area">
            @if($selectedTutor)
                <div class="chat-header">
                    <div class="chat-user-info">
                        <div class="chat-avatar">
                            @if($selectedTutor->profile_picture)
                                <img src="{{ asset('storage/' . $selectedTutor->profile_picture) }}" alt="Profile" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
                            @else
                                {{ substr($selectedTutor->first_name, 0, 1) }}{{ substr($selectedTutor->last_name, 0, 1) }}
                            @endif
                        </div>
                        <div class="chat-user-details">
                            <h3>{{ $selectedTutor->first_name }} {{ $selectedTutor->last_name }}</h3>
                            <div class="chat-user-status">Online • {{ $selectedTutor->specialization }} Tutor</div>
                        </div>
                    </div>
                    <div class="chat-actions">
                        <button class="action-btn" title="Video Call">📹</button>
                        <button class="action-btn" title="Voice Call">📞</button>
                        <button class="action-btn" title="More Options">⋯</button>
                    </div>
                </div>

                <div class="messages-container" id="messages-container">
                    @forelse($messages as $message)
                        <div class="message {{ $message->sender_type === 'student' ? 'sent' : '' }}">
                            <div class="message-avatar">
                                @if($message->sender_type === 'student')
                                    {{ substr(Auth::guard('student')->user()->first_name, 0, 1) }}{{ substr(Auth::guard('student')->user()->last_name, 0, 1) }}
                                @else
                                    {{ substr($selectedTutor->first_name, 0, 1) }}{{ substr($selectedTutor->last_name, 0, 1) }}
                                @endif
                            </div>
                            <div class="message-content">
                                <div class="message-text">{{ $message->message }}</div>
                                <div class="message-time">{{ $message->created_at->format('g:i A') }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="empty-chat">
                            <div class="empty-chat-icon">💬</div>
                            <h3>Start a conversation</h3>
                            <p>Send a message to begin chatting with {{ $selectedTutor->first_name }}</p>
                        </div>
                    @endforelse
                </div>

                <div class="message-input-area">
                    <div class="message-input-container">
                        <textarea class="message-input" 
                                  wire:model="messageText" 
                                  wire:keydown.enter.prevent="sendMessage"
                                  placeholder="Type your message..." 
                                  rows="1"></textarea>
                        <div class="input-actions">
                            <button class="input-btn" title="Attach File">📎</button>
                            <button class="input-btn send-btn" 
                                    wire:click="sendMessage"
                                    title="Send Message">➤</button>
                        </div>
                    </div>
                </div>
            @else
                <div class="empty-chat">
                    <div class="empty-chat-icon">💬</div>
                    <h3>Select a tutor to start chatting</h3>
                    <p>Choose a tutor from the sidebar to begin a conversation</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Existing content ends here -->

    <!-- END: All previous content of the file -->

    <style>
        .messaging-container {
            display: flex;
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.1);
            overflow: hidden;
            height: 70vh;
            min-height: 500px;
        }
        /* Sidebar */
        .conversations-sidebar {
            width: 350px;
            border-right: 1px solid #e0e0e0;
            background: #f8f9fa;
            display: flex;
            flex-direction: column;
        }
        .sidebar-header {
            padding: 1.5rem;
            background: linear-gradient(135deg, #4a90e2, #5637d9);
            color: white;
            margin-bottom: 0;
        }
        .sidebar-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 0.7rem;
        }
        .search-box {
            position: relative;
            margin-bottom: 0.3rem;
        }
        .search-input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.5rem;
            border: none;
            border-radius: 25px;
            background: rgba(255,255,255,0.9);
            font-size: 0.9rem;
            outline: none;
        }
        .search-icon {
            position: absolute;
            left: 0.8rem;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
        }
        .conversations-list {
            flex: 1;
            overflow-y: auto;
            padding: 0.3rem 0 0 0;
        }
        .conversation-item {
            display: flex;
            align-items: center;
            padding: 1rem 1.5rem;
            transition: background-color 0.3s;
            border-bottom: 1px solid #eee;
        }
        .conversation-item:hover {
            background-color: #e9ecef;
        }
        .conversation-item.active {
            background-color: #4a90e2;
            color: white;
        }
        .conversation-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: #4a90e2;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            margin-right: 1rem;
            font-size: 1.1rem;
        }
        .conversation-info {
            flex: 1;
            min-width: 0;
        }
        .conversation-name {
            font-weight: 600;
            margin-bottom: 0.3rem;
            font-size: 0.95rem;
        }
        .conversation-preview {
            font-size: 0.85rem;
            color: #666;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .conversation-item.active .conversation-preview {
            color: rgba(255,255,255,0.8);
        }
        .conversation-meta {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 0.3rem;
        }
        .conversation-time {
            font-size: 0.75rem;
            color: #999;
        }
        .conversation-item.active .conversation-time {
            color: rgba(255,255,255,0.7);
        }
        .unread-badge {
            background: #ff4757;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
            font-weight: bold;
        }
        /* Chat Area */
        .chat-area {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        .chat-header {
            padding: 1.5rem;
            border-bottom: 1px solid #e0e0e0;
            background: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .chat-user-info {
            display: flex;
            align-items: center;
        }
        .chat-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: #4a90e2;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            margin-right: 1rem;
        }
        .chat-user-details h3 {
            font-size: 1.1rem;
            margin-bottom: 0.2rem;
        }
        .chat-user-status {
            font-size: 0.85rem;
            color: #28a745;
            font-weight: 500;
        }
        .chat-actions {
            display: flex;
            gap: 0.5rem;
        }
        .action-btn {
            background: none;
            border: 1px solid #ddd;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 1.1rem;
        }
        .action-btn:hover {
            background: #f8f9fa;
            border-color: #4a90e2;
            color: #4a90e2;
        }
        .messages-container {
            flex: 1;
            overflow-y: auto;
            padding: 1.5rem;
            background: #f8f9fa;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        .message {
            display: flex;
            align-items: flex-end;
            gap: 0.5rem;
            max-width: 70%;
        }
        .message.sent {
            align-self: flex-end;
            flex-direction: row-reverse;
        }
        .message-avatar {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #4a90e2;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 0.8rem;
            font-weight: bold;
        }
        .message-content {
            background: white;
            padding: 0.8rem 1rem;
            border-radius: 18px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            position: relative;
        }
        .message.sent .message-content {
            background: #4a90e2;
            color: white;
        }
        .message-text {
            font-size: 0.9rem;
            line-height: 1.4;
        }
        .message-time {
            font-size: 0.75rem;
            color: #999;
            margin-top: 0.3rem;
        }
        .message.sent .message-time {
            color: rgba(255,255,255,0.8);
            text-align: right;
        }
        /* Message Input */
        .message-input-area {
            padding: 1.5rem;
            background: white;
            border-top: 1px solid #e0e0e0;
        }
        .message-input-container {
            display: flex;
            align-items: flex-end;
            gap: 1rem;
            background: #f8f9fa;
            border: 2px solid #e0e0e0;
            border-radius: 25px;
            padding: 0.5rem;
            transition: border-color 0.3s;
        }
        .message-input-container:focus-within {
            border-color: #4a90e2;
        }
        .message-input {
            flex: 1;
            border: none;
            background: none;
            padding: 0.8rem 1rem;
            font-size: 0.9rem;
            outline: none;
            resize: none;
            max-height: 100px;
            min-height: 20px;
        }
        .input-actions {
            display: flex;
            gap: 0.5rem;
        }
        .input-btn {
            background: none;
            border: none;
            border-radius: 50%;
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s;
            color: #666;
        }
        .input-btn:hover {
            background: #e9ecef;
            color: #4a90e2;
        }
        .send-btn {
            background: #4a90e2;
            color: white;
        }
        .send-btn:hover {
            background: #3a7ccc;
        }
        /* Empty State */
        .empty-chat {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: #666;
        }
        .empty-chat-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
            color: #ddd;
        }
        .empty-chat h3 {
            font-size: 1.3rem;
            margin-bottom: 0.5rem;
            color: #333;
        }
        /* Responsive */
        @media (max-width: 768px) {
            .messaging-container {
                flex-direction: column;
                height: auto;
                min-height: 70vh;
            }
            .conversations-sidebar {
                width: 100%;
                height: 200px;
            }
            .conversations-list {
                flex-direction: row;
                overflow-x: auto;
                overflow-y: hidden;
                padding: 0.5rem;
            }
            .conversation-item {
                min-width: 200px;
                border-right: 1px solid #eee;
                border-bottom: none;
            }
            .chat-area {
                height: 400px;
            }
        }
    </style>
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('refreshMessages', () => {
                // Auto-scroll to bottom when new messages arrive
                const messagesContainer = document.getElementById('messages-container');
                if (messagesContainer) {
                    messagesContainer.scrollTop = messagesContainer.scrollHeight;
                }
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Replace 1 with the actual student ID if needed
            if (window.Echo) {
                window.Echo.private('private-chat.student.1')
                    .listen('MessageSent', (e) => {
                        console.log('DEBUG: Received MessageSent event on private-chat.student.1:', e);
                    });
            } else {
                console.log('DEBUG: Echo is not defined');
            }
        });
    </script>
</div>