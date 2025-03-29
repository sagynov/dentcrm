<?php

namespace App\Http\Controllers\Owner;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Http\Resources\PatientRecordResource;
use App\Http\Resources\PatientResource;
use App\Models\Patient;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PatientController extends Controller
{
    public function index()
    {
        Gate::authorize('viewAny', Patient::class);
        $user = Auth::user();
        if(!$user->active_clinic) {
            return Inertia::render('owner/patient/Index', [
                'patients' => []
            ]);
        }
        $clinic = $user->clinics()->wherePivot('clinic_id', $user->active_clinic)->first();
        return Inertia::render('owner/patient/Index', [
            'patients' => PatientResource::collection($clinic->patients)
        ]);
    }
    public function store(Request $request)
    {
        Gate::authorize('create', Patient::class);
        $validated = $request->validate([
            'iin' => 'required',
            'first_name' => 'required',
            'last_name' => 'nullable',
            'phone' => 'required|string',
            'birth_date' => 'required|date_format:d-m-Y'
        ]);
        $clinic = Auth::user()->clinics()->where('clinic_id', Auth::user()->active_clinic)->first();
        $check_phone = User::where('phone', $validated['phone'])->first();
        $check_iin = Patient::where('iin', $validated['iin'])->first();
        if($check_phone) {
            if($check_phone->is_patient){
                $clinic->users()->syncWithoutDetaching($check_phone->id);
            }else{
                throw ValidationException::withMessages(['phone' => __('This number is already registered')]);
            }
        }
        elseif($check_iin){
            $clinic->users()->syncWithoutDetaching($check_iin->user_id);
        }
        else{
            $new_user = User::create([
                'name' => $validated['first_name'],
                'role' => 'patient',
                'phone' => $validated['phone'],
                'password' => Hash::make(Str::random(10)),
                'remember_token' => Str::random(40)
            ]);
            $birthdate = Carbon::createFromFormat('d-m-Y', $validated['birth_date']);
            $request->merge(['birth_date' => $birthdate->format('Y-m-d')]);
            // dd($birthdate->format('Y-m-d'));
            $new_user->patient()->create($request->only('iin', 'first_name', 'last_name', 'birth_date'));
            $clinic = $request->user()->clinics()->where('clinic_id', $request->user()->active_clinic)->first();
            $clinic->users()->syncWithoutDetaching($new_user->id);
        }
    }
    public function show(Patient $patient)
    {
        Gate::authorize('view', $patient);
        $records = $patient->records()->with(['doctor', 'clinic'])->paginate(5);
        return Inertia::render('owner/patient/Show', [
            'patient' => new PatientResource($patient),
            'records' => PatientRecordResource::collection($records),
        ]);
    }
}
