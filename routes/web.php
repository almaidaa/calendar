<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FullCalenderController;
use App\Models\Event;

use App\Models\User;
use App\Notifications\EventNotif;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;


// Route untuk autentikasi
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('auth');
});

Route::middleware('auth')->group(function () {
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/', [FullCalenderController::class, 'index'])->name('calendar.index');
    Route::post('/fullcalenderAjax', [FullCalenderController::class, 'ajax'])->name('calendar.ajax');
     Route::get('/fullcalenderAjax', [FullCalenderController::class, 'ajax']);
    Route::post('/search', [FullCalenderController::class, 'search'])->name('calendar.search');

    // Pastikan hanya user login yang bisa menyimpan Player ID
    Route::post('/save-player-id', [FullCalenderController::class, 'savePlayerId'])->name('player.save');

    // Rute untuk mengirim notifikasi (opsional, bisa dijadikan console command juga)
    Route::get('/send-notifications', [FullCalenderController::class, 'sendNotifications'])->name('notifications.send');
});

Route::get('/run', function () {
    Artisan::call('event:send-reminder');
//    echo ini_get('disable_functions');

});

Route::get('/test-notification', function () {

    $now = Carbon::now();
    // Temukan pengguna yang akan menerima notifikasi
    $events = Event::whereBetween('start', [$now->copy()->addDay()->startOfDay(), $now->copy()->addDay()->endOfDay()])->get();
    // dd($events);
    foreach ($events as $event) {
        // Pastikan pengguna memiliki onesignal_player_id
        $user = User::where('username', $event->username)->first();
        if ($user->onesignal_player_id) {
            // Kirim notifikasi
            $user->notify(new EventNotif($event));
            echo $user->onesignal_player_id;
            // echo "Notifikasi berhasil dikirim.";
        } else {
            // Pengguna tidak ditemukan atau tidak memiliki onesignal_player_id
            echo "Pengguna tidak ditemukan atau tidak memiliki OneSignal Player ID.";
        }
    }

});

// Route::post('/login', [AuthController::class, 'login'])->name('auth')->middleware('guest');
// Route::get('/login', [AuthController::class, 'index'])->name('login')->middleware('guest');
// Route::get('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');
// Route::get('/', [FullCalenderController::class, 'index'])->middleware('auth');
// Route::post('fullcalenderAjax', [FullCalenderController::class, 'ajax'])->middleware('auth');
// // Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');
// Route::post('/search', [FullCalenderController::class, 'search'])->name('search')->middleware('auth');

// Route::post('/save-player-id', [FullCalenderController::class, 'savePlayerId']);

// Route::get('/send-notifications', [FullCalenderController::class, 'sendNotifications']);
// // Route::middleware('auth')->group(function () {
// // });




// Route::get('/', function () {
//     return view('fullcalendar');

// });
