<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Resources\AppointmentResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\Appointment;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class AppointmentController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return ['can:has_clinic'];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $appointments = $user->doctor->appointments()->with('patient')->paginate();
        return Inertia::render('doctor/appointment/Index', [
            'appointments' => AppointmentResource::collection($appointments),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $validated = request()->validate([
            'date' => 'nullable|date',
            'time' => 'nullable|date_format:H:i',
            'from' => 'nullable'
        ]);
        return Inertia::render('doctor/appointment/Create', $validated);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|numeric',
            'visit_date' => 'required|date',
            'visit_time' => 'required|date_format:H:i',
            'notes' => 'nullable|string'
        ]);
        $user = Auth::user();
        $validated['doctor_id'] = $user->id;
        $validated['clinic_id'] = $user->active_clinic->id;
        $visit_date = date('Y-m-d', strtotime($validated['visit_date']));
        $validated['visit_at'] = date('Y-m-d H:i:s', strtotime($visit_date.' '.$validated['visit_time']));
        $validated['status'] = 'scheduled';
        unset($validated['visit_date'], $validated['visit_time']);
        Appointment::create($validated);
        if($request->from == 'schedule'){
            return to_route('doctor.schedule.index');
        }
        return to_route('doctor.appointments.index');
    }
    public function cancel(Appointment $appointment)
    {
        $user = Auth::user();
        $user->doctor->appointments()->where('id', $appointment->id)->update(['status' => 'canceled']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
