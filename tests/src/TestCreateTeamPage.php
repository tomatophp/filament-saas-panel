<?php

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

beforeEach(function () {
    actingAs(\TomatoPHP\FilamentSaasPanel\Tests\Models\Account::factory()->create(), 'accounts');
});

it('can render create team page', function () {
    get(url(config('filament-saas-panel.id').'/new'))->assertOk();
});

it('can register new team', function () {

    \Pest\Livewire\livewire(\TomatoPHP\FilamentSaasPanel\Filament\Pages\CreateTeam::class)
        ->fillForm([
            'name' => 'Team 1',
        ])
        ->call('register');

    \Pest\Laravel\assertDatabaseHas(\TomatoPHP\FilamentSaasPanel\Models\Team::class, [
        'name' => 'Team 1',
        'account_id' => auth('accounts')->user()->id,
    ]);
});
