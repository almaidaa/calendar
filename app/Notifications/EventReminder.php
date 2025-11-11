<?php

namespace App\Notifications;

use App\Channels\WhatsAppChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Carbon\Carbon;

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
        return [WhatsAppChannel::class];
    }

    public function toWhatsApp($notifiable)
    {
        $eventName = $this->event->tipe . ': ' . $this->event->title;
        $startDate = Carbon::parse($this->event->start)->format('D, d M Y');

        return "Reminder: Your event \"{$eventName}\" is scheduled for {$startDate}. Don't forget to prepare for it!";
    }
}
