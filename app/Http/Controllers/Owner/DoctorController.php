<?php

namespace App\Http\Controllers\Owner;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Controllers\Controller;
use App\Http\Resources\DoctorResource;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class DoctorController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return ['can:has_clinic'];
    }
    public function index()
    {
        Gate::authorize('viewAny', Doctor::class);
        $user = Auth::user();
        $doctors = $user->active_clinic->doctors()->orderByPivot('created_at','desc')->paginate();
        return Inertia::render('owner/doctor/Index', [
            'doctors' => DoctorResource::collection($doctors)
        ]);
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'speciality' => 'required',
            'phone' => 'required|phone',
        ]);
        $user = Auth::user();
        $check_user = User::where('phone', $validated['phone'])->first();
        if($check_user) {
            if($check_user->is_doctor){
                $user->active_clinic->users()->syncWithoutDetaching($check_user->id);
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
            $user->active_clinic->users()->syncWithoutDetaching($new_user->id);
        }

    }
}
