<?php

namespace TomatoPHP\FilamentSaasPanel\Filament\Resources\TeamResource\Table;

use Filament\Tables\Columns\ImageColumn;

class TeamColumn
{
    public static function make(): ImageColumn
    {
        return ImageColumn::make('teams.avatar')
            ->label(trans('filament-saas-panel::messages.column.teams'))
            ->circular()
            ->tooltip(fn ($record) => $record->teams()->pluck('name')->join(', '))
            ->stacked();
    }
}
