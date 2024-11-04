<?php

namespace TomatoPHP\FilamentSaasPanel\Filament\Pages\EditTeam;

use Filament\Facades\Filament;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use TomatoPHP\FilamentSaasPanel\Filament\Forms\ManageTeamMembersForm;

trait HasManageTeamMembers
{
    public function manageTeamMembersForm(Form $form): Form
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
