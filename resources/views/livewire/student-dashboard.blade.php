
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <!-- Header Section -->
        <div class="mb-10 text-center relative z-10">
            <h1 class="text-4xl md:text-5xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600 dark:from-indigo-400 dark:to-purple-400 mb-3 tracking-wide">Pusat Latihan Olimpiade</h1>
            <p class="text-slate-500 dark:text-slate-400 font-medium text-lg">Pilih mata pelajaran yang ingin Anda ujikan hari ini</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($subjects as $subject)
            <div class="group relative bg-white dark:bg-slate-800 rounded-3xl p-8 shadow-[0_8px_0_rgb(203,213,225)] dark:shadow-[0_8px_0_rgb(30,41,59)] border-2 border-slate-200 dark:border-slate-700 hover:shadow-[0_4px_0_rgb(203,213,225)] dark:hover:shadow-[0_4px_0_rgb(30,41,59)] hover:translate-y-1 transition-all duration-200 flex flex-col justify-between overflow-hidden">
                <!-- Decorative element -->
                <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-100 dark:bg-indigo-900/40 rounded-bl-full opacity-50 group-hover:bg-indigo-200 dark:group-hover:bg-indigo-800/60 transition-colors"></div>
                
                <div class="relative z-10">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-indigo-500 dark:bg-indigo-600 text-white shadow-sm mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                    <h4 class="text-2xl font-extrabold text-slate-800 dark:text-white mb-3">{{ $subject->name }}</h4>
                    <p class="text-slate-500 dark:text-slate-400 text-sm mb-6 font-medium leading-relaxed">{{ Str::limit($subject->description, 100) }}</p>
                    
                    <div class="flex items-center text-sm font-bold text-indigo-700 dark:text-indigo-300 bg-indigo-50 dark:bg-indigo-900/40 border border-indigo-100 dark:border-indigo-800/50 px-3 py-1.5 rounded-xl w-max mb-8">
                        <svg class="w-4 h-4 mr-1.5 text-indigo-500 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        {{ $subject->questions()->count() }} Soal Tersedia
                    </div>
                </div>
                
                <a href="{{ route('student.cbt', $subject->id) }}" class="relative z-10 inline-flex justify-center items-center px-6 py-4 bg-indigo-500 hover:bg-indigo-600 dark:bg-indigo-600 dark:hover:bg-indigo-500 text-white font-black rounded-2xl shadow-[0_6px_0_rgb(67,56,202)] hover:shadow-[0_3px_0_rgb(67,56,202)] hover:translate-y-[3px] transition-all w-full text-sm tracking-widest uppercase">
                    Mulai Simulasi
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
            </div>
            @endforeach
        </div>
        
        @if($subjects->isEmpty())
        <div class="bg-white/60 dark:bg-slate-800/60 backdrop-blur-md rounded-2xl p-12 text-center border border-white/50 dark:border-slate-700 shadow-md">
            <svg class="w-16 h-16 text-gray-300 dark:text-slate-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            <h3 class="text-xl font-bold text-gray-700 dark:text-slate-300 mb-2">Belum Ada Soal</h3>
            <p class="text-gray-500 dark:text-slate-400">Silakan minta administrator untuk mengunggah soal latihan OSN.</p>
        </div>
        @endif
        
    </div>
</div>
