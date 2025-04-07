<?php

namespace TomatoPHP\FilamentSaasPanel;

use Filament\Contracts\Plugin;
use Filament\Panel;
use TomatoPHP\FilamentAccounts\Filament\Resources\AccountResource\Form\AccountForm;
use TomatoPHP\FilamentAccounts\Filament\Resources\AccountResource\Table\AccountActions;
use TomatoPHP\FilamentAccounts\Filament\Resources\AccountResource\Table\AccountBulkActions;
use TomatoPHP\FilamentAccounts\Filament\Resources\AccountResource\Table\AccountFilters;
use TomatoPHP\FilamentAccounts\Filament\Resources\AccountResource\Table\AccountTable;
use TomatoPHP\FilamentAccounts\FilamentAccountsPlugin;
use TomatoPHP\FilamentSaasPanel\Filament\Resources\TeamResource;

class FilamentSaasTeamsPlugin implements Plugin
{
    public function getId(): string
    {
        return 'filament-saas-teams';
    }

    public bool $allowAccountTeamTableAction = false;

    public bool $allowAccountTeamTableBulkAction = false;

    public bool $allowAccountTeamFilter = false;

    public bool $allowAccountTeamFormComponent = false;

    public bool $allowAccountTeamTableColumn = false;

    public function allowAccountTeamTableAction(bool $allowAccountTeamTableAction = true): self
    {
        $this->allowAccountTeamTableAction = $allowAccountTeamTableAction;

        return $this;
    }

    public function allowAccountTeamTableBulkAction(bool $allowAccountTeamTableBulkAction = true): self
    {
        $this->allowAccountTeamTableBulkAction = $allowAccountTeamTableBulkAction;

        return $this;
    }

    public function allowAccountTeamFilter(bool $allowAccountTeamFilter = true): self
    {
        $this->allowAccountTeamFilter = $allowAccountTeamFilter;

        return $this;
    }

    public function allowAccountTeamFormComponent(bool $allowAccountTeamFormComponent = true): self
    {
        $this->allowAccountTeamFormComponent = $allowAccountTeamFormComponent;

        return $this;
    }

    public function allowAccountTeamTableColumn(bool $allowAccountTeamTableColumn = true): self
    {
        $this->allowAccountTeamTableColumn = $allowAccountTeamTableColumn;

        return $this;
    }

    public function register(Panel $panel): void
    {
        $panel->resources([
            TeamResource::class,
        ]);
    }

    public function boot(Panel $panel): void
    {
        $panel->plugin(
            FilamentAccountsPlugin::make()
                ->useAvatar()
                ->canLogin()
                ->canBlocked()
        );

        if ($this->allowAccountTeamTableAction) {
            AccountActions::register(TeamResource\Actions\TeamTableAction::make());
        }
        if ($this->allowAccountTeamTableBulkAction) {
            AccountBulkActions::register(TeamResource\Actions\TeamBulkAction::make());
        }
        if ($this->allowAccountTeamFilter) {
            AccountFilters::register(TeamResource\Filters\TeamFilter::make());
        }
        if ($this->allowAccountTeamFormComponent) {
            AccountForm::register(TeamResource\Form\TeamComponent::make());
        }
        if ($this->allowAccountTeamTableColumn) {
            AccountTable::register(TeamResource\Table\TeamColumn::make());
        }
    }

    public static function make(): FilamentSaasTeamsPlugin
    {
        return new FilamentSaasTeamsPlugin;
    }
}
