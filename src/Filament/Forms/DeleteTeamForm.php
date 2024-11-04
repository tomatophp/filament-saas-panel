<?php

namespace TomatoPHP\FilamentSaasPanel\Filament\Forms;

use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Section;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DeleteTeamForm
{
    public static function get($team): array
    {
        return [
            Section::make(trans('filament-saas-panel::messages.profile.delete-team.title'))
                ->description(trans('filament-saas-panel::messages.profile.delete-team.description'))
                ->schema([
                    Forms\Components\ViewField::make('deleteTeam')
                        ->label(__('Delete Team'))
                        ->hiddenLabel()
                        ->view('filament-saas-panel::forms.components.delete-team-description'),
                    Actions::make([
                        Actions\Action::make('deleteAccount')
                            ->label(trans('filament-saas-panel::messages.profile.delete-team.delete'))
                            ->icon('heroicon-m-trash')
                            ->color('danger')
                            ->requiresConfirmation()
                            ->modalHeading(trans('filament-saas-panel::messages.profile.delete-team.delete_account'))
                            ->modalDescription(trans('filament-saas-panel::messages.profile.delete-team.are_you_sure'))
                            ->modalSubmitActionLabel(trans('filament-saas-panel::messages.profile.delete-team.yes_delete_it'))
                            ->form([
                                Forms\Components\TextInput::make('password')
                                    ->password()
                                    ->revealable()
                                    ->label(trans('filament-saas-panel::messages.profile.delete-team.password'))
                                    ->required(),
                            ])
                            ->action(function (array $data) use ($team) {

                                if (! Hash::check($data['password'], Auth::user()->password)) {
                                    self::sendErrorDeleteAccount(trans('filament-saas-panel::messages.profile.delete-team.incorrect_password'));

                                    return;
                                }

                                $team?->delete();

                                Notification::make()
                                    ->title('Team deleted')
                                    ->body('The team has been deleted successfully.')
                                    ->success()
                                    ->send();

                                return redirect()->to(url(Filament::getCurrentPanel()->getId()));
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
