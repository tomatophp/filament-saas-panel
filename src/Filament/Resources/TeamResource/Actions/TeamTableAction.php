<?php

namespace TomatoPHP\FilamentSaasPanel\Filament\Resources\TeamResource\Actions;

use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;

class TeamTableAction
{
    public static function make(): Action
    {
        return Action::make('teams')
            ->iconButton()
            ->color('info')
            ->tooltip(trans('filament-saas-panel::messages.actions.edit.label'))
            ->icon('heroicon-s-user-group')
            ->fillForm(fn ($record) => [
                'teams' => $record->teams->pluck('id')->toArray(),
            ])
            ->schema([
                Select::make('teams')
                    ->columnSpanFull()
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->relationship('teams', 'name'),
            ])
            ->action(function (array $data, $record) {
                Notification::make()
                    ->body(trans('filament-saas-panel::messages.actions.edit.notification'))
                    ->success()
                    ->send();
            });
    }
}
