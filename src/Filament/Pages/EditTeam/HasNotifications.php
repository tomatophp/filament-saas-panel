<?php

namespace TomatoPHP\FilamentSaasPanel\Filament\Pages\EditTeam;

use Filament\Facades\Filament;
use Filament\Notifications\Notification;

trait HasNotifications
{
    private function sendSuccessNotification()
    {
        Notification::make()
            ->success()
            ->title(trans('filament-saas-panel::messages.saved_successfully'))
            ->send();

        $this->editTeamForm->fill(Filament::getTenant()->toArray());
        $this->deleteTeamFrom->fill(Filament::getTenant()->toArray());
        $this->manageTeamMembersForm->fill([]);
    }
}
