<?php

use App\Http\Controllers\Owner\DepositController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Owner\AppointmentController;
use App\Http\Controllers\Owner\ClinicController;
use App\Http\Controllers\Owner\DashboardController;
use App\Http\Controllers\Owner\DoctorController;
use App\Http\Controllers\Owner\PatientController;
use App\Http\Controllers\Owner\PaymentController;
use App\Http\Controllers\Owner\PlanController;
use App\Http\Controllers\Owner\ScheduleController;
use App\Http\Controllers\Owner\ServiceController;

Route::group([
    'middleware'=> ['auth', 'can:owner'],
    'prefix'=> 'owner',
    'as' => 'owner.',
], function() {
    Route::get('plans', [PlanController::class, 'index'])->name('plans.index');
    Route::get('payments/success', [PaymentController::class, 'success'])->name('payments.success');
    Route::get('payments/fail', [PaymentController::class, 'fail'])->name('payments.fail');
    Route::post('payments', [PaymentController::class, 'store'])->name('payments.store');
    // Subscribed group
    Route::group(['middleware' => ['subscribed']], function() {
        Route::get('/', [DashboardController::class,'index'])->name('dashboard');
        Route::get('clinics/get-services', [ClinicController::class, 'getServices'])->name('clinics.get-services');
        Route::post('clinics/{clinic}/addService', [ClinicController::class, 'addService'])->name('clinics.addService');
        Route::resource('clinics', ClinicController::class);
        Route::get('appointment-metric', [DashboardController::class,'appointmentMetric'])->name('appointment-metric');
        Route::get('doctor-workload', [DashboardController::class,'doctorWorkload'])->name('doctor-workload');
        Route::resource('deposits', DepositController::class);
        Route::get('services/{service}/close', [ServiceController::class, 'close'])->name('services.close');
        Route::resource('services', ServiceController::class);
        Route::get('appointments/{appointment}/cancel', [AppointmentController::class, 'cancel'])->name('appointments.cancel');
        Route::resource('appointments', AppointmentController::class);
        Route::resource('doctors', DoctorController::class);
        Route::get('patients/{patient}/get-all-services', [PatientController::class, 'getAllServices'])->name('patients.get-all-services');
        Route::get('patients/{patient}/get-services', [PatientController::class, 'getServices'])->name('patients.get-services');
        Route::get('patients/{patient}/deposits', [PatientController::class, 'deposits'])->name('patients.deposits');
        Route::get('patients/{patient}/services', [PatientController::class, 'services'])->name('patients.services');
        Route::get('patients/{patient}/appointments', [PatientController::class, 'appointments'])->name('patients.appointments');
        Route::resource('patients', PatientController::class);
        Route::get('schedule/get-schedule', [ScheduleController::class, 'getSchedule'])->name('schedules.get-schedule');
        Route::resource('schedule', ScheduleController::class);
    });
    
});