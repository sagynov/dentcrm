<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Http\Resources\DoctorResource;
use App\Models\Doctor;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;

class ScheduleController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $clinic = $user->clinics()->wherePivot('clinic_id', $user->active_clinic)->first();
        $doctors = $clinic->doctors()->with(['appointments' => function($q) {
            $q->whereBetween('visit_at', [today()->format('d-m-Y H:i'), today()->addDay()->format('d-m-Y H:i')]);
        }])->get();
        // dd($doctors);
        $doctors = DoctorResource::collection($doctors);
        $hours = range(10, 19);
        // dd($hours);
        return Inertia::render('owner/schedule/Index', [
            'doctors' => $doctors,
            'hours' => $hours
        ]);
    }
    public function getSchedule(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
        ]);
        $user = Auth::user();
        $clinic = $user->clinics()->wherePivot('clinic_id', $user->active_clinic)->first();
        $date = date('d-m-Y', strtotime($validated['date']));
        $today =  Carbon::createFromFormat('d-m-Y', $date);
        $doctors = $clinic->doctors()->with(['appointments' => function($q)use($today) {
            $q->whereBetween('visit_at', [$today->format('d-m-Y'), $today->addDay()->format('d-m-Y')]);
        }])->get();
        $doctors = DoctorResource::collection($doctors);
        return response()->json([
            'doctors' => $doctors,
            'hours' => range(10, 19)
        ]);
    }
}
