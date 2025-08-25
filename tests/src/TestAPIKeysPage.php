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
    $account->save();

    actingAs($account, config('filament-saas-panel.auth_guard'));
});

it('can render api keys page', function () {
    get(\TomatoPHP\FilamentSaasPanel\Filament\Pages\ApiTokens::getUrl(['tenant' => auth(config('filament-saas-panel.auth_guard'))->user()->current_team_id]))->assertOk();
});
