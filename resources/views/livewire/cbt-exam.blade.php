<x-slot name="header">
    <h2 class="font-extrabold text-2xl text-slate-800 dark:text-slate-200 leading-tight relative z-10">
        Ujian: {{ $subject->name }}
    </h2>
</x-slot>

<div class="py-8">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <div class="flex flex-col md:flex-row gap-8 relative">
            
            <!-- Area Soal (Kiri) -->
            <div class="w-full md:w-3/4 bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl shadow-xl shadow-blue-900/5 dark:shadow-none border border-white/50 dark:border-slate-700 sm:rounded-2xl p-8 relative overflow-hidden transition-all duration-300">
                
                <!-- Indikator Loading Livewire -->
                <div wire:loading class="absolute inset-0 bg-white/60 dark:bg-slate-900/60 backdrop-blur-sm z-20 flex flex-col items-center justify-center">
                    <div class="animate-spin rounded-full h-14 w-14 border-t-4 border-b-4 border-blue-600 dark:border-indigo-400 mb-4"></div>
                    <span class="text-blue-700 dark:text-indigo-300 font-semibold tracking-wide animate-pulse">Menyimpan Jawaban...</span>
                </div>

                @if($questions->count() > 0)
                    @php 
                        $currentQuestion = $questions[$currentIndex]; 
                    @endphp

                    <div class="flex justify-between items-center mb-8 border-b border-gray-100 dark:border-slate-700 pb-4">
                        <div class="inline-flex items-center px-4 py-1.5 rounded-full bg-blue-50 dark:bg-slate-700 text-blue-700 dark:text-indigo-300 font-bold text-sm border border-blue-100 dark:border-slate-600 shadow-sm">
                            <span class="mr-2">📝</span> Soal Nomor {{ $currentIndex + 1 }} dari {{ $questions->count() }}
                        </div>
                    </div>

                    <div wire:key="question-{{ $currentQuestion->id }}">
                    <!-- Isi Soal -->
                    <div class="mb-8 relative z-10">
                        @php
                            $contentParts = explode("\n\n", $currentQuestion->content, 2);
                            $firstPart = $contentParts[0];
                            $remainingPart = $contentParts[1] ?? '';
                        @endphp
                        
                        <div class="text-xl font-medium text-gray-800 dark:text-slate-200 leading-relaxed mb-6 font-serif">
                            {!! nl2br(e($firstPart)) !!}
                        </div>
                        
                        @if($currentQuestion->image_path)
                            <div class="mb-6 flex justify-center bg-gray-50/50 dark:bg-slate-900/50 p-4 rounded-xl border border-gray-100 dark:border-slate-700 shadow-inner">
                                <img src="{{ asset('storage/' . $currentQuestion->image_path) }}" alt="Gambar Soal" class="max-w-full h-auto rounded-lg shadow-md border border-gray-200 dark:border-slate-600 hover:scale-[1.02] transition-transform duration-300">
                            </div>
                        @endif

                        @if(!empty(trim($remainingPart)))
                            <div class="text-xl font-medium text-gray-800 dark:text-slate-200 leading-relaxed mb-6 font-serif">
                                {!! nl2br(e($remainingPart)) !!}
                            </div>
                        @endif
                    </div>

                    <!-- Pilihan Ganda -->
                    <div class="space-y-4 mb-10 relative z-10">
                        @foreach(['A' => $currentQuestion->option_a, 'B' => $currentQuestion->option_b, 'C' => $currentQuestion->option_c, 'D' => $currentQuestion->option_d, 'E' => $currentQuestion->option_e] as $key => $optionText)
                            @if($optionText && $optionText !== '-')
                                @php
                                    $isSelected = (isset($answers[$currentQuestion->id]) && $answers[$currentQuestion->id] === $key);
                                @endphp
                                <label wire:key="label-{{ $currentQuestion->id }}-{{ $key }}" class="group flex items-start p-4 border-2 rounded-xl cursor-pointer transition-all duration-200 {{ $isSelected ? 'bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-indigo-900/50 dark:to-purple-900/50 border-blue-400 dark:border-indigo-400 shadow-md shadow-blue-500/10 dark:shadow-none' : 'border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:border-blue-300 dark:hover:border-indigo-500 hover:bg-blue-50/30 dark:hover:bg-slate-700/50' }}">
                                    <div class="flex items-center h-6 mt-0.5">
                                        <input wire:key="radio-{{ $currentQuestion->id }}-{{ $key }}" wire:model="answers.{{ $currentQuestion->id }}" type="radio" name="answer_{{ $currentQuestion->id }}" value="{{ $key }}" class="w-5 h-5 text-blue-600 dark:text-indigo-500 bg-white dark:bg-slate-900 border-gray-300 dark:border-slate-600 focus:ring-blue-500 dark:focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900 transition-all">
                                    </div>
                                    <div class="ml-4 text-base flex-1">
                                        <div class="flex items-center">
                                            <span class="inline-flex items-center justify-center w-7 h-7 rounded bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-slate-300 font-bold mr-3 group-hover:bg-blue-100 dark:group-hover:bg-indigo-900 group-hover:text-blue-700 dark:group-hover:text-indigo-300 transition-colors {{ $isSelected ? '!bg-blue-600 dark:!bg-indigo-500 !text-white shadow-sm' : '' }}">
                                                {{ $key }}
                                            </span>
                                            <span class="text-gray-700 dark:text-slate-300 font-medium leading-relaxed {{ $isSelected ? 'text-gray-900 dark:text-white' : '' }}">{{ $optionText }}</span>
                                        </div>
                                    </div>
                                </label>
                            @endif
                        @endforeach
                    </div>
                    </div>

                    <!-- Tombol Navigasi Bawah -->
                    <div class="mt-8 flex justify-between items-center border-t border-gray-100 dark:border-slate-700 pt-6">
                        <button wire:click="prevQuestion" @if($currentIndex == 0) disabled @endif class="flex items-center px-6 py-3 bg-white dark:bg-slate-800 border-2 border-gray-200 dark:border-slate-700 text-gray-700 dark:text-slate-300 font-bold rounded-xl disabled:opacity-40 hover:bg-gray-50 dark:hover:bg-slate-700 hover:border-gray-300 dark:hover:border-slate-600 hover:shadow-sm transition-all focus:outline-none focus:ring-4 focus:ring-gray-100 dark:focus:ring-slate-700">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                            Soal Sebelumnya
                        </button>
                        
                        @if($currentIndex < $questions->count() - 1)
                            <button wire:click="nextQuestion" class="flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 dark:from-indigo-600 dark:to-purple-600 text-white font-bold rounded-xl shadow-lg shadow-blue-500/30 dark:shadow-none hover:shadow-xl hover:shadow-blue-500/40 hover:-translate-y-0.5 transition-all focus:outline-none focus:ring-4 focus:ring-blue-200 dark:focus:ring-indigo-400">
                                Soal Selanjutnya
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </button>
                        @else
                            <button type="button" onclick="finishExam()" class="flex items-center px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 dark:from-emerald-600 dark:to-green-700 text-white font-bold rounded-xl shadow-lg shadow-green-500/30 dark:shadow-none hover:shadow-xl hover:shadow-green-500/40 hover:-translate-y-0.5 transition-all focus:outline-none focus:ring-4 focus:ring-green-200 dark:focus:ring-emerald-400">
                                Selesai Ujian
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </button>
                        @endif
                    </div>
                @else
                    <div class="text-center py-20">
                        <div class="inline-block p-4 rounded-full bg-gray-100 dark:bg-slate-700 text-gray-400 dark:text-slate-500 mb-4">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-700 dark:text-slate-300 mb-2">Belum ada soal</h3>
                        <p class="text-gray-500 dark:text-slate-400">Soal untuk mata pelajaran ini belum tersedia.</p>
                    </div>
                @endif
            </div>

            <!-- Grid Nomor Soal (Kanan) -->
            <div class="w-full md:w-1/4 space-y-6">
                <!-- Countdown Timer -->
                <div id="timer-container" class="bg-blue-50/80 dark:bg-slate-800/80 backdrop-blur-md shadow-lg shadow-blue-900/10 dark:shadow-none border border-blue-200 dark:border-slate-700 rounded-2xl p-6 text-center transition-colors duration-500 sticky top-24">
                    <h3 class="text-xs font-black text-blue-900 dark:text-slate-300 mb-2 uppercase tracking-widest flex items-center justify-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Sisa Waktu
                    </h3>
                    <div id="exam-timer" class="text-3xl font-black text-blue-700 dark:text-indigo-400 tracking-wider font-mono">
                        --:--:--
                    </div>
                </div>

                <!-- Navigasi Soal -->
                <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl shadow-xl shadow-blue-900/5 dark:shadow-none border border-white/50 dark:border-slate-700 sm:rounded-2xl p-6 sticky top-52">
                    <h3 class="text-base font-extrabold text-gray-800 dark:text-slate-200 mb-6 flex items-center justify-center uppercase tracking-wider">
                        <svg class="w-5 h-5 mr-2 text-blue-500 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                        Navigasi Soal
                    </h3>
                    
                    <div class="grid grid-cols-5 gap-2 max-h-[50vh] overflow-y-auto pr-1 pb-2 custom-scrollbar">
                        @foreach($questions as $index => $q)
                            @php
                                $isAnswered = !empty($answers[$q->id]);
                                $isActive = $index === $currentIndex;
                                
                                $btnClass = 'w-full aspect-square flex items-center justify-center rounded-lg font-bold text-sm cursor-pointer transition-all duration-200 transform ';
                                
                                if($isActive) {
                                    $btnClass .= 'bg-blue-600 dark:bg-indigo-500 text-white shadow-md shadow-blue-500/40 dark:shadow-none ring-2 ring-offset-2 ring-blue-400 dark:ring-indigo-400 dark:ring-offset-slate-900 scale-105 z-10';
                                } elseif($isAnswered) {
                                    $btnClass .= 'bg-emerald-100 dark:bg-emerald-900/50 text-emerald-700 dark:text-emerald-400 border border-emerald-300 dark:border-emerald-700 hover:bg-emerald-200 dark:hover:bg-emerald-800/60 shadow-sm';
                                } else {
                                    $btnClass .= 'bg-white dark:bg-slate-700 text-gray-600 dark:text-slate-300 border border-gray-200 dark:border-slate-600 hover:border-blue-300 dark:hover:border-indigo-500 hover:bg-blue-50 dark:hover:bg-slate-600 hover:text-blue-600 dark:hover:text-white shadow-sm';
                                }
                            @endphp
                            <button wire:click="goToQuestion({{ $index }})" class="{{ $btnClass }}">
                                {{ $index + 1 }}
                            </button>
                        @endforeach
                    </div>
                    
                    <!-- Keterangan -->
                    <div class="mt-8 text-xs font-medium text-gray-500 dark:text-slate-400 space-y-3 bg-gray-50 dark:bg-slate-900/50 p-4 rounded-xl border border-gray-100 dark:border-slate-700">
                        <div class="flex items-center"><div class="w-4 h-4 bg-blue-600 dark:bg-indigo-500 shadow-sm rounded mr-3"></div> <span class="tracking-wide text-gray-700 dark:text-slate-300">Soal Saat Ini</span></div>
                        <div class="flex items-center"><div class="w-4 h-4 bg-emerald-100 dark:bg-emerald-900/50 border border-emerald-300 dark:border-emerald-700 rounded mr-3"></div> <span class="tracking-wide text-gray-700 dark:text-slate-300">Sudah Dijawab</span></div>
                        <div class="flex items-center"><div class="w-4 h-4 bg-white dark:bg-slate-700 border border-gray-300 dark:border-slate-600 rounded mr-3"></div> <span class="tracking-wide text-gray-700 dark:text-slate-300">Belum Dijawab</span></div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>

