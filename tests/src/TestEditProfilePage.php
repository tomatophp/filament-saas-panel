<?php

use TomatoPHP\FilamentSaasPanel\Filament\Pages\EditProfile;
use TomatoPHP\FilamentSaasPanel\Tests\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

beforeEach(function () {
    $account = User::factory()->create();
    $team = $account->teams()->create([
        'user_id' => $account->id,
        'name' => 'Team 1',
        'personal_team' => true,
    ]);
    $account->current_team_id = $team->id;
    actingAs($account, config('filament-saas-panel.auth_guard'));
});

it('can render edit profile page', function () {
    get(EditProfile::getUrl(['tenant' => auth(config('filament-saas-panel.auth_guard'))->user()->current_team_id]))->assertOk();
});

it('can edit profile details', function () {
    \Pest\Livewire\livewire(EditProfile::class)
        ->fillForm([
            'name' => 'John Doe',
        ], 'editProfileForm')
        ->call('updateProfile');

    \Pest\Laravel\assertDatabaseHas(User::class, [
        'id' => auth(config('filament-saas-panel.auth_guard'))->user()->id,
        'name' => 'John Doe',
    ]);
});

it('can edit profile password', function () {
    \Pest\Livewire\livewire(EditProfile::class)
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
