<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Event;
use App\Models\User;
use App\Notifications\EventReminder;
use Carbon\Carbon;

class SendEventReminder extends Command
{
    protected $signature = 'event:send-reminder';
    protected $description = 'Send event reminder notifications';

    public function handle()
    {
        $now = Carbon::now();
        $reminders = [
            $now->copy()->addDay(), // 1 hari
            $now->copy()->addWeek(), // 1 minggu
            $now->copy()->addMinutes(5), // 5 menit
            $now->copy()->addMinute(), // 1 menit
        ];

        foreach ($reminders as $reminderTime) {
            // $events = Event::whereDate('start', $reminderTime->toDateString())->get();
            $events = Event::whereBetween('start', [$now->copy()->addDay()->startOfDay(), $now->copy()->addDay()->endOfDay()])->get();
            // dd($events);
            foreach ($events as $event) {
                $user = User::where('username', $event->username)->first();
                if ($user) {
                    $user->notify(new EventReminder($event));
                }
            }
        }
    }
}
