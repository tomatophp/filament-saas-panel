<?php

namespace TomatoPHP\FilamentSaasPanel\Tests;

use Filament\Panel;
use Filament\Facades\Filament;
use Laravel\Jetstream\Jetstream;
use Filament\FilamentServiceProvider;
use Livewire\LivewireServiceProvider;
use Filament\Forms\FormsServiceProvider;
use Filament\Tables\TablesServiceProvider;
use Laravel\Fortify\FortifyServiceProvider;
use Orchestra\Testbench\Attributes\WithEnv;
use BladeUI\Icons\BladeIconsServiceProvider;
use Filament\Actions\ActionsServiceProvider;
use Filament\Schemas\SchemasServiceProvider;
use Filament\Support\SupportServiceProvider;
use Filament\Widgets\WidgetsServiceProvider;
use Laravel\Jetstream\JetstreamServiceProvider;
use Orchestra\Testbench\Concerns\WithWorkbench;
use Filament\Infolists\InfolistsServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;
use TomatoPHP\FilamentSaasPanel\Tests\Models\Team;
use TomatoPHP\FilamentSaasPanel\Tests\Models\User;
use BladeUI\Heroicons\BladeHeroiconsServiceProvider;
use Spatie\MediaLibrary\MediaLibraryServiceProvider;
use Filament\Notifications\NotificationsServiceProvider;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use TomatoPHP\FilamentSaasPanel\Tests\Models\Membership;
use TomatoPHP\FilamentSaasPanel\Tests\Models\TeamInvitation;
use TomatoPHP\FilamentSaasPanel\FilamentSaasPanelServiceProvider;
use RyanChandler\BladeCaptureDirective\BladeCaptureDirectiveServiceProvider;

#[WithEnv('DB_CONNECTION', 'testing')]
abstract class TestCase extends BaseTestCase
{
    use LazilyRefreshDatabase;
    use WithWorkbench;

    public ?Panel $panel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->panel = Filament::getCurrentOrDefaultPanel();

        Jetstream::useUserModel(config('filament-saas-panel.user_model'));
        Jetstream::useTeamModel(config('filament-saas-panel.team_model'));
        Jetstream::useMembershipModel(config('filament-saas-panel.membership_model'));
        Jetstream::useTeamInvitationModel(config('filament-saas-panel.team_invitation_model'));
    }

    protected function getPackageProviders($app): array
    {
        $providers = [
            ActionsServiceProvider::class,
            BladeCaptureDirectiveServiceProvider::class,
            BladeHeroiconsServiceProvider::class,
            BladeIconsServiceProvider::class,
            FilamentServiceProvider::class,
            FormsServiceProvider::class,
            InfolistsServiceProvider::class,
            LivewireServiceProvider::class,
            NotificationsServiceProvider::class,
            SupportServiceProvider::class,
            SchemasServiceProvider::class,
            TablesServiceProvider::class,
            WidgetsServiceProvider::class,
            MediaLibraryServiceProvider::class,
            JetstreamServiceProvider::class,
            FortifyServiceProvider::class,
            FilamentSaasPanelServiceProvider::class,
            AppPanelProvider::class,
            AdminPanelProvider::class,
        ];

        sort($providers);

        return $providers;
    }

    protected function defineDatabaseMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }

    public function getEnvironmentSetUp($app): void
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('auth.guards.testing.driver', 'session');
        $app['config']->set('auth.guards.testing.provider', 'testing');
        $app['config']->set('auth.providers.testing.driver', 'eloquent');
        $app['config']->set('auth.providers.testing.model', User::class);
        $app['config']->set('filament-saas-panel.user_model', User::class);
        $app['config']->set('filament-saas-panel.team_model', Team::class);
        $app['config']->set('filament-saas-panel.membership_model', Membership::class);
        $app['config']->set('filament-saas-panel.team_invitation_model', TeamInvitation::class);

        $app['config']->set('view.paths', [
            ...$app['config']->get('view.paths'),
            __DIR__.'/../resources/views',
        ]);
    }
}
