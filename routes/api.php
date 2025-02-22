<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\EventController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/events', [EventController::class, 'index'])->name('event.index');
    Route::get('/events/{event}', [EventController::class, 'show'])->name('event.show');
    Route::post('/store-event', [EventController::class, 'store'])->name('event.store');
    Route::put('/{event}/edit', [EventController::class, 'update'])->name('event.update');
});
