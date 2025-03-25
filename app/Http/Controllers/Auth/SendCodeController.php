<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\User;
use App\Notifications\Whatsapp\SendCode;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SendCodeController extends Controller
{
    /**
     * Show the password reset link request page.
     */
    public function create(Request $request): Response
    {
        return Inertia::render('auth/SendCode', [
            'status' => $request->session()->get('status'),
        ]);
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'phone' => 'required|phone',
        ]);
        $user = User::where('phone', $validated['phone'])->first();
        if($user) {
            $recent_code = DB::table('password_reset_codes')->where([['phone', '=', $validated['phone']], ['valid_until', '>', now()]])->first();
            if(!$recent_code){
                $code = rand(1000, 9999);
                $user->notify(new SendCode($code));
                $token = Str::random(60);

                DB::table('password_reset_codes')->where('phone', $user->phone)->delete();
                
                DB::table('password_reset_codes')->insert([
                    'phone'=> $validated['phone'],
                    'code' => $code,
                    'token' => $token,
                    'valid_until' => now()->addHour()
                ]);
            }
            return to_route('password.enter-code', ['phone' => $validated['phone']]);
        }
        return back()->with('status', __('The phone number does not exist'));
    }
}
