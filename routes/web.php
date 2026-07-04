<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\GoogleController;
use Illuminate\Support\Facades\Route;
use App\Livewire\StudentDashboard;
use App\Livewire\CbtExam;
use App\Livewire\ExamResult;
use App\Livewire\StudentHistory;

// Google OAuth Routes
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    // Rute Siswa (CBT)
    Route::get('/dashboard', StudentDashboard::class)->name('dashboard');
    Route::get('/history', StudentHistory::class)->name('student.history');
    Route::get('/exam/{subject}', CbtExam::class)->name('student.cbt');
    Route::get('/result/{result}', ExamResult::class)->name('student.result');

    // Profile (Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
