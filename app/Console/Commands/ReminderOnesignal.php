<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Event;
use App\Models\User;
use App\Notifications\EventNotif;
use Carbon\Carbon;

class ReminderOnesignal extends Command
{
    protected $signature = 'events:send-onesignal';
    protected $description = 'Kirim pengingat acara melalui OneSignal';

    public function handle()
    {
        $now = Carbon::now();
        $reminderIntervals = [
            $now->copy()->addDay(),
            $now->copy()->addWeek(),
            $now->copy()->addMinute(),
        ];

        foreach ($reminderIntervals as $reminderTime) {
            $events = Event::whereBetween('start', [$now->copy()->addDay()->startOfDay(), $now->copy()->addDay()->endOfDay()])->get();

            foreach ($events as $event) {
                $user = User::where('username', $event->username)->first();
                if ($user) {
                    $user->notify(new EventNotif($event));
                }
            }
        }


        $this->info("Pengingat acara berhasil dikirim!");
    }
}
