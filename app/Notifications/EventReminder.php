<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Event;

class EventReminder extends Notification implements ShouldQueue
{
    use Queueable;

    protected $event;

    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    public function via($notifiable)
    {
        return ['mail', 'onesignal'];
    }


    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject("Pengingat Event: {$this->event->title}")
            ->line("Event \"{$this->event->title}\" akan dimulai besok pada {$this->event->start->format('d M Y')}.")
            ->action('Lihat Detail', url('/events/' . $this->event->id));
    }

    public function toOneSignal($notifiable)
    {
        \Log::info("toOneSignal() dieksekusi untuk User: {$notifiable->id}");

        if (!$notifiable->onesignal_player_id) {
            \Log::warning("Player ID tidak ditemukan untuk User: {$notifiable->id}");
            return;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Basic ' . env('ONESIGNAL_REST_API_KEY'),
                'Content-Type' => 'application/json',
            ])->post('https://onesignal.com/api/v1/notifications', [
                'app_id' => env('ONESIGNAL_APP_ID'),
                'include_player_ids' => [$notifiable->onesignal_player_id],
                'headings' => ["en" => "Reminder: {$this->event->title}"],
                'contents' => ["en" => "Event \"{$this->event->title}\" dijadwalkan besok pada {$this->event->start->format('d M Y')}. Jangan lupa!"],
                'data' => ['event_id' => $this->event->id],
            ]);

            if ($response->successful()) {
                \Log::info("Notifikasi berhasil dikirim ke OneSignal: " . json_encode($response->json()));
            } else {
                \Log::error("Gagal mengirim notifikasi ke OneSignal: " . $response->body());
            }
        } catch (\Exception $e) {
            \Log::error("Exception di toOneSignal(): " . $e->getMessage());
        }
    }


}
