<?php

namespace TomatoPHP\FilamentSaasPanel\Filament\Resources\TeamResource\Form;

use Filament\Forms\Components\Select;

class TeamComponent
{
    public static function make(): Select
    {
        return Select::make('teams')
            ->label(trans('filament-saas-panel::messages.column.teams'))
            ->columnSpanFull()
            ->multiple()
            ->searchable()
            ->preload()
            ->relationship('teams', 'name');
    }
}
