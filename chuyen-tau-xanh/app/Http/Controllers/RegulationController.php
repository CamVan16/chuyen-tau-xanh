<?php

namespace App\Http\Controllers;

use App\Models\ExchangePolicy;
use App\Models\RefundPolicy;

class RegulationController extends Controller
{
    public function index()
    {
        $exchangePolicies = ExchangePolicy::all();
        $refundPolicies = RefundPolicy::all();

        return view('pages.regulations', compact('exchangePolicies', 'refundPolicies'));
    }
}
