<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Plan;
use App\Services\Halyk\Facades\HalykInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class PaymentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'paymethod' => 'required',
            'plan' => 'required',
        ]);
        $plan = Plan::where('slug', $validated['plan'])->first();
        if ($plan) {
            if($validated['paymethod'] == 'halyk') {
                $user = Auth::user();
                $payment = Payment::create([
                    'user_id' => $user->id,
                    'plan_slug' => $plan->slug,
                    'amount' => $plan->price,
                    'payment_method' => 'halyk',
                    'status' => 'pending',
                ]);
                $data = HalykInvoice::create(str_pad($payment->id, 8, '0', STR_PAD_LEFT), $payment->amount);
                $data['description'] = $plan->name.' - '.$plan->price;
                $data['phone'] = $user->phone;
                return $data;
            }
        }
    }

    public function halykProcess(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required',
            'amount' => 'required',
            'invoiceId' => 'required',
            'secret_hash' => 'required',
        ]);
        if($validated['code'] !== 'ok')
        {
            return;
        }
        $secret_hash = env('API_HALYK_SECRET_HASH');
        $plain_text = $validated['invoiceId'].$validated['amount'].$secret_hash;
        if(Hash::check($plain_text, $validated['secret_hash'])) {
            $payment = Payment::find($validated['invoiceId']);
            if($payment->status == 'pending') {
                $payment->user->subscription()->create([
                    'plan_slug' => $payment->plan_slug,
                    'expires_at' => now()->addMonth()
                ]);
                $payment->update(['status' => 'success']);
            }
        }else{
            return response()->json([
                'code' => 'error'
            ]);
        }
    }
    public function success()
    {
        return Inertia::render('owner/payment/Success');
    }
    public function fail()
    {
        return Inertia::render('owner/payment/Fail');
    }
}
