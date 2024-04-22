<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

Auth::routes();

Route::middleware(['customer'])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::any('/reservations', [App\Http\Controllers\HomeController::class, 'reservations'])->name('reservations');
    Route::any('/myreservations', [App\Http\Controllers\HomeController::class, 'myReservations'])->name('myreservations');
    Route::any('/reschedule/{reservation_id}', [App\Http\Controllers\HomeController::class, 'reschedule'])->name('reschedule');
    Route::any('/rooms', [App\Http\Controllers\HomeController::class, 'rooms'])->name('rooms');
    Route::any('/booking', [App\Http\Controllers\HomeController::class, 'booking'])->name('booking');
    Route::get('/locations', [App\Http\Controllers\HomeController::class, 'locations'])->name('autocomplete-locations');
});
