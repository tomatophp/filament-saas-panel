<?php

use Filament\Facades\Filament;

use function Pest\Laravel\get;

beforeEach(function () {
    config()->set('filament-saas-panel.user_model', \TomatoPHP\FilamentSaasPanel\Tests\Models\User::class);

    config()->set('filament-saas-panel.team_model', \TomatoPHP\FilamentSaasPanel\Tests\Models\Team::class);

    config()->set('filament-saas-panel.auth_guard', 'web');

    $this->panel = Filament::getCurrentOrDefaultPanel();
});

it('can render register page', function () {
    get(url(config('filament-saas-panel.id').'/register'))->assertOk();
});

it('can register', function () {
    \Pest\Livewire\livewire(\TomatoPHP\FilamentSaasPanel\Filament\Pages\Auth\RegisterAccountWithoutOTP::class)
        ->fillForm([
            'name' => 'Fady Mondy',
            'email' => 'info@3x1.io',
            'password' => 'password',
            'passwordConfirmation' => 'password',
        ])
        ->call('register')
        ->assertHasNoFormErrors();

    \Pest\Laravel\assertDatabaseHas(\TomatoPHP\FilamentSaasPanel\Tests\Models\User::class, [
        'name' => 'Fady Mondy',
        'email' => 'info@3x1.io',
    ]);
});
