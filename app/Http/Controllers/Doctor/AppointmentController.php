<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Resources\AppointmentResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\Appointment;
use App\Models\Service;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Arr;

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
        $appointments = $user->doctor->appointments()->with(['patient', 'service'])->paginate();
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
            'notes' => 'nullable|string',
            'service_id' => 'required|numeric',
            'clinic_service_id' => 'nullable|numeric',
            'service_name' => 'nullable|string',
            'service_price' => 'nullable|numeric',
            'service_description' => 'nullable|string'
        ]);
        $user = Auth::user();
        $validated['doctor_id'] = $user->id;
        $validated['clinic_id'] = $user->active_clinic->id;
        $visit_date = date('Y-m-d', strtotime($validated['visit_date']));
        $validated['visit_at'] = date('Y-m-d H:i:s', strtotime($visit_date.' '.$validated['visit_time']));
        $validated['status'] = 'scheduled';
        unset($validated['visit_date'], $validated['visit_time']);
        if($validated['service_id'] == '0') {
            $validated['service_id'] = $this->create_service($validated);
        }
        Appointment::create(Arr::only($validated, ['clinic_id', 'patient_id', 'service_id', 'doctor_id', 'notes', 'visit_at', 'status']));
        if($request->from == 'schedule'){
            return to_route('doctor.schedule.index');
        }
        return to_route('doctor.appointments.index');
    }
    public function create_service($data)
    {
        $service = Service::create([
            'clinic_id' => $data['clinic_id'],
            'patient_id' => $data['patient_id'],
            'doctor_id' => $data['doctor_id'],
            'clinic_service_id' => $data['clinic_service_id'],
            'name' => $data['service_name'],
            'price' => $data['service_price'],
            'description' => $data['service_description'],
            'status' => 'open'
        ]);
        return $service->id;
    }

    public function cancel(Appointment $appointment)
    {
        $user = Auth::user();
        if($user->doctor->appointments()->where([['id', '=', $appointment->id], ['status', '=', 'scheduled']])) {
            $appointment->update(['status' => 'canceled']);
        }
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
