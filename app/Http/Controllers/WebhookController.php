<?php

namespace App\Http\Controllers;

use App\User;
use Braintree\WebhookNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Cashier\Http\Controllers\WebhookController as CashierController;

class WebhookController extends CashierController
{
    /**
     * Handle a Braintree webhook.
     *
     * @param  WebhookNotification  $webhook
     * @return Response
     */
    public function handleSubscriptionWentActive($notification)
    {
        file_put_contents("/var/www/html/artproject/app/webhook.log", 'im here ', FILE_APPEND);

        $user = Auth::user();

        $data['body'] = 'it is test notifiacation from webhook';
        $data['user_id'] = $user->id;

        file_put_contents("/var/www/html/artproject/app/webhook.log", 'im still here ', FILE_APPEND);

        DB::table('notifications')->insert($data);

        return new Response('Webhook Handled', 200);
    }
}
