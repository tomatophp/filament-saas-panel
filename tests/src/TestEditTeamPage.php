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

it('can render edit team page', function () {
    get(\TomatoPHP\FilamentSaasPanel\Filament\Pages\EditTeam::getUrl(['tenant' => auth(config('filament-saas-panel.auth_guard'))->user()->current_team_id]))->assertOk();
});

it('can edit team details', function () {
    filament()->setTenant(auth(config('filament-saas-panel.auth_guard'))->user()->currentTeam);

    \Pest\Livewire\livewire(\TomatoPHP\FilamentSaasPanel\Filament\Pages\EditTeam::class)
        ->fillForm([
            'name' => 'Team 2',
        ], 'editTeamForm')
        ->call('saveEditTeam');

    \Pest\Laravel\assertDatabaseHas(\TomatoPHP\FilamentSaasPanel\Models\Team::class, [
        'name' => 'Team 2',
        'user_id' => auth(config('filament-saas-panel.auth_guard'))->user()->id,
    ]);
});
