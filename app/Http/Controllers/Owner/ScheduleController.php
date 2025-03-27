<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Http\Resources\DoctorResource;
use App\Http\Resources\ScheduleResource;
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
        $hours = range(10, 19);
        if(!$user->active_clinic) {
            return Inertia::render('owner/schedule/Index', [
                'doctors' => [],
                'hours' => $hours,
                'appointments' => []
            ]);
        }
        $clinic = $user->clinics()->wherePivot('clinic_id', $user->active_clinic)->first();
        $doctors = $clinic->doctors()->with(['appointments' => function($q) {
            $q->whereBetween('visit_at', [today(), today()->addDay()]);
        }, 'appointments.patient'])->get();
        
        $appointments = [];
        foreach ($doctors as $doctor) {
            foreach($doctor->appointments as $appointment) {
                $appointments[$doctor->user_id][$appointment->visit_hour] = new ScheduleResource($appointment);
            }
        }
        
        foreach($doctors as $doctor) {
            foreach($hours as $hour){
                if(!isset($appointments[$doctor->user_id][$hour])){
                    $appointments[$doctor->user_id][$hour] = null;
                }
            }
        }
        // dd($appointments);
        $doctors = DoctorResource::collection($doctors);
        return Inertia::render('owner/schedule/Index', [
            'doctors' => $doctors,
            'hours' => $hours,
            'appointments' => $appointments
        ]);
    }
    public function getSchedule(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
        ]);
        $user = Auth::user();
        $clinic = $user->clinics()->wherePivot('clinic_id', $user->active_clinic)->first();
        $today =  Carbon::createFromTimestamp('Y-m-d', strtotime($validated['date']));
        $doctors = $clinic->doctors()->with(['appointments' => function($q)use($today) {
            $q->whereBetween('visit_at', [$today, $today->addDay()]);
        }])->get();
        $appointments = [];
        foreach ($doctors as $doctor) {
            foreach($doctor->appointments as $appointment) {
                $appointments[$doctor->user_id][$appointment->visit_hour] = new ScheduleResource($appointment);
            }
        }
        $hours = range(10, 19);
        foreach($doctors as $doctor) {
            foreach($hours as $hour){
                if(!isset($appointments[$doctor->user_id][$hour])){
                    $appointments[$doctor->user_id][$hour] = null;
                }
            }
        }
        $doctors = DoctorResource::collection($doctors);
        return response()->json([
            'doctors' => $doctors,
            'hours' => $hours,
            'appointments' => $appointments
        ]);
    }
}
