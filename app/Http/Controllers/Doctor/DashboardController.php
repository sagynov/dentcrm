<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        return Inertia::render('doctor/dashboard/Index');
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
        $appointments = $user->doctor->appointments()->where('clinic_id', $user->active_clinic->id)
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
}
