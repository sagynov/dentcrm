<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Http\Resources\DoctorResource;
use App\Http\Resources\ScheduleResource;
use Carbon\CarbonPeriod;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ScheduleController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return ['can:has_clinic'];
    }
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
        $datetime = [];
        $now = [];
        foreach($carbon_periods as $carbon_period) {
            $periods[] = $carbon_period->format('H:i');
            $hours[] = $carbon_period->format('H');
            $datetime[$carbon_period->format('H')]['date'] = $carbon_period->format('Y-m-d');
            $datetime[$carbon_period->format('H')]['time'] = $carbon_period->format('H:i');
            $now[$carbon_period->format('H')] = $carbon_period->translatedFormat('j F, H:i');
        }
        $user = Auth::user();
        $query = ['appointments' => function($q)use($start_time, $end_time) {
            $q->whereBetween('visit_at', [$start_time, $end_time])->orderBy('visit_at', 'asc');
        }];
        $doctors = $user->active_clinic->doctors()->with([...$query, 'appointments.patient'])->withCount($query)->orderBy('appointments_count', 'desc')->get();
        $appointments = [];
        foreach ($doctors as $doctor) {
            foreach($doctor->appointments as $appointment) {
                $appointments[$doctor->user_id][$appointment->visit_at->format('H')][] = new ScheduleResource($appointment);
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
            'appointments' => $appointments,
            'datetime' => $datetime,
            'now' => $now
        ];
    }
}
