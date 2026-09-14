<?php

use TomatoPHP\FilamentSaasPanel\Filament\Pages\EditTeam;
use TomatoPHP\FilamentSaasPanel\Models\Team;
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

it('can render edit team page', function () {
    get(EditTeam::getUrl(['tenant' => auth(config('filament-saas-panel.auth_guard'))->user()->current_team_id]))->assertOk();
});

it('can edit team details', function () {
    filament()->setTenant(auth(config('filament-saas-panel.auth_guard'))->user()->currentTeam);

    \Pest\Livewire\livewire(EditTeam::class)
        ->fillForm([
            'name' => 'Team 2',
        ], 'editTeamForm')
        ->call('saveEditTeam');

    \Pest\Laravel\assertDatabaseHas(Team::class, [
        'name' => 'Team 2',
        'user_id' => auth(config('filament-saas-panel.auth_guard'))->user()->id,
    ]);
});
