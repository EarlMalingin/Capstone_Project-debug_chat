<?php

namespace App\Livewire;

use App\Models\Message;
use App\Models\Tutor;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\On;

class StudentChatComponent extends Component
{
    public $selectedTutorId = null;
    public $messageText = '';
    public $tutors = [];
    public $messages = [];
    public $selectedTutor = null;
    public $searchTerm = '';

    public function mount(): void
    {
        $this->loadTutors();
    }

    public function loadTutors()
    {
        $this->tutors = Tutor::with(['messages' => function($query) {
            $query->where('receiver_id', Auth::guard('student')->id())
                  ->where('receiver_type', 'student')
                  ->orderBy('created_at', 'desc');
        }])->get()->map(function($tutor) {
            $tutor->unread_count = $tutor->messages->where('is_read', false)->count();
            $tutor->last_message = $tutor->messages->first();
            return $tutor;
        });
    }

    public function selectTutor($tutorId)
    {
        $this->selectedTutorId = $tutorId;
        $this->selectedTutor = Tutor::find($tutorId);
        $this->loadMessages();
        $this->markMessagesAsRead();
    }

    public function loadMessages()
    {
        if (!$this->selectedTutorId) return;

        $studentId = Auth::guard('student')->id();
        
        $this->messages = Message::betweenUsers(
            $studentId, 'student',
            $this->selectedTutorId, 'tutor'
        )->orderBy('created_at', 'asc')->get();
    }

    public function sendMessage()
    {
        if (empty(trim($this->messageText)) || !$this->selectedTutorId) return;

        $message = Message::create([
            'sender_id' => Auth::guard('student')->id(),
            'sender_type' => 'student',
            'receiver_id' => $this->selectedTutorId,
            'receiver_type' => 'tutor',
            'message' => trim($this->messageText),
            'is_read' => false
        ]);

        $this->messageText = '';
        $this->loadMessages();

        // Broadcast the message
        broadcast(new \App\Events\MessageSent($message))->toOthers();
    }

    public function markMessagesAsRead()
    {
        if (!$this->selectedTutorId) return;

        $studentId = Auth::guard('student')->id();
        
        Message::where('sender_id', $this->selectedTutorId)
               ->where('sender_type', 'tutor')
               ->where('receiver_id', $studentId)
               ->where('receiver_type', 'student')
               ->where('is_read', false)
               ->update(['is_read' => true]);

        $this->loadTutors();
    }

    #[On('refreshMessages')]
    public function refreshMessages()
    {
        $this->loadMessages();
        $this->loadTutors();
    }

    public function getFilteredTutorsProperty()
    {
        if (empty($this->searchTerm)) {
            return $this->tutors;
        }

        return $this->tutors->filter(function($tutor) {
            return str_contains(strtolower($tutor->first_name . ' ' . $tutor->last_name), strtolower($this->searchTerm)) ||
                   str_contains(strtolower($tutor->subjects), strtolower($this->searchTerm));
        });
    }

    public function getListeners()
    {
        $id = \Auth::guard('student')->id();
        return [
            "echo-private:private-chat.student.{$id},MessageSent" => 'refreshMessages',
        ];
    }

    public function render()
    {
        return view('livewire.student-chat-component');
    }
}
