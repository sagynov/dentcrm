<?php

namespace App\Http\Controllers;

use App\Http\Resources\DoctorResource;
use App\Http\Resources\PatientResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function patient(Request $request)
    {
        $validated = $request->validate([
            'query' => 'required|string'
        ]);
        $user = Auth::user();
        $patients = $user->active_clinic->patients()
            ->where(function($query) use ($validated) {
                $query->where(DB::raw('UPPER(first_name)'), 'LIKE','%'. mb_strtoupper($validated['query']) .'%')
                ->orWhere(DB::raw('UPPER(last_name)'),'LIKE','%'. mb_strtoupper($validated['query']) .'%')
                ->orWhere('iin','LIKE', '%'.$validated['query'].'%');
            })->limit(5)->get();
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
        $doctors = $user->active_clinic->doctors()
            ->where(function($query) use ($validated) {
                $query->where(DB::raw('UPPER(first_name)'), 'LIKE','%'. mb_strtoupper($validated['query']) .'%')
                ->orWhere(DB::raw('UPPER(last_name)'),'LIKE','%'. mb_strtoupper($validated['query']) .'%');
        })->get();
        return response()->json([
            'doctors' =>  DoctorResource::collection($doctors)
        ]);
    }
}
