<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Berkayk\OneSignal\OneSignalMessage;
use App\Models\Event;

class EventNotif extends Notification implements ShouldQueue
{
    use Queueable;

    protected $event;

    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    public function via($notifiable)
    {
        return ['onesignal'];
    }

    public function toOneSignal($notifiable)
    {
        return OneSignalMessage::create()
            ->subject("Pengingat: {$this->event->title} akan segera dimulai!")
            ->body("Acara \"{$this->event->title}\" dijadwalkan pada {$this->event->start->format('d M Y, H:i')}.")
            ->setData('event_id', $this->event->users->onesignal_player_id);
    }
}
