<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\EventParticipantController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    
    Route::middleware('auth:sanctum')->group( function() {
        Route::get('/user', [AuthController::class, 'user']);
        Route::post('logout', [AuthController::class, 'logout']);
    });
});

Route::middleware('auth:sanctum')->prefix('events')->group(function () {
    Route::get('/', [EventController::class, 'index'])->name('events.index');
    Route::get('/{event}', [EventController::class, 'show'])->name('events.show');
    Route::post('/create', [EventController::class, 'store'])->name('events.store');
    Route::put('/{event}/edit', [EventController::class, 'update'])->name('events.update');
    Route::delete('/{event}', [EventController::class, 'destroy'])->name('events.destroy');
    Route::get('/organized-events', [EventController::class, 'myEvents'])->name('events.myevents');

    Route::post('/{event}/join', [EventParticipantController::class, 'joinEvent']);
    Route::post('/{event}/leave', [EventParticipantController::class, 'leaveEvent']);
    Route::post('/{event}/accept/{user}', [EventParticipantController::class, 'acceptJoinRequest']);
    Route::post('/{event}/reject/{user}', [EventParticipantController::class, 'rejectJoinRequest']);
});
