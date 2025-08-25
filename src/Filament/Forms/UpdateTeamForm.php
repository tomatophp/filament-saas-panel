<?php

namespace TomatoPHP\FilamentSaasPanel\Filament\Forms;

use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;

class UpdateTeamForm
{
    public static function get($team): array
    {
        return [
            Forms\Components\ViewField::make('teamOwner')
                ->label(trans('filament-saas-panel::messages.teams.edit.owner'))
                ->hiddenLabel()
                ->view('filament-saas-panel::forms.components.team-owner', ['team' => $team]),
            SpatieMediaLibraryFileUpload::make('avatar')
                ->avatar()
                ->label(trans('filament-saas-panel::messages.teams.edit.avatar'))
                ->disabled(fn () => auth(config('filament-saas-panel.auth_guard'))->user()->id !== $team->{config('filament-saas-panel.team_id_column')})
                ->collection('avatar'),
            TextInput::make('name')
                ->label(trans('filament-saas-panel::messages.teams.edit.name'))
                ->disabled(fn () => auth(config('filament-saas-panel.auth_guard'))->user()->id !== $team->{config('filament-saas-panel.team_id_column')})
                ->required(),
            Action::make('editTeam')
                ->requiresConfirmation()
                ->label(trans('filament-saas-panel::messages.teams.edit.save'))
                ->submit('editTeamForm')
                ->color('primary'),
        ];
    }

    public static function sendErrorDeleteAccount(string $message): void
    {
        Notification::make()
            ->danger()
            ->title($message)
            ->send();
    }
}
