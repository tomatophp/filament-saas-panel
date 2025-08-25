<?php

use Filament\Facades\Filament;
use TomatoPHP\FilamentSaasPanel\Tests\Models\Team;

use function Pest\Laravel\get;

beforeEach(function () {
    config()->set('filament-saas-panel.user_model', \TomatoPHP\FilamentSaasPanel\Tests\Models\User::class);

    config()->set('filament-saas-panel.team_model', \TomatoPHP\FilamentSaasPanel\Tests\Models\Team::class);

    config()->set('filament-saas-panel.auth_guard', 'web');

    $this->panel = Filament::getCurrentOrDefaultPanel();
    $this->panel->tenant(Team::class, 'id');
});

it('can render login page', function () {
    get(url(config('filament-saas-panel.id').'/login'))->assertOk();
});

// it('can login', function () {
//     $account = \TomatoPHP\FilamentSaasPanel\Tests\Models\User::factory()->create();
//     $team =Team::create([
//         'user_id' => $account->id,
//         'name' => 'Team 1',
//         'personal_team' => true,
//     ]);
//     $account->current_team_id = $team->id;
//     $account->is_active = true;
//     $account->save();

//     $team->users()->attach($account, ['role' => 'admin']);

//     \Pest\Livewire\livewire(\TomatoPHP\FilamentSaasPanel\Filament\Pages\Auth\LoginAccount::class)
//         ->fillForm([
//             'email' => $account->email,
//             'password' => 'password',
//         ])
//         ->call('authenticate');

//     expect(auth(config('filament-saas-panel.auth_guard'))->check())->toBeTrue();
// });
