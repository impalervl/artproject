<?php

namespace App\Http\Controllers;

use App\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionsController extends Controller
{
    public function store(Request $request)
    {
        //dd($request->all());
        // get the plan after submitting the form

        $plan = Plan::findOrFail($request->plan);

        $user = Auth::user();

        $user->newSubscription($plan->name, $plan->braintree_plan)->create($request->payment_method_nonce);


        // redirect to home after a successful subscription
        return redirect('home');
    }
}
