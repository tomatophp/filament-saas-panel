<?php

use Illuminate\Validation\ValidationException;
use TomatoPHP\FilamentSaasPanel\Actions\Fortify\CreateNewUser;
use TomatoPHP\FilamentSaasPanel\Actions\Fortify\UpdateUserProfileInformation;
use TomatoPHP\FilamentSaasPanel\Tests\Models\User;

it('registers a user of the configured model with a personal team', function () {
    $user = (new CreateNewUser)->create([
        'name' => 'Farida Ashraf',
        'email' => 'farida@example.com',
        'password' => 'Str0ng-Passw0rd!',
        'password_confirmation' => 'Str0ng-Passw0rd!',
    ]);

    $team = $user->ownedTeams()->first();

    expect($user)->toBeInstanceOf(User::class)
        ->and($team->name)->toBe("Farida's Team")
        ->and($team->personal_team)->toBeTrue();
});

it('rejects registering an email twice', function () {
    User::factory()->create(['email' => 'taken@example.com']);

    (new CreateNewUser)->create([
        'name' => 'Someone',
        'email' => 'taken@example.com',
        'password' => 'Str0ng-Passw0rd!',
        'password_confirmation' => 'Str0ng-Passw0rd!',
    ]);
})->throws(ValidationException::class);

it('updates the profile of the configured user model', function () {
    $user = User::factory()->create(['name' => 'Old Name']);

    (new UpdateUserProfileInformation)->update($user, [
        'name' => 'New Name',
        'email' => $user->email,
    ]);

    expect($user->refresh()->name)->toBe('New Name');
});
