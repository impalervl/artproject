<?php

namespace App\Http\Controllers;

use App\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionsController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->all();

        $email = $data['paypal_email'];

        $plan = Plan::findOrFail($data['plan']);

        $user = Auth::user();

        $user->newSubscription($plan->name, $plan->braintree_plan)->create($request->payment_method_nonce, [
            'email' => $email,
        ]);


        // redirect to home after a successful subscription
        return redirect('home');
    }
}
