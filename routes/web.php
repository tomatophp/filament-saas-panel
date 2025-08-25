<?php

use Illuminate\Support\Facades\Route;

Route::get('otp', \TomatoPHP\FilamentSaasPanel\Livewire\Otp::class)
    ->middleware('web', 'throttle:'.config('filament-saas-panel.throttle_otp'))
    ->name('otp');

Route::get('/users/team-invitations/{invitation}/accept', [\TomatoPHP\FilamentSaasPanel\Http\Controllers\TeamsController::class, 'accept'])
    ->middleware(['web', 'auth:'.config('filament-saas-panel.auth_guard')])
    ->name('team-invitations.accept');

Route::get('/users/team-invitations/{invitation}/cancel', [\TomatoPHP\FilamentSaasPanel\Http\Controllers\TeamsController::class, 'cancel'])
    ->middleware(['web', 'auth:'.config('filament-saas-panel.auth_guard')])
    ->name('team-invitations.cancel');
