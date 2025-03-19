<?php

use App\Http\Controllers\TaxpayerController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::middleware('auth')->get('/find-by-iin/{iin}', [TaxpayerController::class, 'findByIIN'])->name('find-by-iin'); 

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
require __DIR__.'/owner.php';
