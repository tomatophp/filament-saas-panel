<?php

namespace TomatoPHP\FilamentSaasPanel\Filament\Resources\TeamResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use TomatoPHP\FilamentSaasPanel\Filament\Resources\TeamResource;

class ListTeams extends ManageRecords
{
    protected static string $resource = TeamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
