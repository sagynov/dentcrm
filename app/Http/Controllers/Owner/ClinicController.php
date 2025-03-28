<?php

namespace App\Http\Controllers\Owner;

use App\Models\Clinic;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;

class ClinicController extends Controller
{
    public function setActiveClinic(Request $request)
    {
        Gate::authorize('viewAny', Clinic::class);
        $validated = $request->validate(['active_clinic' => 'required']);
        $clinic = Clinic::findOrFail($validated['active_clinic']);
        Gate::authorize('update', $clinic);
        Session::put('active_clinic', $clinic->id);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('viewAny', Clinic::class);
        $clinics = Auth::user()->clinics;
        return Inertia::render('owner/clinic/Index', [
            'clinics' => $clinics
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
