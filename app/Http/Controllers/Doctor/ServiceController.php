<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Resources\ClinicServiceResource;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ServiceController extends Controller implements HasMiddleware
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
        $services = $user->doctor->services()->with(['patient', 'doctor', 'deposits'])->paginate();
        $clinic_services = $user->active_clinic->clinic_services;
        return Inertia::render('doctor/service/Index', [
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
        $validated = $request->validate([
            'patient_id' => 'required',
            'clinic_service_id' => 'required',
            'name' => 'required|string',
            'price' => 'required',
            'description' => 'nullable|string'
        ]);
        $user = Auth::user();
        $validated['clinic_id'] = $user->active_clinic->id;
        $user->doctor->services()->create($validated);
    }
    public function close(Service $service)
    {
        $user = Auth::user();
        if($user->active_clinic->services()->where([['id', '=', $service->id], ['status', '=', 'open']])->exists()) {
            $service->update([
                'status' => 'closed',
                'closed_at' => now()
            ]);
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
