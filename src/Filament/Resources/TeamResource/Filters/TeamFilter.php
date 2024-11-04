<?php

namespace TomatoPHP\FilamentSaasPanel\Filament\Resources\TeamResource\Filters;

use Filament\Tables\Filters\SelectFilter;

class TeamFilter
{
    public static function make(): SelectFilter
    {
        return SelectFilter::make('teams')
            ->label(trans('filament-saas-panel::messages.filter'))
            ->searchable()
            ->preload()
            ->multiple()
            ->relationship('teams', 'name');
    }
}
