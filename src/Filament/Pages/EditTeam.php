<?php

namespace TomatoPHP\FilamentSaasPanel\Filament\Pages;

use Filament\Facades\Filament;
use Filament\Pages\Tenancy\EditTenantProfile;
use TomatoPHP\FilamentSaasPanel\Filament\Pages\EditTeam\HasCancelTeamInvitation;
use TomatoPHP\FilamentSaasPanel\Filament\Pages\EditTeam\HasDeleteTeam;
use TomatoPHP\FilamentSaasPanel\Filament\Pages\EditTeam\HasEditTeam;
use TomatoPHP\FilamentSaasPanel\Filament\Pages\EditTeam\HasLeavingTeam;
use TomatoPHP\FilamentSaasPanel\Filament\Pages\EditTeam\HasManageRoles;
use TomatoPHP\FilamentSaasPanel\Filament\Pages\EditTeam\HasManageTeamMembers;
use TomatoPHP\FilamentSaasPanel\Filament\Pages\EditTeam\HasNotifications;
use TomatoPHP\FilamentSaasPanel\Filament\Pages\EditTeam\HasTeamInvitation;

class EditTeam extends EditTenantProfile
{
    use HasCancelTeamInvitation;
    use HasDeleteTeam;
    use HasEditTeam;
    use HasLeavingTeam;
    use HasManageRoles;
    use HasManageTeamMembers;
    use HasNotifications;
    use HasTeamInvitation;

    protected static string $view = 'filament-saas-panel::teams.edit-team';

    public static function isShouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function getLabel(): string
    {
        return trans('filament-saas-panel::messages.teams.title');
    }

    public ?array $deleteTeamData = [];

    public ?array $editTeamData = [];

    public ?array $manageTeamMembersData = [];

    public function mount(): void
    {
        $this->fillForms();
    }

    protected function getForms(): array
    {
        return [
            'editTeamForm',
            'deleteTeamFrom',
            'manageTeamMembersForm',
        ];
    }

    protected function fillForms(): void
    {
        $data = Filament::getTenant();

        $this->editTeamForm->fill($data->toArray());
        $this->deleteTeamFrom->fill($data->toArray());
        $this->manageTeamMembersForm->fill($data->toArray());
    }

    protected function getViewData(): array
    {
        return [
            'team' => Filament::getTenant(),
        ];
    }
}
