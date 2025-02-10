<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EventReminder extends Notification
{
    use Queueable;

    protected $event;


    public function __construct($event)
    {
        $this->event = $event;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject("Reminder: {$this->event->name} is Coming Soon!")
            ->line("Hello, {$notifiable->name}!")
            ->line("Your event \"{$this->event->name}\" will start on {$this->event->start}.")
            ->line("Don't forget to prepare for it!")
            ->action('View Event', url('/events/' . $this->event->id))
            ->line('Thank you for using our application!');
    }
}
