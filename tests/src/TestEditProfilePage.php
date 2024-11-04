<?php

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

beforeEach(function () {
    $account = \TomatoPHP\FilamentSaasPanel\Tests\Models\Account::factory()->create();
    $team = $account->teams()->create([
        'account_id' => $account->id,
        'name' => 'Team 1',
        'personal_team' => true,
    ]);
    $account->current_team_id = $team->id;
    actingAs($account, 'accounts');
});

it('can render edit profile page', function () {
    get(\TomatoPHP\FilamentSaasPanel\Filament\Pages\EditProfile::getUrl(['tenant' => auth('accounts')->user()->current_team_id]))->assertOk();
});

it('can edit profile details', function () {
    \Pest\Livewire\livewire(\TomatoPHP\FilamentSaasPanel\Filament\Pages\EditProfile::class)
        ->fillForm([
            'name' => 'John Doe',
        ], 'editProfileForm')
        ->call('updateProfile');

    \Pest\Laravel\assertDatabaseHas(\TomatoPHP\FilamentSaasPanel\Tests\Models\Account::class, [
        'id' => auth('accounts')->user()->id,
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

    \PHPUnit\Framework\assertTrue(auth('accounts')->attempt([
        'email' => auth('accounts')->user()->email,
        'password' => 'password123',
    ]));
});
