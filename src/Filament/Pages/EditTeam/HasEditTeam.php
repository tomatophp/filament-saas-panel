<?php

namespace TomatoPHP\FilamentSaasPanel\Filament\Pages\EditTeam;

use Filament\Facades\Filament;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Exceptions\Halt;
use Illuminate\Database\Eloquent\Model;
use TomatoPHP\FilamentSaasPanel\Filament\Forms\UpdateTeamForm;

trait HasEditTeam
{
    public function editTeamForm(Schema $form): Schema
    {
        return $form->schema([
            Section::make(trans('filament-saas-panel::messages.teams.edit.title'))
                ->description(trans('filament-saas-panel::messages.teams.edit.description'))
                ->schema(UpdateTeamForm::get(Filament::getTenant())),
        ])
            ->model(Filament::getTenant())
            ->statePath('editTeamData');
    }

    public function saveEditTeam()
    {
        try {
            $data = $this->editTeamForm->getState();

            $this->handleRecordUpdate(Filament::getTenant(), $data);
        } catch (Halt $exception) {
            return;
        }

        $this->sendSuccessNotification();
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $record->update($data);

        return $record;
    }
}
