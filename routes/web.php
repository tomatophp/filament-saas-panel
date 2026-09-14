<?php

use Illuminate\Support\Facades\Route;
use TomatoPHP\FilamentSaasPanel\Http\Controllers\TeamsController;
use TomatoPHP\FilamentSaasPanel\Livewire\Otp;

Route::get('otp', Otp::class)
    ->middleware('web', 'throttle:'.config('filament-saas-panel.throttle_otp'))
    ->name('otp');

Route::get('/users/team-invitations/{invitation}/accept', [TeamsController::class, 'accept'])
    ->middleware(['web', 'auth:'.config('filament-saas-panel.auth_guard')])
    ->name('team-invitations.accept');

Route::get('/users/team-invitations/{invitation}/cancel', [TeamsController::class, 'cancel'])
    ->middleware(['web', 'auth:'.config('filament-saas-panel.auth_guard')])
    ->name('team-invitations.cancel');
