<?php

namespace TomatoPHP\FilamentSaasPanel\Filament\Forms;

use Filament\Forms;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Section;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Hash;

class DeleteAccountForm
{
    public static function get(): array
    {
        return [
            Section::make(trans('filament-saas-panel::messages.profile.delete.delete_account'))
                ->description(trans('filament-saas-panel::messages.profile.delete.delete_account_description'))
                ->schema([
                    Forms\Components\ViewField::make('deleteAccount')
                        ->label(__('Delete Account'))
                        ->hiddenLabel()
                        ->view('filament-saas-panel::forms.components.delete-account-description'),
                    Actions::make([
                        Actions\Action::make('deleteAccount')
                            ->label(trans('filament-saas-panel::messages.profile.delete.delete_account'))
                            ->icon('heroicon-m-trash')
                            ->color('danger')
                            ->requiresConfirmation()
                            ->modalHeading(trans('filament-saas-panel::messages.profile.delete.delete_account'))
                            ->modalDescription(trans('filament-saas-panel::messages.profile.delete.are_you_sure'))
                            ->modalSubmitActionLabel(trans('filament-saas-panel::messages.profile.delete.yes_delete_it'))
                            ->form([
                                Forms\Components\TextInput::make('password')
                                    ->password()
                                    ->revealable()
                                    ->label(trans('filament-saas-panel::messages.profile.delete.password'))
                                    ->required(),
                            ])
                            ->action(function (array $data) {

                                if (! Hash::check($data['password'], auth('accounts')->user()->password)) {
                                    self::sendErrorDeleteAccount(trans('filament-saas-panel::messages.profile.delete.incorrect_password'));

                                    return;
                                }

                                auth('accounts')->user()?->update([
                                    'is_active' => false,
                                ]);

                                auth('accounts')->user()?->delete();
                            }),
                    ]),
                ]),
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
