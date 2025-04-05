<?php

use App\Http\Controllers\Doctor\AppointmentController;
use App\Http\Controllers\Doctor\ClinicController;
use App\Http\Controllers\Doctor\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Doctor\PatientController;
use App\Http\Controllers\Doctor\ServiceController;

Route::group([
    'middleware'=> ['auth', 'can:doctor'],
    'prefix'=> 'doctor',
    'as' => 'doctor.',
], function() {
    Route::get('/', [DashboardController::class,'index'])->name('dashboard');
    Route::get('clinics/get-services', [ClinicController::class, 'getServices'])->name('clinics.get-services');
    Route::get('services/{service}/close', [ServiceController::class, 'close'])->name('services.close');
    Route::resource('services', ServiceController::class);
    Route::get('appointment-metric', [DashboardController::class,'appointmentMetric'])->name('appointment-metric');
    Route::get('appointments/{appointment}/cancel', [AppointmentController::class, 'cancel'])->name('appointments.cancel');
    Route::resource('appointments', AppointmentController::class);
    Route::get('patients/{patient}/get-all-services', [PatientController::class, 'getAllServices'])->name('patients.get-all-services');
    Route::get('patients/{patient}/get-services', [PatientController::class, 'getServices'])->name('patients.get-services');
    Route::post('patients/upload-record-attachment/{patient}', [PatientController::class, 'uploadRecordAttachment'])->name('patients.upload-record-attachment');
    Route::post('patients/add-record/{patient}', [PatientController::class, 'addRecord'])->name('patients.add-record');
    Route::resource('patients', PatientController::class);
});