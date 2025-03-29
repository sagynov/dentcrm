<?php

namespace App\Http\Controllers\Owner;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Controllers\Controller;
use App\Http\Resources\DoctorResource;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class DoctorController extends Controller
{
    public function index()
    {
        Gate::authorize('viewAny', Doctor::class);
        $user = Auth::user();
        if(!$user->active_clinic) {
            return Inertia::render('owner/doctor/Index', [
                'doctors' => []
            ]);
        }
        $clinic = $user->clinics()->wherePivot('clinic_id', $user->active_clinic)->first();
        $doctors = DoctorResource::collection($clinic->doctors);
        return Inertia::render('owner/doctor/Index', [
            'doctors' => $doctors
        ]);
    }
    public function store(Request $request)
    {
        Gate::authorize('create', Doctor::class);
        $validated = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'speciality' => 'required',
            'phone' => 'required|string',
        ]);
        if(!Auth::user()->active_clinic) {
            abort(403);
        }
        $clinic = $request->user()->clinics()->where('clinic_id', $request->user()->active_clinic)->first();
        $check_user = User::where('phone', $validated['phone'])->first();
        if($check_user) {
            if($check_user->is_doctor){
                $clinic->users()->syncWithoutDetaching($check_user->id);
            }else{
                throw ValidationException::withMessages(['phone' => __('This number is already registered')]);
            }
        }else{
            $new_user = User::create([
                'name' => $validated['first_name'],
                'role' => 'doctor',
                'phone' => $validated['phone'],
                'password' => Hash::make(Str::random(10)),
                'remember_token' => Str::random(40)
            ]);
            $new_user->doctor()->create($request->only('first_name', 'last_name', 'speciality'));
            $clinic->users()->syncWithoutDetaching($new_user->id);
        }

    }
}