<!-- Script Countdown Timer -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let remainingSeconds = {{ $remainingSeconds }};
        const timerElement = document.getElementById('exam-timer');
        const timerContainer = document.getElementById('timer-container');
        
        function updateTimerDisplay() {
            if (remainingSeconds <= 0) {
                timerElement.innerText = "00:00:00";
                timerElement.classList.add('text-rose-600');
                return;
            }
            
            let h = Math.floor(remainingSeconds / 3600);
            let m = Math.floor((remainingSeconds % 3600) / 60);
            let s = remainingSeconds % 60;
            
            h = h < 10 ? '0' + h : h;
            m = m < 10 ? '0' + m : m;
            s = s < 10 ? '0' + s : s;
            
            timerElement.innerText = h + ':' + m + ':' + s;
            
            // Visual warning when less than 10 minutes (600 seconds)
            if (remainingSeconds <= 600) {
                timerContainer.classList.remove('bg-blue-50', 'border-blue-200', 'text-blue-900');
                timerContainer.classList.add('bg-rose-50', 'border-rose-300', 'text-rose-900', 'animate-pulse');
                timerElement.classList.remove('text-blue-700');
                timerElement.classList.add('text-rose-600');
            }
        }
        
        updateTimerDisplay();
        
        const countdownInterval = setInterval(function () {
            remainingSeconds--;
            updateTimerDisplay();
            
            if (remainingSeconds <= 0) {
                clearInterval(countdownInterval);
                window.isSubmittingExam = true;
                alert("Waktu ujian telah habis. Jawaban Anda akan otomatis disimpan.");
                @this.submitExam(); // Panggil method Livewire untuk submit
            }
        }, 1000);
        
        // Proteksi Refresh / Back (seperti di halaman Admin)
        window.isSubmittingExam = false;
        
        window.addEventListener('beforeunload', function (e) {
            if (!window.isSubmittingExam) {
                // Tampilkan peringatan bawaan browser
                e.preventDefault();
                e.returnValue = '';
            }
        });
    });
    
    // Fungsi dipanggil saat tombol Selesai Ujian diklik
    function finishExam() {
        if (confirm('Yakin ingin menyelesaikan ujian dan melihat hasil?')) {
            window.isSubmittingExam = true;
            @this.submitExam();
        }
    }
</script>

<style>
/* Custom Scrollbar for navigation grid */
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: #f1f5f9; 
    border-radius: 4px;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #cbd5e1; 
    border-radius: 4px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #94a3b8; 
}
</style>
