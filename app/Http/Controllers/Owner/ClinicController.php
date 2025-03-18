<?php

namespace App\Http\Controllers\Owner;

use App\Models\Clinic;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;

class ClinicController extends Controller
{
    public function setActiveClinic(Request $request)
    {
        $validated = $request->validate(['active_clinic' => 'required']);
        Session::put('active_clinic', $validated['active_clinic']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Clinic $clinic)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Clinic $clinic)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Clinic $clinic)
    {
        //
    }
}
