<?php

use App\Http\Controllers\Owner\PaymentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('halykBank/postProcess', [PaymentController::class, 'halykProcess']);



