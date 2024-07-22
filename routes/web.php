<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FullCalenderController;

Route::get('fullcalender', [FullCalenderController::class, 'index']);
Route::post('fullcalenderAjax', [FullCalenderController::class, 'ajax']);


Route::get('/', function () {
    return view('fullcalendar');

});
