<?php

namespace TomatoPHP\FilamentSaasPanel\Filament\Forms;

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
                ->disabled(fn () => auth('accounts')->user()->id !== $team->account_id)
                ->collection('avatar'),
            TextInput::make('name')
                ->label(trans('filament-saas-panel::messages.teams.edit.name'))
                ->disabled(fn () => auth('accounts')->user()->id !== $team->account_id)
                ->required(),
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
