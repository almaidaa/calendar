<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Event;
use App\Models\User;
use App\Notifications\EventReminder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SendEventReminder extends Command
{
    protected $signature = 'event:send-reminder';
    protected $description = 'Kirim pengingat acara melalui OneSignal dan Email';

    public function handle()
    {
        $this->info('Sending event reminders...');

        // H-1 Reminders (Tomorrow)
        $tomorrow = \Carbon\Carbon::tomorrow()->toDateString();
        $eventsForTomorrow = \App\Models\Event::whereDate('start', $tomorrow)->get();

        foreach ($eventsForTomorrow as $event) {
            $user = \App\Models\User::where('username', $event->username)->first();
            if ($user && $user->phone_number) {
                $user->notify(new \App\Notifications\EventReminder($event));
                $this->info("H-1 reminder sent to {$user->username} for event: {$event->title}");
            }
        }

        // Hari H Reminders (Today)
        $today = \Carbon\Carbon::today()->toDateString();
        $eventsForToday = \App\Models\Event::whereDate('start', $today)->get();

        foreach ($eventsForToday as $event) {
            $user = \App\Models\User::where('username', '!=', 'admin')->where('username', $event->username)->first();
            if ($user && $user->phone_number) {
                $user->notify(new \App\Notifications\EventReminder($event));
                $this->info("Hari H reminder sent to {$user->username} for event: {$event->title}");
            }
        }

        $this->info('Event reminders sent successfully.');
    }
}
