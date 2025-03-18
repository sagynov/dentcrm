<?php

namespace App\Http\Controllers\Owner;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Controllers\Controller;

class DoctorController extends Controller
{
    public function index()
    {
        return Inertia::render('admin/doctor/Index');
    }
}
