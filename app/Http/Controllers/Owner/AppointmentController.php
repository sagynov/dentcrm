<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Http\Resources\AppointmentResource;
use App\Http\Resources\DoctorResource;
use App\Http\Resources\PatientResource;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('viewAny', Appointment::class);
        $user = Auth::user();
        $clinic = $user->clinics()->wherePivot('clinic_id', $user->active_clinic)->first();
        if($clinic){
            $appointments = $clinic->appointments()->with('patient', 'doctor')->paginate();
            $patients = PatientResource::collection($clinic->patients);
            $doctors = DoctorResource::collection($clinic->doctors);

            return Inertia::render('owner/appointment/Index', [
                'appointments' => AppointmentResource::collection($appointments),
                'patients' => $patients,
                'doctors' => $doctors
            ]);
        }
        return Inertia::render('owner/appointment/Index', [
            'appointments' => [],
            'patients' => [],
            'doctors' => []
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
            'doctor' => 'nullable',
            'from' => 'nullable'
        ]);
        if(isset($validated['doctor'])){
            $doctor = Doctor::find($validated['doctor']);
            $validated['doctor'] = new DoctorResource($doctor);
        }
        return Inertia::render('owner/appointment/Create', $validated);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('create', Patient::class);
        $validated = $request->validate([
            'patient_id' => 'required|numeric',
            'doctor_id' => 'required|numeric',
            'visit_date' => 'required|date',
            'visit_time' => 'required|date_format:H:i',
            'notes' => 'nullable|string'
        ]);
        $validated['clinic_id'] = Auth::user()->active_clinic;
        $visit_date = date('Y-m-d', strtotime($validated['visit_date']));
        $validated['visit_at'] = date('Y-m-d H:i:s', strtotime($visit_date.' '.$validated['visit_time']));
        $validated['status'] = 'scheduled';
        unset($validated['visit_date'], $validated['visit_time']);
        Appointment::create($validated);
        if($request->from == 'schedule'){
            return to_route('owner.schedule.index');
        }
        return to_route('owner.appointments.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Appointment $appointment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Appointment $appointment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Appointment $appointment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appointment $appointment)
    {
        //
    }
}
