<?php

namespace TomatoPHP\FilamentSaasPanel;

use Filament\Contracts\Plugin;
use Filament\Navigation\MenuItem;
use Filament\Panel;
use Laravel\Jetstream\Jetstream;
use TomatoPHP\FilamentSaasPanel\Filament\Pages\ApiTokens;
use TomatoPHP\FilamentSaasPanel\Models\Team;

class FilamentSaasPanelPlugin implements Plugin
{
    public function getId(): string
    {
        return 'filament-saas-panel';
    }

    public ?string $authGuard = 'accounts';

    public bool $editProfileMenu = false;

    public string $teamSlug = 'id';

    public bool $APITokenManager = false;

    public bool $editTeam = false;

    public bool $editProfile = false;

    public bool $editPassword = false;

    public bool $deleteAccount = false;

    public bool $browserSessionManager = false;

    public bool $registration = false;

    public bool $useJetstreamTeamModel = false;

    public bool $teamInvitation = false;

    public bool $deleteTeam = false;

    public bool $allowTenants = false;

    public bool $showTeamMembers = false;

    public bool $checkAccountStatusInLogin = false;

    public bool $useOTPActivation = false;

    public function allowTenants(bool $allowTenants = true): static
    {
        $this->allowTenants = $allowTenants;

        return $this;
    }

    public function teamSlug(string $teamSlug): static
    {
        $this->teamSlug = $teamSlug;

        return $this;
    }

    public function useOTPActivation(bool $useOTPActivation = true): static
    {
        $this->useOTPActivation = $useOTPActivation;

        return $this;
    }

    public function checkAccountStatusInLogin(bool $checkAccountStatusInLogin = true): static
    {
        $this->checkAccountStatusInLogin = $checkAccountStatusInLogin;

        return $this;
    }

    public function showTeamMembers(bool $showTeamMembers = true): static
    {
        $this->showTeamMembers = $showTeamMembers;

        return $this;
    }

    public function teamInvitation(bool $teamInvitation = true): static
    {
        $this->teamInvitation = $teamInvitation;

        return $this;
    }

    public function deleteTeam(bool $deleteTeam = true): static
    {
        $this->deleteTeam = $deleteTeam;

        return $this;
    }

    public function useJetstreamTeamModel(bool $useJetstreamTeamModel = true): static
    {
        $this->useJetstreamTeamModel = $useJetstreamTeamModel;

        return $this;
    }

    public function editProfileMenu(bool $editProfileMenu = true): static
    {
        $this->editProfileMenu = $editProfileMenu;

        return $this;
    }

    public function APITokenManager(bool $APITokenManager = true): static
    {
        $this->APITokenManager = $APITokenManager;

        return $this;
    }

    public function editTeam(bool $editTeam = true): static
    {
        $this->editTeam = $editTeam;

        return $this;
    }

    public function editProfile(bool $editProfile = true): static
    {
        $this->editProfile = $editProfile;

        return $this;
    }

    public function editPassword(bool $editPassword = true): static
    {
        $this->editPassword = $editPassword;

        return $this;
    }

    public function deleteAccount(bool $deleteAccount = true): static
    {
        $this->deleteAccount = $deleteAccount;

        return $this;
    }

    public function browserSessionManager(bool $browserSessionManager = true): static
    {
        $this->browserSessionManager = $browserSessionManager;

        return $this;
    }

    public function registration(bool $registration = true): static
    {
        $this->registration = $registration;

        return $this;
    }

    public function register(Panel $panel): void
    {
        $pages = [];

        if ($this->allowTenants) {
            $panel
                ->tenant($this->useJetstreamTeamModel ? Jetstream::teamModel() : Team::class, $this->teamSlug)
                ->tenantRegistration(config('filament-saas-panel.pages.teams.create'));

            $pages[] = config('filament-saas-panel.pages.teams.create');
        }

        $menuItems = [];

        if ($this->editProfile) {
            $pages[] = config('filament-saas-panel.pages.profile.edit');

            if ($this->editProfileMenu) {
                if ($this->allowTenants) {
                    $panel->userMenuItems([
                        'profile' => MenuItem::make()
                            ->label(fn (): string => auth('accounts')->user()?->name)
                            ->icon('heroicon-s-user')
                            ->url(fn (): string => filament()->getTenant() ? config('filament-saas-panel.pages.profile.edit')::getUrl() : '#'),
                    ]);
                } else {
                    $panel->userMenuItems([
                        'profile' => MenuItem::make()
                            ->label(fn (): string => auth('accounts')->user()?->name)
                            ->icon('heroicon-s-user')
                            ->url(fn (): string => config('filament-saas-panel.pages.profile.edit')::getUrl()),
                    ]);
                }

            }
        }

        if ($this->APITokenManager) {
            $pages[] = ApiTokens::class;

            if ($this->editProfileMenu) {
                $menuItems[] = MenuItem::make()
                    ->label(fn (): string => ApiTokens::getNavigationLabel())
                    ->icon('heroicon-s-lock-closed')
                    ->url(fn (): string => ApiTokens::getUrl());
            }
        }

        if ($this->editTeam) {
            $panel->livewireComponents([
                config('filament-saas-panel.pages.teams.edit'),
            ]);
            $panel->tenantProfile(config('filament-saas-panel.pages.teams.edit'));
        }

        if ($this->checkAccountStatusInLogin) {
            $panel->login(config('filament-saas-panel.pages.auth.login'));
        }

        if ($this->registration) {
            $panel->registration(config('filament-saas-panel.pages.auth.register-without-otp'));
        }

        if ($this->useOTPActivation) {
            $panel->registration(config('filament-saas-panel.pages.auth.register'));
        }

        $panel->tenantMenuItems($menuItems)
            ->authGuard($this->authGuard)
            ->pages($pages);

    }

    public function boot(Panel $panel): void {}

    public static function make(): FilamentSaasPanelPlugin
    {
        return new FilamentSaasPanelPlugin;
    }
}
