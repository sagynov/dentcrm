<?php

namespace App\Http\Controllers;

use App\Services\Taxpayer\Facades\Taxpayer;
use Illuminate\Http\Request;

class TaxpayerController extends Controller
{
    public function findByIIN($iin)
    { 
        $taxpayer = Taxpayer::findByIIN($iin);
        if (isset($taxpayer['error'])) {
            return response()->json($taxpayer, 500);
        }

        return response()->json($taxpayer);
    }
}
