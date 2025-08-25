<?php

namespace TomatoPHP\FilamentSaasPanel\Filament\Pages\EditTeam;

use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Support\Exceptions\Halt;
use Laravel\Jetstream\Events\TeamMemberRemoved;

trait HasLeavingTeam
{
    public function getLeavingTeamAction(): Action
    {
        return Action::make('getLeavingTeamAction')
            ->requiresConfirmation()
            ->link()
            ->color('danger')
            ->label(trans('filament-saas-panel::messages.teams.members.leave_team'))
            ->action(function (array $arguments) {
                $this->removeMember($arguments['user']);
            });
    }

    public function getRemoveMemberAction(): Action
    {
        return Action::make('getRemoveMemberAction')
            ->requiresConfirmation()
            ->link()
            ->color('danger')
            ->label(trans('filament-saas-panel::messages.teams.members.remove_member'))
            ->action(function (array $arguments) {
                $this->removeMember($arguments['user']);
            });
    }

    public function removeMember($user)
    {
        $teamMember = config('filament-saas-panel.user_model')::find($user);
        try {
            Filament::getTenant()->removeUser($teamMember);
            TeamMemberRemoved::dispatch(Filament::getTenant(), $teamMember);
            $teamMember->current_team_id = $teamMember->teams()->first()?->id ?? null;
        } catch (Halt $exception) {
            return;
        }

        $this->sendSuccessNotification();

        return redirect()->to(Filament::getCurrentOrDefaultPanel()->getUrl());
    }
}
