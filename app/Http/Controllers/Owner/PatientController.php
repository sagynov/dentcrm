<?php

namespace App\Http\Controllers\Owner;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Controllers\Controller;

class PatientController extends Controller
{
    public function index()
    {
        return Inertia::render('patient/Index');
    }
}
