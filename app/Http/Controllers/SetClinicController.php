<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;

class SetClinicController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate(['active_clinic' => 'required']);
        $clinic = Clinic::findOrFail($validated['active_clinic']);
        Session::put('active_clinic', $clinic);
    }
}
