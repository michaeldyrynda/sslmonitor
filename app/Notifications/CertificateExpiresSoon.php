<?php

namespace App\Notifications;

use App\Monitor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CertificateExpiresSoon extends Notification implements ShouldQueue
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
            ->subject('A site certificate expires soon')
            ->line("The secure certificate for {$this->monitor->site} expires in {$this->monitor->expires_in_days} days.")
            ->line('Renew now to avoid service disruption.');
    }
}
