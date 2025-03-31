<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class DownloadController extends Controller
{
    public function index(Request $request, Patient $patient)
    {
        Gate::authorize('view', $patient);
        $validated = $request->validate([
            'path' => 'required'
        ]);
        if(Storage::exists($validated['path'])) {
            return Storage::download($validated['path']);
        }
    }
}
