<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Http\Resources\DoctorResource;
use App\Http\Resources\ScheduleResource;
use App\Models\Doctor;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;

class ScheduleController extends Controller
{
    public function index()
    {
        $start_time = today()->addHours(10);
        $end_time = today()->addHours(20);
        $data = $this->getByTime($start_time, $end_time);
        return Inertia::render('owner/schedule/Index', $data);
    }
    public function getSchedule(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
        ]);
        $today =  date('Y-m-d', strtotime($validated['date']));
        $today = new DateTime($today);
        $data = $this->getByTime(date_format($today->modify('+ 10 hour'), 'Y-m-d H:i:s'), date_format($today->modify('+ 10 hour'), 'Y-m-d H:i:s'));
        return response()->json($data);
    }
    public function getByTime($start_time, $end_time) 
    {
        $carbon_periods = CarbonPeriod::create($start_time, '1 hour', $end_time);
        $periods = [];
        $hours = [];
        foreach($carbon_periods as $carbon_period) {
            $periods[] = $carbon_period->format('H:i');
            $hours[] = $carbon_period->format('H');
        }
        $user = Auth::user();
        if(!$user->active_clinic) {
            return [
                'doctors' => [],
                'periods' => $periods,
                'hours' => $hours,
                'appointments' => []
            ];
        }

        $clinic = $user->clinics()->wherePivot('clinic_id', $user->active_clinic)->first();
        $doctors = $clinic->doctors()->with(['appointments' => function($q)use($start_time, $end_time) {
            $q->whereBetween('visit_at', [$start_time, $end_time]);
        }, 'appointments.patient'])->get();
        
        $appointments = [];
        foreach ($doctors as $doctor) {
            foreach($doctor->appointments as $appointment) {
                $appointments[$doctor->user_id][$appointment->visit_at->format('H')] = new ScheduleResource($appointment);
            }
        }
        
        foreach($doctors as $doctor) {
            foreach($hours as $hour){
                if(!isset($appointments[$doctor->user_id][$hour])){
                    $appointments[$doctor->user_id][$hour] = null;
                }
            }
        }
        $doctors = DoctorResource::collection($doctors);
        return [
            'doctors' => $doctors,
            'periods' => $periods,
            'hours' => $hours,
            'appointments' => $appointments
        ];
    }
}
