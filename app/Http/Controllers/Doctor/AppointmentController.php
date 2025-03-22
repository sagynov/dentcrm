<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Resources\AppointmentResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Http\Resources\PatientResource;
use Illuminate\Support\Facades\DB;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $clinic = $user->clinics()->wherePivot('clinic_id', $user->active_clinic)->first();
        $appointments = AppointmentResource::collection($user->doctor->appointments()->with('patient')->get());
        $patients = PatientResource::collection($clinic->patients()->orderBy('pivot_created_at', 'desc')->limit(3)->get());
        return Inertia::render('doctor/appointment/Index', [
            'appointments' => $appointments,
            'patients' => [],
        ]);
    }
    public function searchPatient(Request $request)
    {
        $validated = $request->validate([
            'query' => 'required|string'
        ]);
        $user = Auth::user();
        $clinic = $user->clinics()->wherePivot('clinic_id', $user->active_clinic)->first();

        $patients = $clinic->patients()
            ->where(DB::raw('UPPER(first_name)'), 'LIKE','%'. mb_strtoupper($validated['query']) .'%')
            ->orWhere(DB::raw('UPPER(last_name)'),'LIKE','%'. mb_strtoupper($validated['query']) .'%')
            ->orWhere('iin','LIKE', '%'.$validated['query'].'%')->limit(5)->get();
        return response()->json([
            'patients' =>  PatientResource::collection($patients)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
