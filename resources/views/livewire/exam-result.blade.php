<x-slot name="header">
    <h2 class="font-extrabold text-3xl text-slate-800 dark:text-slate-200 leading-tight tracking-wide relative z-10">
        Hasil Ujian: {{ $subject->name }}
    </h2>
</x-slot>

<div class="py-12">
    <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
        
        <!-- Kartu Skor Mewah -->
        <div class="relative bg-white/90 backdrop-blur-xl overflow-hidden shadow-2xl shadow-indigo-900/10 rounded-3xl mb-12 border border-white/50">
        <!-- Overview Card -->
        <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl shadow-xl shadow-blue-900/5 dark:shadow-none border border-white/50 dark:border-slate-700 sm:rounded-3xl p-8 mb-8 relative overflow-hidden">
            <!-- Decorative Background -->
            <div class="absolute -top-24 -right-24 w-64 h-64 bg-gradient-to-br from-blue-400 to-indigo-500 dark:from-indigo-600 dark:to-purple-600 rounded-full mix-blend-multiply dark:mix-blend-lighten filter blur-3xl opacity-30 dark:opacity-20"></div>
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 relative z-10">
                <div class="bg-blue-50/80 dark:bg-slate-900/50 backdrop-blur-md rounded-2xl p-6 border border-blue-100 dark:border-slate-700 text-center transform transition duration-300 hover:scale-105 hover:shadow-md">
                    <div class="inline-flex items-center justify-center w-12 h-12 bg-white dark:bg-slate-800 rounded-xl text-blue-600 dark:text-indigo-400 shadow-sm mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    </div>
                    <div class="text-3xl font-black text-gray-800 dark:text-slate-200">{{ $questions->count() }}</div>
                    <div class="text-sm font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider mt-1">Total Soal</div>
                </div>

                <div class="bg-gradient-to-br {{ $result->score >= 70 ? 'from-emerald-500 to-teal-600 dark:from-emerald-600 dark:to-teal-700' : 'from-indigo-500 to-purple-600 dark:from-indigo-600 dark:to-purple-700' }} rounded-2xl p-6 text-center text-white transform transition duration-300 hover:scale-105 hover:shadow-lg relative overflow-hidden">
                    <div class="absolute -right-4 -bottom-4 opacity-20">
                        <svg class="w-24 h-24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                    </div>
                    <div class="text-sm font-bold text-white/80 uppercase tracking-wider mb-1 relative z-10">Nilai Akhir</div>
                    <div class="text-5xl font-black relative z-10 tracking-tight">{{ number_format($result->score, 0) }}</div>
                </div>
            </div>
            
            <div class="mt-8 text-center">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center px-8 py-4 bg-gray-900 dark:bg-slate-700 text-white font-bold rounded-2xl hover:bg-gray-800 dark:hover:bg-slate-600 transition-all shadow-xl">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali ke Pusat Latihan
                </a>
            </div>
        </div>
        
        <!-- Flash Messages -->
        @if (session()->has('error'))
            <div class="bg-rose-100 dark:bg-rose-900/30 border-l-4 border-rose-500 text-rose-700 dark:text-rose-400 p-4 rounded-r shadow-md mb-8" role="alert">
                <strong class="font-bold">Oops!</strong>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- AI Recommendation Section -->
        <div class="bg-gradient-to-br from-indigo-900 to-purple-900 rounded-3xl p-8 md:p-10 mb-12 shadow-2xl relative overflow-hidden border border-indigo-700/50">
            <div class="absolute top-0 right-0 w-64 h-64 bg-white opacity-5 rounded-full blur-3xl -mt-20 -mr-20 pointer-events-none"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row gap-8 items-start">
                <div class="shrink-0 bg-white/10 p-4 rounded-2xl backdrop-blur-md border border-white/20">
                    <svg class="w-12 h-12 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                </div>
                
                <div class="flex-1 text-white">
                    <h3 class="text-2xl font-bold mb-2 flex items-center">
                        Rekomendasi Belajar AI
                        <span class="ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-500 text-white shadow-sm border border-indigo-400">Beta</span>
                    </h3>
                    
                    @if($result->ai_recommendation)
                        <div class="mt-4 bg-black/20 p-6 rounded-2xl border border-white/10 prose prose-sm prose-invert max-w-none prose-p:leading-relaxed prose-p:text-indigo-100 prose-headings:text-white prose-strong:text-indigo-200 prose-li:text-indigo-100">
                            {!! Str::markdown(str_replace(['$\rightarrow$', '\rightarrow'], '→', $result->ai_recommendation)) !!}
                        </div>
                    @else
                        <p class="text-indigo-200 text-lg mb-6 leading-relaxed">
                            Berdasarkan hasil jawaban Anda yang salah, AI kami dapat menganalisis materi mana yang masih menjadi kelemahan Anda.
                        </p>
                        
                        <button wire:click="getAIRecommendation" wire:loading.attr="disabled" class="inline-flex items-center justify-center px-8 py-3.5 text-base font-bold text-indigo-900 bg-white rounded-xl shadow-xl hover:bg-indigo-50 transition-all">
                            <span wire:loading.remove wire:target="getAIRecommendation">Minta Saran Belajar AI Sekarang</span>
                            <span wire:loading wire:target="getAIRecommendation">AI Sedang Menganalisis...</span>
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Detail Jawaban -->
        <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl shadow-xl shadow-blue-900/5 dark:shadow-none border border-white/50 dark:border-slate-700 sm:rounded-3xl p-8">
            <h3 class="text-xl font-black text-gray-800 dark:text-slate-200 mb-8 flex items-center">
                <svg class="w-6 h-6 mr-3 text-blue-500 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                Analisis Detail Jawaban
            </h3>

            <div class="space-y-8">
                @foreach($questions as $index => $q)
                    @php
                        $userAns = $userAnswers[$q->id] ?? null;
                        $isCorrect = $userAns === $q->correct_option;
                    @endphp
                    <div class="border-2 {{ $isCorrect ? 'border-emerald-100 dark:border-emerald-900/50 bg-emerald-50/30 dark:bg-emerald-900/10' : 'border-rose-100 dark:border-rose-900/50 bg-rose-50/30 dark:bg-rose-900/10' }} rounded-2xl p-6 relative overflow-hidden transition-all hover:shadow-md">
                        
                        <!-- Header Soal -->
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg {{ $isCorrect ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-400' : 'bg-rose-100 text-rose-700 dark:bg-rose-900/50 dark:text-rose-400' }} font-bold text-sm mr-4">
                                    {{ $index + 1 }}
                                </span>
                                <div class="text-sm font-bold {{ $isCorrect ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }} uppercase tracking-wider">
                                    {{ $isCorrect ? 'Jawaban Benar' : 'Jawaban Salah' }}
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-gray-800 dark:text-slate-200 font-medium mb-6 font-serif">
                            {!! nl2br(e($q->content)) !!}
                        </div>
                        
                        @if($q->image_path)
                            <div class="mb-6 flex justify-center bg-white dark:bg-slate-900/50 p-4 rounded-xl border border-gray-100 dark:border-slate-700 shadow-sm">
                                <img src="{{ asset('storage/' . $q->image_path) }}" alt="Gambar Soal" class="max-w-full h-auto rounded-lg">
                            </div>
                        @endif

                        <!-- Perbandingan Jawaban -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
                            <div class="bg-white dark:bg-slate-800 border-2 {{ $isCorrect ? 'border-emerald-200 dark:border-emerald-800' : 'border-rose-200 dark:border-rose-800' }} rounded-xl p-4">
                                <div class="text-xs font-bold text-gray-400 dark:text-slate-500 uppercase tracking-wider mb-2">Jawaban Anda</div>
                                <div class="flex items-start">
                                    <span class="inline-flex items-center justify-center min-w-[28px] h-7 rounded {{ $isCorrect ? 'bg-emerald-500 text-white' : 'bg-rose-500 text-white' }} font-bold text-sm mr-3">
                                        {{ $userAns ?: '-' }}
                                    </span>
                                    <span class="font-medium text-gray-700 dark:text-slate-300">
                                        {{ $userAns ? $q->{'option_'.strtolower($userAns)} : 'Tidak dijawab' }}
                                    </span>
                                </div>
                            </div>
                            
                            @if(!$isCorrect)
                                <div class="bg-emerald-50 dark:bg-emerald-900/20 border-2 border-emerald-200 dark:border-emerald-800 rounded-xl p-4">
                                    <div class="text-xs font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider mb-2">Kunci Jawaban</div>
                                    <div class="flex items-start">
                                        <span class="inline-flex items-center justify-center min-w-[28px] h-7 rounded bg-emerald-500 text-white font-bold text-sm mr-3">
                                            {{ $q->correct_option }}
                                        </span>
                                        <span class="font-medium text-emerald-900 dark:text-emerald-300">
                                            {{ $q->{'option_'.strtolower($q->correct_option)} }}
                                        </span>
                                    </div>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Penjelasan -->
                        @if($q->explanation)
                        <div class="mt-4 bg-indigo-50/50 dark:bg-indigo-900/20 border border-indigo-100 dark:border-indigo-900/50 rounded-xl p-5 relative">
                            <div class="absolute -top-3 left-4 bg-white dark:bg-slate-800 border border-indigo-100 dark:border-indigo-800 px-3 py-1 rounded-full text-xs font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-wider flex items-center shadow-sm">
                                <span class="text-base mr-1">🤖</span> AI Pembahasan
                            </div>
                            <div class="mt-2 text-gray-700 dark:text-slate-300 font-medium leading-relaxed prose prose-sm prose-indigo dark:prose-invert max-w-none">
                                {!! Str::markdown($q->explanation) !!}
                            </div>
                        </div>
                        @endif
                        
                    </div>
                @endforeach
            </div>
            
        </div>
    </div>
</div>
