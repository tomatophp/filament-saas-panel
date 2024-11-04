<?php

use Filament\Facades\Filament;
use TomatoPHP\FilamentSaasPanel\FilamentSaasPanelPlugin;

it('registers plugin', function () {
    $panel = Filament::getCurrentPanel();

    $panel->plugins([
        FilamentSaasPanelPlugin::make(),
    ]);

    expect($panel->getPlugin('filament-saas-panel'))
        ->not()
        ->toThrow(Exception::class);
});

it('can modify profile menu', function ($condition) {
    $plugin = FilamentSaasPanelPlugin::make()
        ->editProfileMenu($condition);

    expect($plugin->editProfileMenu)->toBe($condition);
})->with([
    false,
    fn () => true,
]);

it('can modify team slug', function ($condition) {
    $plugin = FilamentSaasPanelPlugin::make()
        ->teamSlug($condition);

    expect($plugin->teamSlug)->toBe($condition);
})->with([
    'slug',
    fn () => 'slug',
]);

it('can modify API Token Manager', function ($condition) {
    $plugin = FilamentSaasPanelPlugin::make()
        ->APITokenManager($condition);

    expect($plugin->APITokenManager)->toBe($condition);
})->with([
    false,
    fn () => true,
]);

it('can modify Edit team', function ($condition) {
    $plugin = FilamentSaasPanelPlugin::make()
        ->editTeam($condition);

    expect($plugin->editTeam)->toBe($condition);
})->with([
    false,
    fn () => true,
]);

it('can modify Edit profile', function ($condition) {
    $plugin = FilamentSaasPanelPlugin::make()
        ->editProfile($condition);

    expect($plugin->editProfile)->toBe($condition);
})->with([
    false,
    fn () => true,
]);

it('can modify Edit password', function ($condition) {
    $plugin = FilamentSaasPanelPlugin::make()
        ->editPassword($condition);

    expect($plugin->editPassword)->toBe($condition);
})->with([
    false,
    fn () => true,
]);

it('can modify delete account', function ($condition) {
    $plugin = FilamentSaasPanelPlugin::make()
        ->deleteAccount($condition);

    expect($plugin->deleteAccount)->toBe($condition);
})->with([
    false,
    fn () => true,
]);

it('can modify browser session manager', function ($condition) {
    $plugin = FilamentSaasPanelPlugin::make()
        ->browserSessionManager($condition);

    expect($plugin->browserSessionManager)->toBe($condition);
})->with([
    false,
    fn () => true,
]);

it('can modify registration', function ($condition) {
    $plugin = FilamentSaasPanelPlugin::make()
        ->registration($condition);

    expect($plugin->registration)->toBe($condition);
})->with([
    false,
    fn () => true,
]);

it('can modify use Jetstream Team Model', function ($condition) {
    $plugin = FilamentSaasPanelPlugin::make()
        ->useJetstreamTeamModel($condition);

    expect($plugin->useJetstreamTeamModel)->toBe($condition);
})->with([
    false,
    fn () => true,
]);

it('can modify team Invitation', function ($condition) {
    $plugin = FilamentSaasPanelPlugin::make()
        ->teamInvitation($condition);

    expect($plugin->teamInvitation)->toBe($condition);
})->with([
    false,
    fn () => true,
]);

it('can modify delete Team', function ($condition) {
    $plugin = FilamentSaasPanelPlugin::make()
        ->deleteTeam($condition);

    expect($plugin->deleteTeam)->toBe($condition);
})->with([
    false,
    fn () => true,
]);

it('can modify allow tenants', function ($condition) {
    $plugin = FilamentSaasPanelPlugin::make()
        ->allowTenants($condition);

    expect($plugin->allowTenants)->toBe($condition);
})->with([
    false,
    fn () => true,
]);

it('can modify show Team Members', function ($condition) {
    $plugin = FilamentSaasPanelPlugin::make()
        ->showTeamMembers($condition);

    expect($plugin->showTeamMembers)->toBe($condition);
})->with([
    false,
    fn () => true,
]);

it('can modify check Account Status In Login', function ($condition) {
    $plugin = FilamentSaasPanelPlugin::make()
        ->checkAccountStatusInLogin($condition);

    expect($plugin->checkAccountStatusInLogin)->toBe($condition);
})->with([
    false,
    fn () => true,
]);

it('can modify use OTP Activation', function ($condition) {
    $plugin = FilamentSaasPanelPlugin::make()
        ->useOTPActivation($condition);

    expect($plugin->useOTPActivation)->toBe($condition);
})->with([
    false,
    fn () => true,
]);
