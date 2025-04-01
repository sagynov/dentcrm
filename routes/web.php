<?php

use App\Http\Controllers\SetLocaleController;
use App\Http\Controllers\TaxpayerController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SetClinicController;

Route::get('/', function () {
    if(Auth::check()) {
        if(Auth::user()->role == 'owner') {
            return redirect('/owner');
        }elseif(Auth::user()->role == 'doctor') {
            return redirect('/doctor');
        }elseif(Auth::user()->role == 'patient') {
            return redirect('/patient');
        }
    }
    return redirect()->route('login');
})->name('home');

Route::get('set-locale/{locale}', [SetLocaleController::class, 'setLocale'])->name('set-locale');

Route::middleware('auth')->group(function () {
    Route::get('download-attachment/{patient}', [DownloadController::class, 'index'])->name('download');
    Route::get('search-patient', [SearchController::class, 'patient'])->name('search-patient');
    Route::get('search-doctor', [SearchController::class, 'doctor'])->name('search-doctor');
    Route::get('find-by-iin/{iin}', [TaxpayerController::class, 'findByIIN'])->name('find-by-iin'); 
    Route::post('set-active-clinic', [SetClinicController::class,'index'])->name('set-active-clinic');
});


require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
require __DIR__.'/owner.php';
require __DIR__.'/doctor.php';
require __DIR__.'/patient.php';
