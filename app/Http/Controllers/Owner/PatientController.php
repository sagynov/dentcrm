<?php

namespace App\Http\Controllers\Owner;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Http\Resources\PatientResource;
use App\Models\User;
use Illuminate\Support\Str;

class PatientController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $clinic = $user->clinics()->wherePivot('clinic_id', $user->active_clinic)->first();
        $patients = PatientResource::collection($clinic->users()->where('role', 'patient')->with('patient')->get());
        return Inertia::render('owner/patient/Index', [
            'patients' => $patients
        ]);
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'iin' => 'required',
            'first_name' => 'required',
            'last_name' => 'nullable',
            'phone' => 'required|string|unique:'.User::class,
            'birth_date' => 'required|date_format:d-m-Y'
        ]);
        $new_user = User::create([
            'name' => $validated['first_name'],
            'role' => 'patient',
            'phone' => $validated['phone'],
            'password' => Hash::make(Str::random(10)),
            'remember_token' => Str::random(40)
        ]);
        $new_user->patient()->create($request->only('iin', 'first_name', 'last_name', 'birth_date'));
        $clinic = $request->user()->clinics()->where('clinic_id', $request->user()->active_clinic)->first();
        $clinic->users()->syncWithoutDetaching($new_user->id);
    }
}
