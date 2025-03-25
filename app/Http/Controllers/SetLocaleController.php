<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Response;

class SetLocaleController extends Controller
{
    public function setLocale($locale)
    {
        if(in_array($locale, config('app.available_locales'))) {
            session()->put('locale', $locale);
            if(Auth::check()) {
                $user = Auth::user();
                $user->locale = $locale;
                $user->save();
            }
            return back();
        }
    }
}
