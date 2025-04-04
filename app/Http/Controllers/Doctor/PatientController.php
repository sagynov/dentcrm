<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Resources\PatientRecordResource;
use App\Http\Resources\PatientResource;
use App\Models\Patient;
use App\Models\PatientRecord;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Illuminate\Support\Number;

class PatientController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return ['can:has_clinic'];
    }
    public function uploadRecordAttachment(Request $request, Patient $patient)
    {
        Gate::authorize('create', PatientRecord::class);
        $validated = $request->validate([
            'attachments.*' => 'nullable|file|mimes:jpg,png,pdf'
        ]);
        foreach($request->file('attachments') as $file) {
            $name = $file->getClientOriginalName();
            $slug = Str::slug($name);
            $attachments[] = [
                'name' => $name,
                'path' => $file->store('tmp'),
            ];
        }
        return response()->json([
            'files' => $attachments
        ]);
    }
    public function addRecord(Request $request, Patient $patient)
    {
        Gate::authorize('create', PatientRecord::class);
        $validated = $request->validate([
            'comment' => 'required',
            'attachments' => 'nullable|json'
        ]);
        if($request->has('attachments')){
            $attachments = [];
            $tmp_files = json_decode($validated['attachments'], true);
            foreach($tmp_files as $tmp_file) {
                $path = 'patient/record/'.basename($tmp_file['path']);
                if(Storage::exists($tmp_file['path'])){
                    Storage::move($tmp_file['path'], $path);
                    $attachments[] = [
                        'name' => $tmp_file['name'],
                        'slug' => Str::slug($tmp_file['name']),
                        'path' => $path,
                        'extension' => Storage::mimeType($path),
                        'size' => Number::fileSize(Storage::fileSize($path), precision: 2),
                    ];
                }
            }
        }
        $user = Auth::user();
        $patient->records()->create([
            'comment' => $validated['comment'],
            'attachments' => $attachments,
            'doctor_id' => $user->id,
            'clinic_id' => $user->active_clinic->id
        ]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('viewAny', Patient::class);
        $user = Auth::user();
        $patients = $user->active_clinic->patients()->orderByPivot('created_at', 'desc')->paginate();
        return Inertia::render('doctor/patient/Index', [
            'patients' => PatientResource::collection($patients)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Patient $patient)
    {
        Gate::authorize('view', $patient);
        $records = $patient->records()->with(['doctor', 'clinic'])->paginate();
        return Inertia::render('doctor/patient/Show', [
            'patient' => new PatientResource($patient),
            'records' => PatientRecordResource::collection($records),
        ]);
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
