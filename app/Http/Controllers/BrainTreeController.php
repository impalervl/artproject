<?php

namespace App\Http\Controllers;

use App\Plan;
use Braintree_ClientToken;
use Illuminate\Http\Request;

class BrainTreeController extends Controller
{
    public function token(){

        return response()->json([
            'data' => [
                'token' => Braintree_ClientToken::generate(),
            ]
        ]);

    }

    public function index(){

        return view('plans.index')->with(['plans' => Plan::get()]);
    }

    public function show(Plan $plan)
    {
        return view('plans.show')->with(['plan' => $plan]);
    }
}
