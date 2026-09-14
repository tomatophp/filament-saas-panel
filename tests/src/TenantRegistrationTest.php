<?php

use Illuminate\Support\Facades\Route;
use TomatoPHP\FilamentSaasPanel\Filament\Pages\CreateTeam;
use TomatoPHP\FilamentSaasPanel\Tests\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

// allowTenants() used to register the create team page twice: as the tenant registration page and
// as a regular tenant page, which added a `/app/{tenant}/new` route that failed with a 500.
it('registers the create team page only as the tenant registration page', function () {
    $panel = filament()->getPanel('app');

    expect(Route::has('filament.app.tenant.registration'))->toBeTrue()
        ->and(Route::has('filament.app.registration'))->toBeFalse()
        ->and($panel->getTenantRegistrationPage())->toBe(CreateTeam::class)
        ->and($panel->getPages())->not->toContain(CreateTeam::class);
});

it('renders the create team page for a signed in user', function () {
    actingAs(User::factory()->create(), config('filament-saas-panel.auth_guard'));

    get(route('filament.app.tenant.registration'))->assertOk();
});
