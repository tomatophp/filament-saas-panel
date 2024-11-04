<?php

namespace TomatoPHP\FilamentSaasPanel\Filament\Pages\EditTeam;

use Filament\Facades\Filament;
use Filament\Forms\Form;
use TomatoPHP\FilamentSaasPanel\Filament\Forms\DeleteTeamForm;

trait HasDeleteTeam
{
    public function deleteTeamFrom(Form $form): Form
    {
        return $form
            ->schema(DeleteTeamForm::get(Filament::getTenant()))
            ->model(Filament::getTenant())
            ->statePath('deleteTeamData');
    }
}
