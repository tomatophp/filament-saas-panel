<?php

namespace TomatoPHP\FilamentSaasPanel;

use Filament\Auth\Events\Registered;
use Filament\Events\TenantSet;
use Filament\Livewire\Notifications;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;
use Laravel\Jetstream\Jetstream;
use Livewire\Livewire;
use TomatoPHP\FilamentSaasPanel\Listeners\CreatePersonalTeam;
use TomatoPHP\FilamentSaasPanel\Listeners\SwitchTeam;
use TomatoPHP\FilamentSaasPanel\Livewire\Otp;
use TomatoPHP\FilamentSaasPanel\Livewire\SanctumTokens;

class FilamentSaasPanelServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Register generate command
        $this->commands([
            \TomatoPHP\FilamentSaasPanel\Console\FilamentSaasPanelInstall::class,
        ]);

        // Register Config file
        $this->mergeConfigFrom(__DIR__.'/../config/filament-saas-panel.php', 'filament-saas-panel');

        // Publish Config
        $this->publishes([
            __DIR__.'/../config/filament-saas-panel.php' => config_path('filament-saas-panel.php'),
        ], 'filament-saas-panel-config');

        // Register Migrations
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        // Publish Migrations
        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'filament-saas-panel-migrations');
        // Register views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'filament-saas-panel');

        // Publish Views
        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/filament-saas-panel'),
        ], 'filament-saas-panel-views');

        // Register Langs
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'filament-saas-panel');

        // Publish Lang
        $this->publishes([
            __DIR__.'/../resources/lang' => base_path('lang/vendor/filament-saas-panel'),
        ], 'filament-saas-panel-lang');

        // Register Routes
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        $this->publishes([
            __DIR__.'/../publish/migrations/create_teams_table.php' => database_path('migrations/'.date('Y_m_d_His', ((int) time()) + 1).'_create_teams_table.php'),
            __DIR__.'/../publish/migrations/create_team_invitations_table.php' => database_path('migrations/'.date('Y_m_d_His', ((int) time()) + 2).'_create_team_invitations_table.php'),
            __DIR__.'/../publish/migrations/create_team_user_table.php' => database_path('migrations/'.date('Y_m_d_His', ((int) time()) + 3).'_create_team_user_table.php'),
        ], 'filament-saas-teams-migrations');

        $this->publishes([
            __DIR__.'/../publish/Team.php' => app_path('Models/Team.php'),
            __DIR__.'/../publish/TeamInvitation.php' => app_path('Models/TeamInvitation.php'),
            __DIR__.'/../publish/Membership.php' => app_path('Models/Membership.php'),
        ], 'filament-saas-teams-models');

        if (class_exists(Jetstream::class)) {
            Jetstream::useUserModel(config('filament-saas-panel.user_model'));
            Jetstream::useTeamModel(config('filament-saas-panel.team_model'));
            Jetstream::useMembershipModel(config('filament-saas-panel.membership_model'));
            Jetstream::useTeamInvitationModel(config('filament-saas-panel.team_invitation_model'));
            Jetstream::$registersRoutes = false;
            Fortify::$registersRoutes = false;

            Jetstream::defaultApiTokenPermissions(['read']);
        }

        Livewire::component('sanctum-tokens', SanctumTokens::class);
        Livewire::component('otp', Otp::class);
        Livewire::component('notifications', Notifications::class);
    }

    public function boot(): void
    {
        $this->configurePermissions();
    }

    /**
     * Configure the permissions that are available within the application.
     */
    protected function configurePermissions(): void
    {
        Jetstream::role('admin', trans('filament-saas-panel::messages.roles.admin.name'), [
            'create',
            'read',
            'update',
            'delete',
        ])->description(trans('filament-saas-panel::messages.roles.admin.description'));

        Jetstream::role('user', trans('filament-saas-panel::messages.roles.user.name'), [
            'read',
            'update',
        ])->description(trans('filament-saas-panel::messages.roles.user.description'));

        Jetstream::permissions([
            'create',
            'read',
            'update',
            'delete',
        ]);

        /**
         * Disable Fortify routes
         */
        Fortify::$registersRoutes = false;

        /**
         * Disable Jetstream routes
         */
        Jetstream::$registersRoutes = false;

        /**
         * Listen and create personal team for new accounts
         */
        Event::listen(
            Registered::class,
            CreatePersonalTeam::class,
        );

        /**
         * Listen and switch team if tenant was changed
         */
        Event::listen(
            TenantSet::class,
            SwitchTeam::class,
        );
    }
}
