<?php

namespace TomatoPHP\FilamentSaasPanel\Filament\Pages\EditTeam;

use Filament\Facades\Filament;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use TomatoPHP\FilamentSaasPanel\Filament\Forms\ManageTeamMembersForm;

trait HasManageTeamMembers
{
    public function manageTeamMembersForm(Schema $form): Schema
    {
        return $form->schema([
            Section::make(trans('filament-saas-panel::messages.teams.members.title'))
                ->description(trans('filament-saas-panel::messages.teams.members.description'))
                ->schema(ManageTeamMembersForm::get(Filament::getTenant())),
        ])
            ->model(Filament::getTenant())
            ->statePath('manageTeamMembersData');
    }
}
