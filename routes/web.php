<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FullCalenderController;
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
    Route::post('/search', [FullCalenderController::class, 'search'])->name('calendar.search');

    // Pastikan hanya user login yang bisa menyimpan Player ID
    Route::post('/save-player-id', [FullCalenderController::class, 'savePlayerId'])->name('player.save');

    // Rute untuk mengirim notifikasi (opsional, bisa dijadikan console command juga)
    Route::get('/send-notifications', [FullCalenderController::class, 'sendNotifications'])->name('notifications.send');
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
