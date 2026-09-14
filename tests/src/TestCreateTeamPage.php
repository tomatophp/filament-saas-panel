<?php

use TomatoPHP\FilamentSaasPanel\Filament\Pages\CreateTeam;
use TomatoPHP\FilamentSaasPanel\Models\Team;
use TomatoPHP\FilamentSaasPanel\Tests\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

beforeEach(function () {
    actingAs(User::factory()->create(), config('filament-saas-panel.auth_guard'));
});

it('can render create team page', function () {
    get(url(config('filament-saas-panel.id').'/new'))->assertOk();
});

it('can register new team', function () {

    \Pest\Livewire\livewire(CreateTeam::class)
        ->fillForm([
            'name' => 'Team 1',
        ])
        ->call('register');

    \Pest\Laravel\assertDatabaseHas(Team::class, [
        'name' => 'Team 1',
        'user_id' => auth(config('filament-saas-panel.auth_guard'))->user()->id,
    ]);
});
