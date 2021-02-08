<?php

namespace App\Notifications;

use App\Monitor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DomainReadyForRenewal extends Notification implements ShouldQueue
{
    use Queueable;

    public Monitor $monitor;

    public function __construct(Monitor $monitor)
    {
        $this->monitor = $monitor;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('A domain is ready for renewal')
            ->line("The domain {$this->monitor->site} has entered its renewal window.")
            ->line('Renew now to avoid service disruption.');
    }
}
