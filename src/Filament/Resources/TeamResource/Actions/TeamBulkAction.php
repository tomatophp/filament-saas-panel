<?php

namespace TomatoPHP\FilamentSaasPanel\Filament\Resources\TeamResource\Actions;

use Filament\Actions\BulkAction;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Collection;
use TomatoPHP\FilamentSaasPanel\Models\Team;

class TeamBulkAction
{
    public static function make(): BulkAction
    {
        return BulkAction::make('teams')
            ->color('info')
            ->tooltip(trans('filament-saas-panel::messages.actions.edit.label'))
            ->label(trans('filament-saas-panel::messages.actions.edit.label'))
            ->icon('heroicon-s-user-group')
            ->schema([
                Select::make('teams')
                    ->columnSpanFull()
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->options(Team::query()->pluck('name', 'id')->toArray()),
            ])
            ->deselectRecordsAfterCompletion()
            ->action(function (array $data, Collection $records) {
                $records->each(function ($account) use ($data) {
                    $account->teams()->sync($data['teams']);
                });

                Notification::make()
                    ->body(trans('filament-saas-panel::messages.actions.edit.notification'))
                    ->success()
                    ->send();
            });
    }
}
