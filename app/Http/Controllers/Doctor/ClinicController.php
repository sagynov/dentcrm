<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Resources\ClinicServiceResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClinicController extends Controller
{
    public function getServices()
    {
        $user = Auth::user();
        $services = $user->active_clinic->clinic_services()->get();
        return response()->json([
            'services' => ClinicServiceResource::collection($services),
        ]);
    }
}
