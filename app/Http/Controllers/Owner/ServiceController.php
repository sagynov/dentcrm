<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Http\Resources\ClinicServiceResource;
use App\Http\Resources\ServiceResource;
use App\Models\Clinic;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clinic = Clinic::find(Auth::user()->active_clinic);
        $services = $clinic->services()->with('patient', 'doctor')->paginate();
        $clinic_services = $clinic->clinic_services;
        return Inertia::render('owner/services/Index', [
            'services' => ServiceResource::collection($services),
            'clinic_services' => ClinicServiceResource::collection($clinic_services),
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
        Gate::authorize('create', Service::class);
        $validated = $request->validate([
            'patient_id' => 'required',
            'doctor_id' => 'required',
            'clinic_service_id' => 'required',
            'name' => 'required|string',
            'price' => 'required',
            'description' => 'nullable|string'
        ]);
        $user = Auth::user();
        $clinic = $user->clinics()->wherePivot('clinic_id', $user->active_clinic)->first();
        $clinic->services()->create($validated);
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
