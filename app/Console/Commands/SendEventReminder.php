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
        $now = Carbon::now();
        $reminderIntervals = [
            '1 day' => $now->copy()->addDay(),

        ];

        $success = 0;
        $failed = 0;

        foreach ($reminderIntervals as $interval => $reminderTime) {
            // Ambil event yang start-nya sesuai dengan waktu reminder
            // $events = Event::whereBetween('start', [$reminderTime->startOfDay(), $reminderTime->endOfDay()])->get();
            //1 hari sebelum reminder
            $events = Event::whereBetween('end', [$now->copy()->addDay()->startOfDay(), $now->copy()->addDay()->endOfDay()])
                ->where('isnotified','n')
                ->get();

            // sukses
            // foreach ($events as $event) {
            //     $user = User::where('username', $event->username)->first();
            //     $user = User::whereNotNull('onesignal_player_id')->first();
            //     $event = Event::where('end', '>', now())->first();

            //     if ($user && $event) {
            //         $notification = new EventReminder($event);
            //         $notification->toOneSignal($user);
            //     }
            // }

            foreach ($events as $event) {
                $user = User::where('username', $event->username)->first();
                if ($user) {
                    try {
                        Log::info("EventReminder: Notifikasi dikirim ke {$user->username} untuk event {$event->title} ({$interval} sebelum acara).");
                        $notification = new EventReminder($event);
                        $notification->toOneSignal($user);
                        $success++;
                        $event->isnotified = 'y';
                        $event->save();
                    } catch (\Exception $e) {
                        Log::error("EventReminder: Gagal mengirim notifikasi ke {$user->username} untuk event {$event->title}. Error: " . $e->getMessage());
                        $failed++;
                    }
                } else {
                    Log::warning("EventReminder: Tidak menemukan user dengan username '{$event->username}' untuk event '{$event->title}'.");
                }
            }
        }

        $this->info("Pengingat acara berhasil dikirim ke {$success} user.");
        if ($failed > 0) {
            $this->error("Pengingat acara gagal dikirim ke {$failed} user.");
        }
    }
}
