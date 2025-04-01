<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Http\Resources\AppointmentResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AppointmentController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if(!$user->active_clinic){
            return Inertia::render('patient/appointment/Index', [
                'appointments' => [],
            ]);
        }
        $appointments = $user->patient->appointments()->with('doctor')->paginate();
        return Inertia::render('patient/appointment/Index', [
            'appointments' => AppointmentResource::collection($appointments),
            'patients' => [],
        ]);
    }
}
