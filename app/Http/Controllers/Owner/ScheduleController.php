<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Http\Resources\DoctorResource;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ScheduleController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $clinic = $user->clinics()->wherePivot('clinic_id', $user->active_clinic)->first();
        $doctors = $clinic->doctors()->with('appointments')->get();
        // dd($doctors);
        $doctors = DoctorResource::collection($doctors);
        $hours = range(10, 19);
        // dd($hours);
        return Inertia::render('owner/schedule/Index', [
            'doctors' => $doctors,
            'hours' => $hours
        ]);
    }
}
