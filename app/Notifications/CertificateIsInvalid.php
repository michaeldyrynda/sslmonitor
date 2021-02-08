<?php

namespace App\Notifications;

use App\Monitor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CertificateIsInvalid extends Notification implements ShouldQueue
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
            ->subject('A site certificate is invalid!')
            ->line("The certificate for {$this->monitor->site} is invalid.")
            ->line('Please investigate to minimise service disruption.');
    }
}
