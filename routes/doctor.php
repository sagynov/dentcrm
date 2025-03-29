<?php

use App\Http\Controllers\Doctor\AppointmentController;
use App\Http\Controllers\Doctor\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Doctor\PatientController;


Route::group([
    'middleware'=> ['auth', 'can:doctor'],
    'prefix'=> 'doctor',
    'as' => 'doctor.',
], function() {
    Route::get('/', [DashboardController::class,'index'])->name('dashboard');
    Route::get('appointments/search-patient', [AppointmentController::class, 'searchPatient'])->name('appointments.search-patient');
    Route::resource('appointments', AppointmentController::class);
    Route::post('patients/upload-record-attachment/{patient}', [PatientController::class, 'uploadRecordAttachment'])->name('patients.upload-record-attachment');
    Route::post('patients/add-record/{patient}', [PatientController::class, 'addRecord'])->name('patients.add-record');
    Route::resource('patients', PatientController::class);
});