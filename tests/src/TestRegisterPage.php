<?php

use function Pest\Laravel\get;

it('can render register page', function () {
    get(url(config('filament-saas-panel.id').'/register'))->assertOk();
});

it('can register', function () {
    \Pest\Livewire\livewire(\TomatoPHP\FilamentSaasPanel\Filament\Pages\Auth\RegisterAccountWithoutOTP::class)
        ->fillForm([
            'name' => 'Fady Mondy',
            'phone' => '+201207860084',
            'username' => 'info@3x1.io',
            'email' => 'info@3x1.io',
            'password' => 'password',
            'passwordConfirmation' => 'password',
        ])
        ->call('register')
        ->assertHasNoFormErrors();

    \Pest\Laravel\assertDatabaseHas(\TomatoPHP\FilamentSaasPanel\Tests\Models\Account::class, [
        'name' => 'Fady Mondy',
        'phone' => '+201207860084',
        'username' => 'info@3x1.io',
        'email' => 'info@3x1.io',
    ]);
});
