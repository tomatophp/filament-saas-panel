<?php

namespace TomatoPHP\FilamentSaasPanel\Filament\Pages\EditTeam;

use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Support\Exceptions\Halt;
use TomatoPHP\FilamentSaasPanel\Support\InvitationAccess;

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

                $invitation = $model::whereKey($invitationId)
                    ->where('team_id', Filament::getTenant()?->getKey())
                    ->firstOrFail();

                abort_unless(InvitationAccess::canManage(auth(config('filament-saas-panel.auth_guard'))->user(), $invitation), 403);

                $invitation->delete();
            }
        } catch (Halt $exception) {
            return;
        }

        $this->sendSuccessNotification();
    }
}
