<?php

namespace App\Http\Controllers;

use App\Notifications\SubscriptionNotification;
use App\User;
use Exception;
use Braintree\WebhookNotification;
use Illuminate\Http\Request;
use Laravel\Cashier\Http\Controllers\WebhookController as CashierController;
use Symfony\Component\HttpFoundation\Response;
use Laravel\Cashier\Subscription;


class WebhookController extends CashierController
{

    public function handleWebhook(Request $request)
    {
        try {
            $webhook = $this->parseBraintreeNotification($request);
        } catch (Exception $e) {
            return;
        }

        $method = 'handle'.studly_case(str_replace('.', '_', $webhook->kind));

        file_put_contents("/var/www/html/artproject/storage/logs/webhook.log", "method $method \n", FILE_APPEND);


        if (method_exists($this, $method)) {
            file_put_contents("/var/www/html/artproject/storage/logs/webhook.log", "good method \n", FILE_APPEND);
            return $this->{$method}($webhook);
        } else {
            return $this->missingMethod();
        }
    }

    protected function parseBraintreeNotification($request)
    {
        return WebhookNotification::parse($request->bt_signature, $request->bt_payload);
    }

    /**
     * Handle a subscription cancellation notification from Braintree.
     *
     * @param  WebhookNotification  $webhook
     * @return \Illuminate\Http\Response
     */
    protected function handleSubscriptionCanceled($webhook)
    {
        $message = $webhook->subscription->id;
        //$message .= $webhook->timestamp;

        file_put_contents("/var/www/html/artproject/storage/logs/webhook.log", "cancel $message \n", FILE_APPEND);

        return $this->cancelSubscription($webhook->subscription->id);
    }

    /**
     * Handle a subscription expiration notification from Braintree.
     *
     * @param  WebhookNotification  $webhook
     * @return \Illuminate\Http\Response
     */
    protected function handleSubscriptionExpired($webhook)
    {
        return $this->cancelSubscription($webhook->subscription->id);
    }

    /**
     * Handle a subscription cancellation notification from Braintree.
     *
     * @param  string  $subscriptionId
     * @return \Illuminate\Http\Response
     */
    protected function cancelSubscription($subscriptionId)
    {
        $subscription = $this->getSubscriptionById($subscriptionId);

        if ($subscription && (! $subscription->cancelled() || $subscription->onGracePeriod())) {
            $subscription->markAsCancelled();
        }

        return new Response('Webhook Handled', 200);
    }

    /**
     * Get the user for the given subscription ID.
     *
     * @param  string  $subscriptionId
     * @return mixed
     */
    protected function getSubscriptionById($subscriptionId)
    {
        return Subscription::where('braintree_id', $subscriptionId)->first();
    }

    /**
     * Handle calls to missing methods on the controller.
     *
     * @param  array   $parameters
     * @return mixed
     */
    public function missingMethod($parameters = [])
    {
        return new Response;
    }
    /**
     * Handle a Braintree webhook.
     *

     * @return Response
     */
    public function handleSubscriptionWentActive($notification)
    {
        $message = $notification->subscription->id;
        $braintreeSubscription['price'] = $notification->subscription->price;
        $braintreeSubscription['createdAt'] = $notification->subscription->createdAt;
        $braintreeSubscription['nextBillingDate'] = $notification->subscription->nextBillingDate;
        $braintreeSubscription['status'] = $notification->subscription->status;

        file_put_contents("/var/www/html/artproject/storage/logs/webhook.log", "went active  $message \n", FILE_APPEND);

        $subscription = Subscription::where('braintree_id', $notification->subscription->id)->first();
        $user = $subscription->user;
        $subject = 'Your subscribtion '.$subscription->name.' was started successfully';


        $user->notify(new SubscriptionNotification($subject,$braintreeSubscription,$user));

        return new Response('Webhook Handled', 200);
    }

    public function handleSubscriptionChargedSuccessfully($notification)
    {
        $message = $notification->subscription->id;
        file_put_contents("/var/www/html/artproject/storage/logs/webhook.log", "charged  $message \n", FILE_APPEND);

        /*$subscription = Subscription::where('braintree_id', $notification->subscription->id)->first();
        $user = $subscription->user;
        $subject = 'Your subscribtion '.$subscription->name.' was started successfully';

        $user->notify(new SubscriptionNotification($subject,$notification,$user));*/

        return new Response('Webhook Handled', 200);
    }
}
