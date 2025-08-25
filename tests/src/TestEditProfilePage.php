<?php

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

beforeEach(function () {
    $account = \TomatoPHP\FilamentSaasPanel\Tests\Models\User::factory()->create();
    $team = $account->teams()->create([
        'user_id' => $account->id,
        'name' => 'Team 1',
        'personal_team' => true,
    ]);
    $account->current_team_id = $team->id;
    actingAs($account, config('filament-saas-panel.auth_guard'));
});

it('can render edit profile page', function () {
    get(\TomatoPHP\FilamentSaasPanel\Filament\Pages\EditProfile::getUrl(['tenant' => auth(config('filament-saas-panel.auth_guard'))->user()->current_team_id]))->assertOk();
});

it('can edit profile details', function () {
    \Pest\Livewire\livewire(\TomatoPHP\FilamentSaasPanel\Filament\Pages\EditProfile::class)
        ->fillForm([
            'name' => 'John Doe',
        ], 'editProfileForm')
        ->call('updateProfile');

    \Pest\Laravel\assertDatabaseHas(\TomatoPHP\FilamentSaasPanel\Tests\Models\User::class, [
        'id' => auth(config('filament-saas-panel.auth_guard'))->user()->id,
        'name' => 'John Doe',
    ]);
});

it('can edit profile password', function () {
    \Pest\Livewire\livewire(\TomatoPHP\FilamentSaasPanel\Filament\Pages\EditProfile::class)
        ->fillForm([
            'current_password' => 'password',
            'password' => 'password123',
            'passwordConfirmation' => 'password123',
        ], 'editPasswordForm')
        ->call('updatePassword');

    \PHPUnit\Framework\assertTrue(auth(config('filament-saas-panel.auth_guard'))->attempt([
        'email' => auth(config('filament-saas-panel.auth_guard'))->user()->email,
        'password' => 'password123',
    ]));
});
