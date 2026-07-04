<x-slot name="header">
    <h2 class="font-extrabold text-3xl text-slate-800 dark:text-slate-200 leading-tight tracking-wide relative z-10">
        Riwayat Ujian & Analisis
    </h2>
</x-slot>

<div class="py-12">
    <!-- Riwayat Ujian Section -->
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-4">
        @if($history && $history->isNotEmpty())
            <!-- Statistics Dashboard -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8 relative z-10">
                
                <!-- Total Ujian -->
                <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl rounded-2xl p-5 shadow-sm border border-slate-200 dark:border-slate-700 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Total Latihan</p>
                            <h3 class="text-3xl font-black text-slate-800 dark:text-slate-200">{{ $stats['total'] }}</h3>
                        </div>
                        <div class="p-3 bg-indigo-50 dark:bg-indigo-900/50 rounded-xl text-indigo-600 dark:text-indigo-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        </div>
                    </div>
                </div>

                <!-- Rata-rata Skor -->
                <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl rounded-2xl p-5 shadow-sm border border-slate-200 dark:border-slate-700 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Rata-rata Skor</p>
                            <h3 class="text-3xl font-black text-slate-800 dark:text-slate-200">{{ number_format($stats['avg_score'], 1) }}</h3>
                        </div>
                        <div class="p-3 bg-blue-50 dark:bg-blue-900/50 rounded-xl text-blue-600 dark:text-blue-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        </div>
                    </div>
                </div>

                <!-- Rata-rata Waktu -->
                <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl rounded-2xl p-5 shadow-sm border border-slate-200 dark:border-slate-700 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Waktu Rata-rata</p>
                            @php
                                $avgMins = floor($stats['avg_time_seconds'] / 60);
                                $avgSecs = round($stats['avg_time_seconds'] % 60);
                            @endphp
                            <h3 class="text-3xl font-black text-slate-800 dark:text-slate-200">
                                @if($avgMins > 0)
                                    {{ $avgMins }}<span class="text-lg text-slate-500 font-bold ml-1 mr-1">m</span>
                                @endif
                                {{ $avgSecs }}<span class="text-lg text-slate-500 font-bold ml-1">s</span>
                            </h3>
                        </div>
                        <div class="p-3 bg-purple-50 dark:bg-purple-900/50 rounded-xl text-purple-600 dark:text-purple-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                </div>

                <!-- Tren Performa -->
                <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl rounded-2xl p-5 shadow-sm border border-slate-200 dark:border-slate-700 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Tren Performa</p>
                            <h3 class="text-2xl font-black {{ $stats['trend_color'] }} flex items-center">
                                {{ $stats['trend'] }}
                            </h3>
                        </div>
                        <div class="p-3 bg-slate-50 dark:bg-slate-900/50 rounded-xl text-2xl">
                            {{ $stats['trend_icon'] }}
                        </div>
                    </div>
                </div>

            </div>

            <h3 class="text-lg font-bold text-slate-800 dark:text-slate-200 mb-4 relative z-10 flex items-center">
                <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                Daftar Riwayat Ujian
            </h3>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 relative z-10">
                @foreach($history as $result)
                    <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-[0_6px_0_rgb(203,213,225)] dark:shadow-[0_6px_0_rgb(30,41,59)] border-2 border-slate-200 dark:border-slate-700 hover:shadow-[0_3px_0_rgb(203,213,225)] dark:hover:shadow-[0_3px_0_rgb(30,41,59)] hover:translate-y-1 transition-all duration-200 flex flex-col md:flex-row justify-between md:items-center gap-4">
                        
                        <div class="flex-1">
                            <h4 class="text-xl font-extrabold text-slate-800 dark:text-slate-200 mb-2">{{ $result->subject->name }}</h4>
                            <div class="flex flex-wrap items-center gap-3 text-sm font-medium text-slate-500 dark:text-slate-400 mb-2">
                                <span class="flex items-center bg-slate-100 dark:bg-slate-700 px-2.5 py-1 rounded-lg">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    {{ $result->created_at->format('d M Y, H:i') }}
                                </span>
                                @if($result->time_taken_seconds !== null)
                                    <span class="flex items-center bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-400 px-2.5 py-1 rounded-lg border border-indigo-100 dark:border-indigo-800/50">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        @php
                                            $mins = floor($result->time_taken_seconds / 60);
                                            $secs = $result->time_taken_seconds % 60;
                                        @endphp
                                        {{ $mins }} mnt {{ $secs }} dtk
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="flex flex-row md:flex-col items-center justify-between md:justify-center gap-4 border-t md:border-t-0 md:border-l border-slate-100 dark:border-slate-700 pt-4 md:pt-0 md:pl-6">
                            <div class="text-center">
                                <span class="block text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Skor</span>
                                <div class="inline-flex items-center justify-center w-14 h-14 rounded-full font-black text-xl shadow-inner {{ $result->score > 50 ? 'bg-emerald-100 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-400 border-2 border-emerald-200 dark:border-emerald-800/50' : 'bg-rose-100 dark:bg-rose-900/40 text-rose-600 dark:text-rose-400 border-2 border-rose-200 dark:border-rose-800/50' }}">
                                    {{ $result->score }}
                                </div>
                            </div>
                            <a href="{{ route('student.result', $result->id) }}" class="inline-flex justify-center items-center px-5 py-2.5 bg-indigo-500 hover:bg-indigo-600 dark:bg-indigo-600 dark:hover:bg-indigo-500 text-white font-bold rounded-xl shadow-[0_4px_0_rgb(67,56,202)] hover:shadow-[0_2px_0_rgb(67,56,202)] hover:translate-y-[2px] transition-all text-xs tracking-wider uppercase">
                                Analisis AI &rarr;
                            </a>
                        </div>
                        
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white dark:bg-slate-800 rounded-3xl p-12 text-center border-2 border-slate-200 dark:border-slate-700 shadow-[0_8px_0_rgb(203,213,225)] dark:shadow-[0_8px_0_rgb(30,41,59)] relative z-10 max-w-2xl mx-auto mt-10">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-slate-100 dark:bg-slate-700 text-slate-300 dark:text-slate-500 mb-6">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <h3 class="text-2xl font-extrabold text-slate-800 dark:text-slate-200 mb-3">Belum Ada Riwayat</h3>
                <p class="text-slate-500 dark:text-slate-400 font-medium mb-6">Anda belum pernah mengikuti simulasi ujian. Silakan ke menu Pusat Latihan untuk memulai.</p>
                <a href="{{ route('dashboard') }}" class="inline-flex justify-center items-center px-6 py-3 bg-indigo-500 hover:bg-indigo-600 dark:bg-indigo-600 dark:hover:bg-indigo-500 text-white font-bold rounded-xl shadow-[0_4px_0_rgb(67,56,202)] hover:shadow-[0_2px_0_rgb(67,56,202)] hover:translate-y-[2px] transition-all text-sm tracking-widest uppercase">Mulai Latihan Sekarang</a>
            </div>
        @endif
    </div>
</div>
