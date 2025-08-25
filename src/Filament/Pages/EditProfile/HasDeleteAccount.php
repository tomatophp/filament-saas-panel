<?php

namespace TomatoPHP\FilamentSaasPanel\Filament\Pages\EditProfile;

use Filament\Schemas\Schema;
use TomatoPHP\FilamentSaasPanel\Filament\Forms\DeleteAccountForm;

trait HasDeleteAccount
{
    public function deleteAccountForm(Schema $form): Schema
    {
        return $form
            ->schema(DeleteAccountForm::get())
            ->model($this->getUser())
            ->statePath('deleteAccountData');
    }
}
