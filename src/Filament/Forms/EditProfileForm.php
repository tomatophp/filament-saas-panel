<?php

namespace TomatoPHP\FilamentSaasPanel\Filament\Forms;

use Filament\Forms;

class EditProfileForm
{
    public static function get(): array
    {
        return [
            Forms\Components\Section::make(trans('filament-saas-panel::messages.profile.edit.title'))
                ->description(trans('filament-saas-panel::messages.profile.edit.description'))
                ->schema([
                    Forms\Components\SpatieMediaLibraryFileUpload::make('avatar')
                        ->avatar()
                        ->alignCenter()
                        ->circleCropper()
                        ->collection('avatar')
                        ->columnSpan(2)
                        ->label(trans('filament-saas-panel::messages.profile.edit.avatar')),
                    Forms\Components\TextInput::make('name')
                        ->columnSpan(2)
                        ->label(trans('filament-saas-panel::messages.profile.edit.name'))
                        ->required(),
                    Forms\Components\TextInput::make('email')
                        ->columnSpan(2)
                        ->label(trans('filament-saas-panel::messages.profile.edit.email'))
                        ->email()
                        ->required()
                        ->unique(ignoreRecord: true),
                ]),
        ];
    }
}
