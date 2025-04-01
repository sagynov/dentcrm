<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Gate;

class DashboardController extends Controller
{
    public function index()
    {
        return Inertia::render('owner/dashboard/Index');
    }
    public function appointmentMetric(Request $request)
    {
        $request->validate([
            'period' => 'required|string',
        ]);
        $start_date =  today()->subDays(7);
        $end_date = today()->endOfDay();
        if($request->period == 'month')
        {
            $start_date =  today()->subMonth();
            $end_date = today()->endOfDay();
        }
        $user = Auth::user();
        if(!$user->active_clinic){
            return response()->json([
                'data_keys' => [],
                'data_values' => [],
                'total' => []
            ]);
        }
        $appointments = Appointment::select('id', 'visit_at')->where('clinic_id', $user->active_clinic)
            ->whereBetween('visit_at', [
                $start_date, 
                $end_date
            ])->get();
        $period = CarbonPeriod::create($start_date, '1 day', $end_date);
        $days  = [];
        foreach ($period as $date) {
            $days[$date->translatedFormat('d F')] = 0;
            foreach ($appointments as $appointment) {
                if($appointment->visit_at->format('Y-m-d') == $date->format('Y-m-d'))
                {
                    $days[$date->translatedFormat('d F')] += 1;
                }
            }
        }
        return response()->json([
            'data_keys' => array_keys($days),
            'data_values' => array_values($days),
            'total' => $appointments->count()
        ]);
    }
    public function doctorWorkload(Request $request)
    {
        $request->validate([
            'period' => 'required|string',
        ]);
        $start_date =  today()->subDays(7);
        $end_date = today()->endOfDay();
        if($request->period == 'month')
        {
            $start_date =  today()->subMonth();
            $end_date = today()->endOfDay();
        }
        $user = Auth::user();
        if(!$user->active_clinic){
            return response()->json([
                'data_keys' => [],
                'data_values' => [],
                'total' => []
            ]);
        }
        $clinic = $user->clinics()->wherePivot('clinic_id', $user->active_clinic)->first();
        $doctors = $clinic->doctors()->with(['appointments' => function($q)use($start_date, $end_date) {
            $q->whereBetween('visit_at', [$start_date, $end_date]);
        }])->withCount('appointments')->orderBy('appointments_count', 'desc')->limit(15)->get();
        $period = CarbonPeriod::create($start_date, '1 day', $end_date);
        $days  = [];
        foreach ($doctors as $doctor) {
            $days[$doctor->full_name] = 0;
            foreach($doctor->appointments as $appointment){
                foreach ($period as $date) {
                    if($appointment->visit_at->format('Y-m-d') == $date->format('Y-m-d'))
                    {
                        $days[$doctor->full_name] += 1;
                    }
                }
            }
        }
        return response()->json([
            'data_keys' => array_keys($days),
            'data_values' => array_values($days)
        ]);
    }
}
