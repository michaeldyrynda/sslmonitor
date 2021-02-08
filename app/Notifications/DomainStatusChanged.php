<?php

namespace App\Notifications;

use App\Check;
use App\Monitor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DomainStatusChanged extends Notification implements ShouldQueue
{
    use Queueable;

    public Check $check;

    public string $newStatus;

    public string $originalStatus;

    public function __construct(Check $check, string $originalStatus, string $newStatus)
    {
        $this->check = $check;
        $this->originalStatus = $originalStatus;
        $this->newStatus = $newStatus;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject("A domain has changed registration status")
            ->line("The registration status for {$this->monitor->site} has changed from {$this->originalStatus} to {$this->newStatus}")
            ->action('Whois Status Codes', 'https://afilias.com.au/get-au/whois-status-codes')
            ->line('This may simply mean that a domain has recently been renewed.');
    }
}
