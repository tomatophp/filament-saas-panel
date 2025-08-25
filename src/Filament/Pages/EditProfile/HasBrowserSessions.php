<?php

namespace TomatoPHP\FilamentSaasPanel\Filament\Pages\EditProfile;

use Filament\Schemas\Schema;
use TomatoPHP\FilamentSaasPanel\Filament\Forms\BrowserSessionsForm;

trait HasBrowserSessions
{
    public function browserSessionsForm(Schema $form): Schema
    {
        return $form
            ->schema(BrowserSessionsForm::get());
    }
}
