<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Result;

class ExamResult extends Component
{
    public $result;
    public $subject;
    public $questions;
    public $userAnswers = [];

    public function mount(Result $result)
    {
        // Make sure user owns this result
        if ($result->user_id !== auth()->id()) {
            abort(403);
        }

        $this->result = $result;
        $this->subject = $result->subject;
        $this->questions = $this->subject->questions;
        
        // Retrieve detailed answers from database
        $details = $this->result->details ?? [];
        
        $this->userAnswers = [];
        foreach ($details as $detail) {
            $this->userAnswers[$detail['question_id']] = $detail['user_answer'];
        }
        
        // Fallback to session if it's an old result before migration
        if (empty($this->userAnswers)) {
            $this->userAnswers = session()->get('last_exam_answers', []);
        }
    }

    public function getAIRecommendation()
    {
        if ($this->result->ai_recommendation) {
            return;
        }

        $apiKey = config('services.gemini.api_key') ?? env('GEMINI_API_KEY');
        if (!$apiKey) {
            session()->flash('error', 'Gemini API Key belum dikonfigurasi. Silakan tambahkan di .env.');
            return;
        }

        // Analyze performance
        $wrongConcepts = [];
        $details = $this->result->details ?? [];
        
        $totalQuestions = count($details);
        $emptyCount = 0;
        $wrongCount = 0;
        $correctCount = 0;

        foreach ($details as $detail) {
            if ($detail['status'] === 'empty') $emptyCount++;
            elseif ($detail['status'] === 'wrong') $wrongCount++;
            else $correctCount++;

            if ($detail['status'] === 'wrong' || $detail['status'] === 'empty') {
                $wrongConcepts[] = '"' . strip_tags($detail['content']) . '"';
            }
        }

        if (empty($wrongConcepts)) {
            $this->result->update(['ai_recommendation' => 'Luar biasa! Kamu berhasil menjawab semua soal dengan benar. Terus pertahankan prestasimu, manajemen waktumu pasti sudah sangat baik!']);
            $this->result->refresh();
            return;
        }

        $timeString = "";
        if ($this->result->time_taken_seconds) {
            $mins = floor($this->result->time_taken_seconds / 60);
            $secs = $this->result->time_taken_seconds % 60;
            $duration = $this->subject->duration_minutes ?? 120;
            $timeString = "Saya menghabiskan waktu {$mins} menit {$secs} detik dari total alokasi waktu {$duration} menit. ";
        }

        $prompt = "Saya adalah siswa SMP yang sedang belajar untuk OSN Biologi. Saya baru saja mengerjakan simulasi ujian dengan hasil:\n"
                . "- Total Soal: {$totalQuestions}\n"
                . "- Benar: {$correctCount}\n"
                . "- Salah: {$wrongCount}\n"
                . "- Kosong/Tidak Dijawab: {$emptyCount}\n"
                . $timeString . "\n\n"
                . "Saya salah/kosong pada materi terkait pertanyaan berikut:\n"
                . implode("\n- ", array_slice($wrongConcepts, 0, 5)) 
                . "\n\nTolong berikan:\n"
                . "1. Analisis materi apa yang harus saya pelajari ulang (fokus pada topik dari soal di atas).\n"
                . "2. Evaluasi manajemen waktu dan akurasi (berdasarkan data waktu & jumlah benar/salah/kosong), serta strategi OSN/Olimpiade yang spesifik (misal: menebak vs mengosongkan, mempercepat durasi, dll).\n\n"
                . "Gunakan bahasa Indonesia yang memotivasi, santai, dan friendly. Format dengan bullet points bila perlu agar rapi.";

        try {
            $response = \Illuminate\Support\Facades\Http::withoutVerifying()
                ->withHeaders([
                    'Content-Type' => 'application/json',
                ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}", [
                    'contents' => [
                        ['parts' => [['text' => $prompt]]]
                    ]
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $recommendation = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'Maaf, AI sedang sibuk. Coba lagi nanti.';
                
                $this->result->update(['ai_recommendation' => $recommendation]);
                $this->result->refresh();
            } else {
                $errorData = $response->json();
                if (isset($errorData['error']['code']) && $errorData['error']['code'] == 503) {
                    session()->flash('error', 'Server AI sedang sangat sibuk (High Demand). Silakan coba klik tombol lagi dalam beberapa saat ya! 🚀');
                } else {
                    $msg = $errorData['error']['message'] ?? 'Unknown Error';
                    session()->flash('error', 'Gagal menghubungi server AI: ' . $msg);
                }
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.exam-result')->layout('layouts.app');
    }
}
