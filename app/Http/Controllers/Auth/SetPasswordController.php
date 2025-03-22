<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SetPasswordController extends Controller
{
    public function create(string $token)
    {
        $recent_code = DB::table('password_reset_codes')->where([['token', '=', $token], ['valid_until', '>', now()]])->first();
        if ($recent_code) {
            return Inertia::render('auth/SetPassword', [
                'token' => $token,
                'phone' => $recent_code->phone,
            ]);
        }
        return to_route('password.send-code');
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'token' => 'required|string',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        $recent_code = DB::table('password_reset_codes')->where([['token', '=', $validated['token']], ['valid_until', '>', now()]])->first();
        if($recent_code) {
            $user = User::where('phone', $recent_code->phone)->first();
            if($user){
                $user->forceFill([
                    'password' => Hash::make($validated['password']),
                    'remember_token' => Str::random(60),
                ])->save();
            }
            DB::table('password_reset_codes')->where('phone', $recent_code->phone)->delete();
            return to_route('login');
        }
    }
}
