<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ScheduleController;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::resource('doctors', DoctorController::class)->middleware(['auth', 'verified']);
Route::resource('patients', PatientController::class)->middleware(['auth', 'verified']);
Route::resource('schedule', ScheduleController::class)->middleware(['auth', 'verified']);

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
