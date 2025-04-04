<?php

namespace App\Http\Controllers\Owner;

use App\Http\Resources\DepositResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Http\Controllers\Controller;
use App\Http\Resources\PatientResource;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Validation\ValidationException;

class DepositController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return ['can:has_clinic'];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $deposits = $user->active_clinic->deposits()->with(['patient', 'service'])->paginate();
        return Inertia::render('owner/deposit/Index', [
            'deposits' => DepositResource::collection($deposits),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $validated = request()->validate([
            'patient' => 'nullable',
            'service' => 'nullable',
            'from' => 'nullable'
        ]);
        $user = Auth::user();
        if(isset($validated['patient'])){
            $patient = $user->active_clinic->patients()->find($validated['patient']);
            if($patient){
                $validated['patient'] = new PatientResource($patient);
                if(isset($validated['service'])){
                    $service = $patient->services()->find($validated['service']);
                    if($service){
                        $validated['service'] = new ServiceResource($service);
                    }else{
                        unset($validated['service']);
                    }
                }
            }else{
                unset($validated['patient']);
            }
        }
        return Inertia::render('owner/deposit/Create', $validated);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required',
            'service_id' => 'required',
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|string',
        ]);
        $service = Service::find($validated['service_id']);
        if($validated['amount'] > $service->debt) {
            throw ValidationException::withMessages(['amount' => __('The amount cannot be greater than the amount of the debt')]);
        }
        $user = Auth::user();
        $user->active_clinic->deposits()->create($validated);
        if($request->from == 'services'){
            return to_route('owner.services.index');
        }
        return to_route('owner.deposits.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
