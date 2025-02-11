<?php

use App\Http\Controllers\FullCalenderController;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;



Schedule::command('event:send-reminder')->everyMinute();
Schedule::command('event:send-reminder')->everyFiveMinutes();
Schedule::command('event:send-reminder')->hourly();
Schedule::command('event:send-reminder')->daily();
Schedule::command([FullCalenderController::class, 'sendNotifications'])->everyMinute();

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();



