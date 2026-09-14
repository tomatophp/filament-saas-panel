<?php

use TomatoPHP\FilamentSaasPanel\Filament\Pages\ApiTokens;
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
    $account->save();

    actingAs($account, config('filament-saas-panel.auth_guard'));
});

it('can render api keys page', function () {
    get(ApiTokens::getUrl(['tenant' => auth(config('filament-saas-panel.auth_guard'))->user()->current_team_id]))->assertOk();
});
