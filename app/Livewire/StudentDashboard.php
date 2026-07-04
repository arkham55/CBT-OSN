<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Subject;

class StudentDashboard extends Component
{
    public function render()
    {
        $subjects = Subject::all();
        $history = \App\Models\Result::with('subject')
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('livewire.student-dashboard', [
            'subjects' => $subjects,
            'history' => $history
        ])->layout('layouts.app');
    }
}
