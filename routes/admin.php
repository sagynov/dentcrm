<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ScheduleController;


Route::group([
    'middleware'=> ['auth'],
    'prefix'=> 'admin',
    'as' => 'admin.',
], function() {
    Route::resource('doctors', DoctorController::class);
    Route::resource('patients', PatientController::class);
    Route::resource('schedule', ScheduleController::class);
});