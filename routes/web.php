<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FullCalenderController;
use App\Http\Controllers\NoteController;




use App\Http\Controllers\HMIController;

Route::post('/login', [AuthController::class, 'login'])->name('auth')->middleware('guest');
Route::get('/login', [AuthController::class, 'index'])->name('login')->middleware('guest');
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register')->middleware('guest');
Route::post('/register', [AuthController::class, 'register'])->middleware('guest');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');
Route::get('/', [FullCalenderController::class, 'index'])->middleware('auth');
Route::post('fullcalenderAjax', [FullCalenderController::class, 'ajax'])->middleware('auth');
// Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('/notes', [NoteController::class, 'index']);
    Route::post('/notes', [NoteController::class, 'saveNote']);
    Route::delete('/notes', [NoteController::class, 'deleteNote']);
    Route::get('/hmi', [HMIController::class, 'index']);
});


// Route::middleware('auth')->group(function () {
// });




// Route::get('/', function () {
//     return view('fullcalendar');

// });