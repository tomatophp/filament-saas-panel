<?php

namespace TomatoPHP\FilamentSaasPanel\Filament\Pages\EditTeam;

use Filament\Actions\Action;
use Filament\Support\Exceptions\Halt;

trait HasCancelTeamInvitation
{
    public function getCancelTeamInvitationAction(): Action
    {
        return Action::make('getCancelTeamInvitationAction')
            ->requiresConfirmation()
            ->color('danger')
            ->label(trans('filament-saas-panel::messages.teams.actions.cancel_invitation'))
            ->action(function (array $arguments) {
                $this->cancelTeamInvitation($arguments['invitation']);
            });
    }

    public function cancelTeamInvitation($invitationId)
    {
        try {
            if (! empty($invitationId)) {
                $model = config('filament-saas-panel.team_invitation_model');

                $model::whereKey($invitationId)->delete();
            }
        } catch (Halt $exception) {
            return;
        }

        $this->sendSuccessNotification();
    }
}
