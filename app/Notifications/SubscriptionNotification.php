<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SubscriptionNotification extends Notification
{
    use Queueable;

    protected $subscription;
    protected $subject;
    protected $user;


    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($subject,$subscription,$user)
    {
        $this->subscription = $subscription;
        $this->subject = $subject;
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail','database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url = url('/user/'.$this->user->id);

        return (new MailMessage)
            ->subject($this->subject)
            ->markdown('emails.webhook.notifications',['url'=>$url]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
           'message' => $this->subscription
        ];
    }
}
