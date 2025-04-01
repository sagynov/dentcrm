<?php

use App\Http\Controllers\Patient\AppointmentController;
use App\Http\Controllers\Patient\DashboardController;
use Illuminate\Support\Facades\Route;


Route::group([
    'middleware'=> ['auth', 'can:patient'],
    'prefix'=> 'patient',
    'as' => 'patient.',
], function() {
    Route::get('/', [DashboardController::class,'index'])->name('dashboard');
    Route::resource('appointments', AppointmentController::class);
});