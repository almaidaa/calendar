<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Berkayk\OneSignal\OneSignalChannel;
use Berkayk\OneSignal\OneSignalMessage;

class EventReminder extends Notification implements ShouldQueue
{
    use Queueable;

    protected $event;

    public function __construct($event)
    {
        $this->event = $event;
    }

    public function via($notifiable)
    {
        return ['mail', OneSignalChannel::class];
    }

    // Method untuk email
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject("Reminder: {$this->event->name} is Coming Soon!")
            ->line("Hello, {$notifiable->name}!")
            ->line("Your event \"{$this->event->title}\" will start on {$this->event->start}.")
            ->line("Don't forget to prepare for it!")
            ->action('View Event', url('/events/' . $this->event->id))
            ->line('Thank you for using our application!');
    }

    // Method untuk OneSignal
    public function toOneSignal($notifiable)
    {
        return OneSignalMessage::create()
            ->setSubject("Reminder: {$this->event->name} is Coming Soon!")
            ->setBody("Your event \"{$this->event->title}\" will start on {$this->event->start}. Don't forget to prepare for it!")
            ->setUrl(url('/events/' . $this->event->id))
            ->setData('event_id', $this->event->id)
            ->setData('type', 'event_reminder');
    }
}
