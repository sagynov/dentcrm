<?php

namespace App\Http\Controllers;

use App\Http\Resources\DoctorResource;
use App\Http\Resources\PatientResource;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class SearchController extends Controller
{
    public function patient(Request $request)
    {
        $validated = $request->validate([
            'query' => 'required|string'
        ]);
        $user = Auth::user();
        if(!$user->active_clinic)
        {
            return response()->json([
                'patients' => []
            ]);
        }
        $clinic = $user->clinics()->wherePivot('clinic_id', $user->active_clinic)->first();

        $patients = $clinic->patients()
            ->where(DB::raw('UPPER(first_name)'), 'LIKE','%'. mb_strtoupper($validated['query']) .'%')
            ->orWhere(DB::raw('UPPER(last_name)'),'LIKE','%'. mb_strtoupper($validated['query']) .'%')
            ->orWhere('iin','LIKE', '%'.$validated['query'].'%')->limit(5)->get();
        return response()->json([
            'patients' =>  PatientResource::collection($patients)
        ]);
    }
    public function doctor(Request $request)
    {
        $validated = $request->validate([
            'query' => 'required|string'
        ]);
        $user = Auth::user();
        if(!$user->active_clinic)
        {
            return response()->json([
                'doctors' => []
            ]);
        }
        $clinic = $user->clinics()->wherePivot('clinic_id', $user->active_clinic)->first();

        $doctors = $clinic->doctors()
            ->where(DB::raw('UPPER(first_name)'), 'LIKE','%'. mb_strtoupper($validated['query']) .'%')
            ->orWhere(DB::raw('UPPER(last_name)'),'LIKE','%'. mb_strtoupper($validated['query']) .'%')
            ->get();
        return response()->json([
            'doctors' =>  DoctorResource::collection($doctors)
        ]);
    }
}
