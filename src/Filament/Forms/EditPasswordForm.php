<?php

namespace TomatoPHP\FilamentSaasPanel\Filament\Forms;

use Filament\Actions\Action;
use Filament\Forms;
use Filament\Schemas\Components\Section;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class EditPasswordForm
{
    public static function get(): array
    {
        return [
            Section::make(trans('filament-saas-panel::messages.profile.password.title'))
                ->description(trans('filament-saas-panel::messages.profile.password.description'))
                ->schema([
                    Forms\Components\TextInput::make('current_password')
                        ->label(trans('filament-saas-panel::messages.profile.password.current_password'))
                        ->password()
                        ->required()
                        ->currentPassword()
                        ->revealable(),
                    Forms\Components\TextInput::make('password')
                        ->label(trans('filament-saas-panel::messages.profile.password.new_password'))
                        ->password()
                        ->required()
                        ->rule(Password::default())
                        ->autocomplete('new-password')
                        ->dehydrateStateUsing(fn ($state): string => Hash::make($state))
                        ->live(debounce: 500)
                        ->same('passwordConfirmation')
                        ->revealable(),
                    Forms\Components\TextInput::make('passwordConfirmation')
                        ->label(trans('filament-saas-panel::messages.profile.password.confirm_password'))
                        ->password()
                        ->required()
                        ->dehydrated(false)
                        ->revealable(),
                    Action::make('getUpdatePasswordFormActions')
                        ->label(trans('filament-saas-panel::messages.save'))
                        ->submit('editPasswordForm'),
                ]),
        ];
    }
}
