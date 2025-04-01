<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Owner\AppointmentController;
use App\Http\Controllers\Owner\ClinicController;
use App\Http\Controllers\Owner\DashboardController;
use App\Http\Controllers\Owner\DoctorController;
use App\Http\Controllers\Owner\PatientController;
use App\Http\Controllers\Owner\ScheduleController;


Route::group([
    'middleware'=> ['auth', 'can:owner'],
    'prefix'=> 'owner',
    'as' => 'owner.',
], function() {
    Route::get('/', [DashboardController::class,'index'])->name('dashboard');
    Route::get('appointment-metric', [DashboardController::class,'appointmentMetric'])->name('appointment-metric');
    Route::get('doctor-workload', [DashboardController::class,'doctorWorkload'])->name('doctor-workload');
    Route::resource('clinics', ClinicController::class);
    Route::resource('appointments', AppointmentController::class);
    Route::resource('doctors', DoctorController::class);
    Route::resource('patients', PatientController::class);
    Route::get('schedule/get-schedule', [ScheduleController::class, 'getSchedule'])->name('schedules.get-schedule');
    Route::resource('schedule', ScheduleController::class);
});