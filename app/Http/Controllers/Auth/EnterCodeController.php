<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class EnterCodeController extends Controller
{
    public function create(Request $request, string $phone) 
    {
        $recent_code = DB::table('password_reset_codes')->where([['phone', '=', $phone], ['valid_until', '>', now()]])->first();
        if($recent_code){
            return Inertia::render('auth/EnterCode', [
                'status' => $request->session()->get('status'),
                'phone' => $phone
            ]);
        }
        return to_route('password.send-code');
    }
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'phone' => 'required|phone',
            'code' => 'required|string',
        ]);

        $user = User::where('phone', $validated['phone'])->first();
        if($user) {
            $recent_code = DB::table('password_reset_codes')->where([['phone', '=', $validated['phone']], ['valid_until', '>', now()]])->first();
            if($validated['code'] == $recent_code->code){
                return to_route('password.set-password', ['token' => $recent_code->token]);
            }
        }
        return back()->with('status', __('The code is incorrect'));
    }
}
