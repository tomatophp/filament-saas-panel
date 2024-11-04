<?php

use function Pest\Laravel\get;

it('can render login page', function () {
    get(url(config('filament-saas-panel.id').'/login'))->assertOk();
});

it('can login', function () {
    $account = \TomatoPHP\FilamentSaasPanel\Tests\Models\Account::factory()->create();
    $team = $account->teams()->create([
        'account_id' => $account->id,
        'name' => 'Team 1',
        'personal_team' => true,
    ]);
    $account->current_team_id = $team->id;

    \Pest\Livewire\livewire(\TomatoPHP\FilamentSaasPanel\Filament\Pages\Auth\LoginAccount::class)
        ->fillForm([
            'email' => $account->email,
            'password' => 'password',
        ])
        ->call('authenticate');

    expect(auth('accounts')->check())->toBeTrue();
});
