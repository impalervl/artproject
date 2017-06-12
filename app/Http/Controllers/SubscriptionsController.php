<?php

namespace App\Http\Controllers;

use App\Mail\ConfirmEmail;
use App\Plan;
use App\User;
use Braintree_Customer;
use Braintree_Subscription;
use Illuminate\Support\Facades\Mail;
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

        //$user = Auth::user();

        $user = User::first();

        if(!$user->newSubscription($plan->slug, $plan->braintree_plan)
            ->create($request->payment_method_nonce, ['email' => $email,
        ])){
            return response()->json(['message'=>'try later'],500);
        }
        else{

            if($plan->slug == 'large'){

                $user->max_uploads = 50;
            }
            elseif($plan->slug == 'small'){

                $user->max_uploads = 20;
            }
            $user->role = 'artist';
        }

        $user->save();

        // redirect to home after a successful subscription
        return redirect('home');
    }

    /**
     *
     */
    public function nextBilling(){

        $user = User::first();

        Auth::login($user);

        $plan = $user->subscription('professional-monthly')->swap('professional_year');

        dd($plan);

    }
}
