<?php

namespace TomatoPHP\FilamentSaasPanel\Filament\Pages\EditTeam;

use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Support\Exceptions\Halt;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use Laravel\Jetstream\Events\InvitingTeamMember;
use Laravel\Jetstream\Jetstream;

trait HasTeamInvitation
{
    public function getResendInvitationAction(): Action
    {
        return Action::make('getResendInvitationAction')
            ->requiresConfirmation()
            ->color('warning')
            ->label(trans('filament-saas-panel::messages.teams.actions.resend_invitation'))
            ->action(function (array $arguments) {
                $this->resendTeamInvitation($arguments['invitation']);
            });
    }

    public function resendTeamInvitation($invitationId)
    {
        try {
            $model = Jetstream::teamInvitationModel();

            $invitation = $model::whereKey($invitationId)->first();

            $mail = config('filament-saas-panel.team_invitation_mail');

            Mail::to($invitation->email)->send(new $mail($invitation));

            $account = config('filament-saas-panel.user_model')::where('email', $invitation->email)->first();

            if ($account) {
                Notification::make()
                    ->title(trans('filament-saas-panel::messages.teams.members.notifications.title'))
                    ->body(trans('filament-saas-panel::messages.teams.members.notifications.body', ['team' => $invitation->team->name]))
                    ->success()
                    ->actions([
                        Action::make('acceptInvitation')
                            ->label(trans('filament-saas-panel::messages.teams.members.notifications.accept'))
                            ->color('success')
                            ->markAsRead()
                            ->url(route('team-invitations.accept', ['invitation' => $invitation->id])),
                        Action::make('cancelInvitation')
                            ->label(trans('filament-saas-panel::messages.teams.members.notifications.cancel'))
                            ->color('danger')
                            ->url(route('team-invitations.cancel', ['invitation' => $invitation->id])),
                    ])
                    ->sendToDatabase($account);
            }
        } catch (Halt $exception) {
            return;
        }

        $this->sendSuccessNotification();
    }

    public function sendInvitation()
    {
        try {
            $data = $this->manageTeamMembersForm->getState();
            $this->manageTeamInvitations(Filament::getTenant(), $data);
        } catch (Halt $exception) {
            return;
        }

        $this->sendSuccessNotification();
    }

    protected function manageTeamInvitations(Model $record, array $data)
    {
        $user = auth(config('filament-saas-panel.auth_guard'))->user();
        $team = $record;
        $email = $data['email'];
        $role = $data['role'];

        InvitingTeamMember::dispatch($team, $email, $role);

        $invitation = $team->teamInvitations()->create([
            'email' => $email,
            'role' => $role,
        ]);

        $mail = config('filament-saas-panel.team_invitation_mail');

        Mail::to($email)->send(new $mail($invitation));

        $account = config('filament-saas-panel.user_model')::where('email', $email)->first();

        if ($account) {
            Notification::make()
                ->title(trans('filament-saas-panel::messages.teams.members.notifications.title'))
                ->body(trans('filament-saas-panel::messages.teams.members.notifications.body', ['team' => $team->name]))
                ->success()
                ->actions([
                    Action::make('acceptInvitation')
                        ->label(trans('filament-saas-panel::messages.teams.members.notifications.accept'))
                        ->color('success')
                        ->markAsRead()
                        ->url(route('accounts.team-invitations.accept', ['invitation' => $invitation->id])),
                    Action::make('cancelInvitation')
                        ->label(trans('filament-saas-panel::messages.teams.members.notifications.cancel'))
                        ->color('danger')
                        ->url(route('accounts.team-invitations.cancel', ['invitation' => $invitation->id])),
                ])
                ->sendToDatabase($account);
        }

    }
}
