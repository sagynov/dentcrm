<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Http\Resources\AppointmentResource;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AppointmentController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return ['can:has_clinic'];
    }
    public function index()
    {
        $user = Auth::user();
        $appointments = $user->patient->appointments()->with('doctor')->paginate();
        return Inertia::render('patient/appointment/Index', [
            'appointments' => AppointmentResource::collection($appointments),
        ]);
    }
}
