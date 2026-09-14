<?php

use Filament\Actions\Testing\TestAction;
use TomatoPHP\FilamentSaasPanel\Filament\Forms\DeleteAccountForm;
use TomatoPHP\FilamentSaasPanel\Filament\Pages\EditProfile;
use TomatoPHP\FilamentSaasPanel\Tests\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->user = User::factory()->create();
    $team = $this->user->teams()->create([
        'user_id' => $this->user->id,
        'name' => 'Team 1',
        'personal_team' => true,
    ]);
    $this->user->forceFill(['current_team_id' => $team->id])->save();

    actingAs($this->user, config('filament-saas-panel.auth_guard'));
});

// The action used a hard-coded `accounts` guard and wrote the filament-accounts `is_active` column,
// so it failed with "Auth guard [accounts] is not defined" on the default `web` guard.
it('deletes the signed in account from the edit profile page', function () {
    livewire(EditProfile::class)
        ->callAction(
            TestAction::make('deleteAccount')->schemaComponent(schema: 'deleteAccountForm'),
            data: ['password' => 'password'],
        )
        ->assertHasNoErrors();

    expect(User::query()->find($this->user->id))->toBeNull()
        ->and(auth(config('filament-saas-panel.auth_guard'))->check())->toBeFalse();
});

it('keeps the account when the password is wrong', function () {
    expect(DeleteAccountForm::deleteAccount(['password' => 'not-the-password']))->toBeFalse()
        ->and(User::query()->find($this->user->id))->not->toBeNull();
});
