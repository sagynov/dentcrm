<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        if($user->is_owner) {
            if(!$user->subscription) {
                return to_route('owner.plans.index')->with('status', __('Please select a tariff plan to get started'));
            }else {
                if($user->subscription->expires_at < now()) {
                    return to_route('owner.subscription.expired');
                }
            }
        }
        if($user->doctor)
        {
            $clinic = $user->active_clinic;
            $owner = $clinic->users()->where('role', 'owner')->first();
            if($owner->subscription->expires_at < now()) {
                return to_route('doctor.subscription.expired');
            }
        }

        return $next($request);
    }
}
