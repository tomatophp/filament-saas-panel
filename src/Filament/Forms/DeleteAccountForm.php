<?php

namespace TomatoPHP\FilamentSaasPanel\Filament\Forms;

use Filament\Actions\Action;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

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
                    Action::make('deleteAccount')
                        ->label(trans('filament-saas-panel::messages.profile.delete.delete_account'))
                        ->icon('heroicon-m-trash')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalHeading(trans('filament-saas-panel::messages.profile.delete.delete_account'))
                        ->modalDescription(trans('filament-saas-panel::messages.profile.delete.are_you_sure'))
                        ->modalSubmitActionLabel(trans('filament-saas-panel::messages.profile.delete.yes_delete_it'))
                        ->schema([
                            Forms\Components\TextInput::make('password')
                                ->password()
                                ->revealable()
                                ->label(trans('filament-saas-panel::messages.profile.delete.password'))
                                ->required(),
                        ])
                        ->action(fn (array $data, Component $livewire) => static::deleteAccount($data, $livewire)),
                ]),
        ];
    }

    /**
     * Delete the signed-in user on the panel's auth guard after checking the password.
     */
    public static function deleteAccount(array $data, ?Component $livewire = null): bool
    {
        $guard = auth(config('filament-saas-panel.auth_guard'));
        $user = $guard->user();

        if (! $user || ! Hash::check($data['password'] ?? '', $user->password)) {
            self::sendErrorDeleteAccount(trans('filament-saas-panel::messages.profile.delete.incorrect_password'));

            return false;
        }

        // Log out before deleting: logging out cycles the remember token and saves the user,
        // which would insert the deleted row again.
        $guard->logout();

        $user->delete();

        $livewire?->redirect(filament()->getLoginUrl() ?? url('/'));

        return true;
    }

    public static function sendErrorDeleteAccount(string $message): void
    {
        Notification::make()
            ->danger()
            ->title($message)
            ->send();
    }
}
