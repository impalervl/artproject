<?php

namespace App\Http\Controllers;

use App\Plan;
use Braintree_Customer;
use Braintree_Subscription;
use Laravel\Cashier\Subscription;
use Braintree_SubscriptionSearch;
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

        $user->newSubscription($plan->slug, $plan->braintree_plan)->create($request->payment_method_nonce, [
            'email' => $email,
        ]);


        // redirect to home after a successful subscription
        return redirect('home');
    }

    public function nextBilling(){

        $user = Auth::user();

        $id = $user->id;

        $data['body'] = 'it is test notifiacation';

        $result = $user->notifications()->create($data);

        dd($id);

    }
}
