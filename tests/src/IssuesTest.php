<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use TomatoPHP\FilamentSaasPanel\Filament\Resources\TeamResource;
use TomatoPHP\FilamentSaasPanel\Tests\Models\Team;
use TomatoPHP\FilamentSaasPanel\Tests\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

/**
 * #16 Route [filament.admin.resources.accounts.index] not defined: the package must work
 * without tomatophp/filament-accounts, which is not one of its dependencies.
 */
it('renders the teams resource without tomatophp/filament-accounts (#16)', function () {
    expect(class_exists('TomatoPHP\\FilamentAccounts\\FilamentAccountsServiceProvider'))->toBeFalse();

    actingAs(User::factory()->create(), 'web');
    filament()->setCurrentPanel(filament()->getPanel('admin'));
    Team::factory()->create(['name' => 'Tomato Crew']);

    get(TeamResource::getUrl())
        ->assertSuccessful()
        ->assertSee('Tomato Crew')
        ->assertDontSee('filament-accounts::');

    expect(TeamResource::getNavigationLabel())->toBe('Teams')
        ->and(TeamResource::getModelLabel())->toBe('Team')
        ->and(TeamResource::getNavigationGroup())->toBe('Accounts');
});

function runPublishedTeamMigrations(): void
{
    foreach (['create_teams_table', 'create_team_invitations_table', 'create_team_user_table'] as $migration) {
        (require __DIR__."/../../publish/migrations/{$migration}.php")->up();
    }
}

/**
 * #21 SQLSTATE no such table: accounts: the published team migrations must use the configured
 * user table (users by default) instead of the filament-accounts `accounts` table.
 */
it('runs the published team migrations against the users table (#21)', function () {
    Schema::dropIfExists('team_invitations');
    Schema::dropIfExists('team_user');
    Schema::dropIfExists('teams');
    Schema::table('users', fn (Blueprint $table) => $table->dropColumn(['current_team_id', 'profile_photo_path']));

    runPublishedTeamMigrations();

    expect(Schema::hasColumns('teams', ['user_id', 'name', 'personal_team']))->toBeTrue()
        ->and(Schema::hasColumns('team_user', ['team_id', 'user_id', 'role']))->toBeTrue()
        ->and(Schema::hasColumns('team_invitations', ['team_id', 'email', 'role']))->toBeTrue()
        ->and(Schema::hasColumns('users', ['current_team_id', 'profile_photo_path']))->toBeTrue()
        ->and(Schema::hasTable('accounts'))->toBeFalse();
});

/**
 * #21 SQLSTATE table "team_user" already exists: running the published migrations on an app that
 * already has the Jetstream tables must not fail.
 */
it('skips the published team migrations when the tables already exist (#21)', function () {
    expect(Schema::hasTable('team_user'))->toBeTrue();

    runPublishedTeamMigrations();

    expect(Schema::hasTable('teams'))->toBeTrue()
        ->and(Schema::hasTable('team_user'))->toBeTrue();
});
