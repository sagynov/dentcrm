<?php

namespace App\Http\Controllers\Owner;

use App\Models\Clinic;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ClinicResource;
use App\Http\Resources\ClinicServiceResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class ClinicController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('viewAny', Clinic::class);
        $clinics = Auth::user()->clinics()->paginate();
        return Inertia::render('owner/clinic/Index', [
            'clinics' => ClinicResource::collection($clinics),
        ]);
    }
    public function getServices()
    {
        $user = Auth::user();
        $clinic = $user->clinics()->wherePivot('clinic_id', $user->active_clinic)->first();
        $services = $clinic->clinic_services()->get();
        return response()->json([
            'services' => ClinicServiceResource::collection($services),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('create', Clinic::class);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('create', Clinic::class);
        $validated = $request->validate([
            'name' => 'required|string',
            'specialization' => 'required|string',
            'address' => 'required|string',
            'phone' => 'required',
            'email' => 'email|nullable',
            'website' => 'nullable|string'
        ]);
        $clinic = Clinic::create($validated);
        Auth::user()->clinics()->syncWithoutDetaching($clinic);
    }

    /**
     * Display the specified resource.
     */
    public function show(Clinic $clinic)
    {
        Gate::authorize('view', $clinic);
        $services = $clinic->clinic_services()->paginate();
        return Inertia::render('owner/clinic/Show', [
            'clinic' => new ClinicResource($clinic),
            'services' => ClinicServiceResource::collection($services),
        ]);
    }

    public function addService(Request $request, Clinic $clinic)
    {
        Gate::authorize('update', $clinic);
        $validated = $request->validate([
            'name' => 'required|string',
            'base_price' => 'required',
            'description' => 'nullable|string'
        ]);
        $clinic->clinic_services()->create($validated);
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Clinic $clinic)
    {
        Gate::authorize('update', $clinic);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Clinic $clinic)
    {
        Gate::authorize('update', $clinic);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Clinic $clinic)
    {
        Gate::authorize('delete', $clinic);
    }
}
