<?php

use App\Http\Controllers\Owner\ClinicController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Owner\DoctorController;
use App\Http\Controllers\Owner\PatientController;
use App\Http\Controllers\ScheduleController;


Route::group([
    'middleware'=> ['auth'],
    'prefix'=> 'owner',
    'as' => 'owner.',
], function() {
    Route::post('clinics/set-active-clinic', [ClinicController::class,'setActiveClinic'])->name('clinics.set-active-clinic');
    Route::resource('clinics', ClinicController::class);
    Route::resource('doctors', DoctorController::class);
    Route::resource('patients', PatientController::class);
    Route::resource('schedule', ScheduleController::class);
});