<?php

use App\Http\Controllers\Doctor\AppointmentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Doctor\PatientController;


Route::group([
    'middleware'=> ['auth'],
    'prefix'=> 'doctor',
    'as' => 'doctor.',
], function() {
    Route::get('appointments/search-patient', [AppointmentController::class, 'searchPatient'])->name('appointments.search-patient');
    Route::resource('appointments', AppointmentController::class);
    Route::resource('patients', PatientController::class);
});