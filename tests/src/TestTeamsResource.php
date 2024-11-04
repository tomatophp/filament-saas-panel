<?php

use TomatoPHP\FilamentSaasPanel\Filament\Resources\TeamResource;
use TomatoPHP\FilamentSaasPanel\Filament\Resources\TeamResource\Pages;
use TomatoPHP\FilamentSaasPanel\Tests\Models\Team;
use TomatoPHP\FilamentSaasPanel\Tests\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

beforeEach(function () {
    actingAs(User::factory()->create(), 'web');

    $panel = filament()->getPanels()['admin'];
    filament()->setCurrentPanel($panel);
});

it('can render teams resource', function () {
    get(TeamResource::getUrl())->assertSuccessful();
});

it('can list teams', function () {
    Team::query()->delete();
    $teams = Team::factory()->count(10)->create();

    livewire(Pages\ListTeams::class)
        ->loadTable()
        ->assertCanSeeTableRecords($teams)
        ->assertCountTableRecords(10);
});

it('can render teams name/avatar column in table', function () {
    Team::factory()->count(10)->create();

    livewire(Pages\ListTeams::class)
        ->loadTable()
        ->assertCanRenderTableColumn('name')
        ->assertCanRenderTableColumn('avatar');
});

it('can render teams list page', function () {
    livewire(Pages\ListTeams::class)->assertSuccessful();
});

it('can render view teams action', function () {
    livewire(Pages\ListTeams::class, [
        'record' => Team::factory()->create(),
    ])
        ->mountAction('view')
        ->assertSuccessful();
});

it('can render team create action', function () {
    livewire(Pages\ListTeams::class)
        ->mountAction('create')
        ->assertSuccessful();
});

it('can render team edit action', function () {
    livewire(Pages\ListTeams::class, [
        'record' => Team::factory()->create(),
    ])
        ->mountAction('edit')
        ->assertSuccessful();
});
