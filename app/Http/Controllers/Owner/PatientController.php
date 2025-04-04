<?php

namespace App\Http\Controllers\Owner;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Http\Resources\AppointmentResource;
use App\Http\Resources\DepositResource;
use App\Http\Resources\PatientRecordResource;
use App\Http\Resources\PatientResource;
use App\Http\Resources\ServiceResource;
use App\Models\Patient;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PatientController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return ['can:has_clinic'];
    }
    public function index()
    {
        $user = Auth::user();
        $patients = $user->active_clinic->patients()->orderByPivot('created_at', 'desc')->paginate();
        return Inertia::render('owner/patient/Index', [
            'patients' => PatientResource::collection($patients)
        ]);
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'iin' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required|phone',
            'birth_date' => 'required|date_format:d-m-Y'
        ]);
        $user = Auth::user();
        $check_phone = User::where('phone', $validated['phone'])->first();
        $check_iin = Patient::where('iin', $validated['iin'])->first();
        if($check_iin){
            $user->active_clinic->users()->syncWithoutDetaching($check_iin->user_id);
        }
        elseif($check_phone) {
            if($check_phone->is_patient){
                $user->active_clinic->users()->syncWithoutDetaching($check_phone->id);
            }else{
                throw ValidationException::withMessages(['phone' => __('This number is already registered')]);
            }
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
            $new_user->patient()->create($request->only('iin', 'first_name', 'last_name', 'birth_date'));
            $user->active_clinic->users()->syncWithoutDetaching($new_user->id);
        }
    }
    public function show(Patient $patient)
    {
        Gate::authorize('view', $patient);
        $records = $patient->records()->with(['doctor', 'clinic'])->paginate();
        return Inertia::render('owner/patient/Show', [
            'patient' => new PatientResource($patient->load('user')),
            'records' => PatientRecordResource::collection($records),
        ]);
    }
    public function appointments(Patient $patient)
    {
        Gate::authorize('view', $patient);
        $appointments = $patient->appointments()->with(['doctor', 'patient', 'service'])->paginate();
        return Inertia::render('owner/patient/Show', [
            'patient' => new PatientResource($patient->load('user')),
            'appointments' => AppointmentResource::collection($appointments),
        ]);
    }
    public function services(Patient $patient)
    {
        Gate::authorize('view', $patient);
        $services = $patient->services()->with(['doctor', 'patient', 'deposits'])->paginate();
        return Inertia::render('owner/patient/Show', [
            'patient' => new PatientResource($patient->load('user')),
            'services' => ServiceResource::collection($services),
        ]);
    }
    public function deposits(Patient $patient)
    {
        Gate::authorize('view', $patient);
        $deposits = $patient->deposits()->with(['patient', 'service'])->paginate();
        return Inertia::render('owner/patient/Show', [
            'patient' => new PatientResource($patient->load('user')),
            'deposits' => DepositResource::collection($deposits),
        ]);
    }
    public function getServices(Patient $patient)
    {
        Gate::authorize('view', $patient);
        $services = $patient->services()->where('status', 'open')->with(['doctor', 'deposits'])->get();
        return response()->json([
            'services' => ServiceResource::collection($services),
        ]);
    }
    public function getAllServices(Patient $patient)
    {
        Gate::authorize('view', $patient);
        $services = $patient->services()->with(['doctor', 'deposits'])->get();
        return response()->json([
            'services' => ServiceResource::collection($services),
        ]);
    }
}
