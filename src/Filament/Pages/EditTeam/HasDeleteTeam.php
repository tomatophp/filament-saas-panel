<?php

namespace TomatoPHP\FilamentSaasPanel\Filament\Pages\EditTeam;

use Filament\Facades\Filament;
use Filament\Schemas\Schema;
use TomatoPHP\FilamentSaasPanel\Filament\Forms\DeleteTeamForm;

trait HasDeleteTeam
{
    public function deleteTeamFrom(Schema $form): Schema
    {
        return $form
            ->schema(DeleteTeamForm::get(Filament::getTenant()))
            ->model(Filament::getTenant())
            ->statePath('deleteTeamData');
    }
}
