<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FullCalenderController;




Route::post('/login', [AuthController::class, 'login'])->name('auth')->middleware('guest');
Route::get('/login', [AuthController::class, 'index'])->name('login')->middleware('guest');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');
Route::get('/', [FullCalenderController::class, 'index'])->middleware('auth');
Route::post('fullcalenderAjax', [FullCalenderController::class, 'ajax'])->middleware('auth');
// Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');
Route::post('/search', [FullCalenderController::class, 'search'])->name('search')->middleware('auth');
Route::post('/save-player-id', [FullCalenderController::class, 'savePlayerId']);

// Route::middleware('auth')->group(function () {
// });




// Route::get('/', function () {
//     return view('fullcalendar');

// });
