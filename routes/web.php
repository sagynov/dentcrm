<?php

use App\Http\Controllers\SetLocaleController;
use App\Http\Controllers\TaxpayerController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

Route::middleware('auth')->get('/find-by-iin/{iin}', [TaxpayerController::class, 'findByIIN'])->name('find-by-iin'); 

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
require __DIR__.'/owner.php';
require __DIR__.'/doctor.php';
