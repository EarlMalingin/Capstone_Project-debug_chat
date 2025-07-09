<?php

namespace App\Livewire;

use App\Models\Message;
use App\Models\Student;
use App\Models\Tutor;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\On;

class TutorChatComponent extends Component
{
    public $selectedStudentId = null;
    public $messageText = '';
    public $students = [];
    public $messages = [];
    public $selectedStudent = null;
    public $searchTerm = '';

    public function mount()
    {
        $this->loadStudents();
    }

    public function loadStudents()
    {
        $this->students = Student::with(['messages' => function($query) {
            $query->where('receiver_id', Auth::guard('tutor')->id())
                  ->where('receiver_type', 'tutor')
                  ->orderBy('created_at', 'desc');
        }])->get()->map(function($student) {
            $student->unread_count = $student->messages->where('is_read', false)->count();
            $student->last_message = $student->messages->first();
            return $student;
        });
    }

    public function selectStudent($studentId)
    {
        $this->selectedStudentId = $studentId;
        $this->selectedStudent = Student::find($studentId);
        $this->loadMessages();
        $this->markMessagesAsRead();
    }

    public function loadMessages()
    {
        if (!$this->selectedStudentId) return;

        $tutorId = Auth::guard('tutor')->id();
        
        $this->messages = Message::betweenUsers(
            $tutorId, 'tutor',
            $this->selectedStudentId, 'student'
        )->orderBy('created_at', 'asc')->get();
    }

    public function sendMessage()
    {
        if (empty(trim($this->messageText)) || !$this->selectedStudentId) return;

        $message = Message::create([
            'sender_id' => Auth::guard('tutor')->id(),
            'sender_type' => 'tutor',
            'receiver_id' => $this->selectedStudentId,
            'receiver_type' => 'student',
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
        if (!$this->selectedStudentId) return;

        $tutorId = Auth::guard('tutor')->id();
        
        Message::where('sender_id', $this->selectedStudentId)
               ->where('sender_type', 'student')
               ->where('receiver_id', $tutorId)
               ->where('receiver_type', 'tutor')
               ->where('is_read', false)
               ->update(['is_read' => true]);

        $this->loadStudents();
    }

    #[On('refreshMessages')]
    public function refreshMessages()
    {
        $this->loadMessages();
        $this->loadStudents();
    }

    public function getFilteredStudentsProperty()
    {
        if (empty($this->searchTerm)) {
            return $this->students;
        }

        return $this->students->filter(function($student) {
            return str_contains(strtolower($student->first_name . ' ' . $student->last_name), strtolower($this->searchTerm)) ||
                   str_contains(strtolower($student->course), strtolower($this->searchTerm));
        });
    }

    public function getListeners()
    {
        $id = \Auth::guard('tutor')->id();
        return [
            "echo-private:private-chat.tutor.{$id},MessageSent" => 'refreshMessages',
        ];
    }

    public function render()
    {
        return view('livewire.tutor-chat-component');
    }
}
