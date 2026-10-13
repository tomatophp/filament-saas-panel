<?php

use Illuminate\Support\Facades\Route;
use TomatoPHP\FilamentSaasPanel\Http\Controllers\TeamsController;
use TomatoPHP\FilamentSaasPanel\Livewire\Otp;

Route::get('otp', Otp::class)
    ->middleware('web', 'throttle:'.config('filament-saas-panel.throttle_otp'))
    ->name('otp');

// Invitation links are signed. The signed GET only renders a confirmation page,
// the state change is a POST to the same signed URL.
Route::middleware(['web', 'auth:'.config('filament-saas-panel.auth_guard'), 'signed'])->group(function () {
    Route::get('/users/team-invitations/{invitation}/accept', [TeamsController::class, 'showAccept'])
        ->name('team-invitations.accept');

    Route::post('/users/team-invitations/{invitation}/accept', [TeamsController::class, 'accept'])
        ->name('team-invitations.accept.confirm');

    Route::get('/users/team-invitations/{invitation}/cancel', [TeamsController::class, 'showCancel'])
        ->name('team-invitations.cancel');

    Route::post('/users/team-invitations/{invitation}/cancel', [TeamsController::class, 'cancel'])
        ->name('team-invitations.cancel.confirm');
});
