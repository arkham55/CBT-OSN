<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-3xl text-slate-800 dark:text-slate-200 leading-tight tracking-wide relative z-10">
            {{ __('Profil Saya') }}
        </h2>
    </x-slot>

    <div class="py-12 relative z-10">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl shadow-xl shadow-blue-900/5 border border-white/50 dark:border-slate-700 sm:rounded-3xl p-8 relative overflow-hidden">
                <div class="max-w-xl relative z-10">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl shadow-xl shadow-blue-900/5 border border-white/50 dark:border-slate-700 sm:rounded-3xl p-8 relative overflow-hidden">
                <div class="max-w-xl relative z-10">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl shadow-xl shadow-rose-900/5 border border-rose-100 dark:border-rose-900/50 sm:rounded-3xl p-8 relative overflow-hidden">
                <div class="max-w-xl relative z-10">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
            
        </div>
    </div>
</x-app-layout>
