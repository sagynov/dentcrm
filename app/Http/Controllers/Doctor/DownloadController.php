<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DownloadController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'path' => 'required'
        ]);
        if(Storage::exists($validated['path'])) {
            return Storage::download($validated['path']);
        }
    }
}
