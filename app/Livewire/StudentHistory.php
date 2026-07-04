<?php

namespace App\Livewire;

use Livewire\Component;

class StudentHistory extends Component
{
    public function render()
    {
        $history = \App\Models\Result::with('subject')
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();
            
        $stats = [
            'total' => $history->count(),
            'avg_score' => $history->avg('score') ?? 0,
            'avg_time_seconds' => $history->avg('time_taken_seconds') ?? 0,
            'trend' => 'Belum cukup data',
            'trend_icon' => '➖',
            'trend_color' => 'text-slate-500 dark:text-slate-400'
        ];

        if ($stats['total'] >= 2) {
            $half = floor($stats['total'] / 2);
            $recentAvg = $history->take($half)->avg('score');
            $olderAvg = $history->slice($half)->avg('score');
            
            if ($recentAvg > $olderAvg) {
                $stats['trend'] = 'Meningkat';
                $stats['trend_icon'] = '📈';
                $stats['trend_color'] = 'text-emerald-500 dark:text-emerald-400';
            } elseif ($recentAvg < $olderAvg) {
                $stats['trend'] = 'Menurun';
                $stats['trend_icon'] = '📉';
                $stats['trend_color'] = 'text-rose-500 dark:text-rose-400';
            } else {
                $stats['trend'] = 'Stabil';
                $stats['trend_icon'] = '➖';
                $stats['trend_color'] = 'text-blue-500 dark:text-blue-400';
            }
        }
            
        return view('livewire.student-history', [
            'history' => $history,
            'stats' => $stats
        ])->layout('layouts.app');
    }
}
