<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Http\Resources\AppointmentResource;
use App\Http\Resources\DoctorResource;
use App\Models\Appointment;
use Illuminate\Support\Arr;
use App\Models\Service;
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
            $appointments = $clinic->appointments()->with('patient', 'doctor', 'service')->paginate();

            return Inertia::render('owner/appointment/Index', [
                'appointments' => AppointmentResource::collection($appointments),
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
        $user = Auth::user();
        if(!$user->active_clinic){
            abort(403);
        }
        if(isset($validated['doctor'])){
            $clinic = $user->clinics()->wherePivot('clinic_id', $user->active_clinic)->first();
            $doctor = $clinic->doctors()->find($validated['doctor']);
            if($doctor){
                $validated['doctor'] = new DoctorResource($doctor);
            }else{
                unset($validated['doctor']);
            }
        }
        return Inertia::render('owner/appointment/Create', $validated);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('create', Appointment::class);
        $validated = $request->validate([
            'patient_id' => 'required|numeric',
            'doctor_id' => 'required|numeric',
            'visit_date' => 'required|date',
            'visit_time' => 'required|date_format:H:i',
            'notes' => 'nullable|string',
            'service_id' => 'required|numeric',
            'clinic_service_id' => 'nullable|numeric',
            'service_name' => 'nullable|string',
            'service_price' => 'nullable|numeric',
            'service_description' => 'nullable|string'
        ]);
        if(!Auth::user()->active_clinic) {
            abort(403);
        }
        $validated['clinic_id'] = Auth::user()->active_clinic;
        $visit_date = date('Y-m-d', strtotime($validated['visit_date']));
        $validated['visit_at'] = date('Y-m-d H:i:s', strtotime($visit_date.' '.$validated['visit_time']));
        $validated['status'] = 'scheduled';
        unset($validated['visit_date'], $validated['visit_time']);
        if($validated['service_id'] == '0') {
            $validated['service_id'] = $this->create_service($validated);
        }
        Appointment::create(Arr::only($validated, ['clinic_id', 'patient_id', 'service_id', 'doctor_id', 'notes', 'visit_at', 'status']));
        if($request->from == 'schedule'){
            return to_route('owner.schedule.index');
        }
        return to_route('owner.appointments.index');
    }
    public function create_service($data)
    {
        return Service::create([
            'clinic_id' => $data['clinic_id'],
            'patient_id' => $data['patient_id'],
            'doctor_id' => $data['doctor_id'],
            'clinic_service_id' => $data['clinic_service_id'],
            'name' => $data['service_name'],
            'price' => $data['service_price'],
            'description' => $data['service_description']
        ]);
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
