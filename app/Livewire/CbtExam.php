<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Subject;
use App\Models\Question;
use App\Models\Result;
use Illuminate\Support\Facades\Auth;

class CbtExam extends Component
{
    public $subject;
    public $questions;
    public $currentIndex = 0;
    
    // Timer properties
    public $remainingSeconds = 0;
    
    // Store user's answers. Key is question_id, Value is 'A', 'B', 'C', 'D', 'E'
    public $answers = [];

    public function mount(Subject $subject)
    {
        $this->subject = $subject;
        $this->questions = $subject->questions()->get();
        
        // Initialize answers array
        foreach ($this->questions as $question) {
            $this->answers[$question->id] = null;
        }

        // Initialize Timer logic
        $sessionKey = 'exam_start_' . $subject->id;
        
        if (!session()->has($sessionKey)) {
            session()->put($sessionKey, now()->timestamp);
        }

        $startTime = session()->get($sessionKey);
        $durationInSeconds = ($subject->duration_minutes ?? 120) * 60;
        $elapsedTime = now()->timestamp - $startTime;
        
        $this->remainingSeconds = max(0, $durationInSeconds - $elapsedTime);
        
        if ($this->remainingSeconds <= 0) {
            // Jika masuk tapi waktu sudah habis
            return $this->submitExam();
        }
    }

    public function goToQuestion($index)
    {
        if ($index >= 0 && $index < $this->questions->count()) {
            $this->currentIndex = $index;
        }
    }

    public function nextQuestion()
    {
        if ($this->currentIndex < $this->questions->count() - 1) {
            $this->currentIndex++;
        }
    }

    public function prevQuestion()
    {
        if ($this->currentIndex > 0) {
            $this->currentIndex--;
        }
    }

    public function submitExam()
    {
        $score = 0;
        $total = $this->questions->count();
        $details = [];
        
        if ($total == 0) {
            return redirect()->route('dashboard');
        }

        foreach ($this->questions as $question) {
            $userAnswer = $this->answers[$question->id] ?? null;
            $status = 'empty';
            $points = 0;
            
            if (empty($userAnswer)) {
                $score += 0;
                $points = 0;
                $status = 'empty';
            } elseif ($userAnswer === $question->correct_option) {
                $score += 4;
                $points = 4;
                $status = 'correct';
            } else {
                $score -= 1;
                $points = -1;
                $status = 'wrong';
            }
            
            $details[] = [
                'question_id' => $question->id,
                'content' => $question->content,
                'user_answer' => $userAnswer,
                'correct_option' => $question->correct_option,
                'status' => $status,
                'points' => $points,
                'explanation' => $question->explanation
            ];
        }

        // Calculate time taken
        $sessionKey = 'exam_start_' . $this->subject->id;
        $startTime = session()->get($sessionKey);
        $timeTaken = $startTime ? (now()->timestamp - $startTime) : null;

        $result = Result::create([
            'user_id' => Auth::id(),
            'subject_id' => $this->subject->id,
            'score' => $score,
            'details' => $details,
            'time_taken_seconds' => $timeTaken,
        ]);

        // Optional: Save detailed answers to session or database
        session()->put('last_exam_answers', $this->answers);
        
        // Clear the timer session
        session()->forget('exam_start_' . $this->subject->id);

        return redirect()->route('student.result', ['result' => $result->id]);
    }

    public function render()
    {
        return view('livewire.cbt-exam')->layout('layouts.app');
    }
}
