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
        // if (Auth::user()->cannot('viewAny', Clinic::class)) {
        //     abort(403);
        // }
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
        if (Auth::user()->cannot('create', Clinic::class)) {
            abort(403);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // if (Auth::user()->cannot('create', Clinic::class)) {
        //     abort(403);
        // }
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
        if (Auth::user()->cannot('view', $clinic)) {
            abort(403);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Clinic $clinic)
    {
        if (Auth::user()->cannot('update', $clinic)) {
            abort(403);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Clinic $clinic)
    {
        if (Auth::user()->cannot('update', $clinic)) {
            abort(403);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Clinic $clinic)
    {
        if (Auth::user()->cannot('delete', $clinic)) {
            abort(403);
        }
    }
}
