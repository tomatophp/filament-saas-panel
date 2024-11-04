<?php

namespace TomatoPHP\FilamentSaasPanel\Filament\Pages\EditProfile;

use Filament\Forms\Form;
use TomatoPHP\FilamentSaasPanel\Filament\Forms\BrowserSessionsForm;

trait HasBrowserSessions
{
    public function browserSessionsForm(Form $form): Form
    {
        return $form
            ->schema(BrowserSessionsForm::get());
    }
}
