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

it('can render api keys page', function () {
    get(\TomatoPHP\FilamentSaasPanel\Filament\Pages\ApiTokens::getUrl(['tenant' => auth('accounts')->user()->current_team_id]))->assertOk();
});
