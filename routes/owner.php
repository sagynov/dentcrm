<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Owner\AppointmentController;
use App\Http\Controllers\Owner\ClinicController;
use App\Http\Controllers\Owner\DoctorController;
use App\Http\Controllers\Owner\PatientController;
use App\Http\Controllers\Owner\ScheduleController;


Route::group([
    'middleware'=> ['auth'],
    'prefix'=> 'owner',
    'as' => 'owner.',
], function() {
    Route::post('clinics/set-active-clinic', [ClinicController::class,'setActiveClinic'])->name('clinics.set-active-clinic');
    Route::resource('clinics', ClinicController::class);
    Route::resource('appointments', AppointmentController::class);
    Route::resource('doctors', DoctorController::class);
    Route::resource('patients', PatientController::class);
    Route::get('schedule/get-schedule', [ScheduleController::class, 'getSchedule'])->name('schedules.get-schedule');
    Route::resource('schedule', ScheduleController::class);
});