<?php

namespace TomatoPHP\FilamentSaasPanel\Filament\Pages\EditProfile;

use Filament\Forms\Form;
use TomatoPHP\FilamentSaasPanel\Filament\Forms\DeleteAccountForm;

trait HasDeleteAccount
{
    public function deleteAccountForm(Form $form): Form
    {
        return $form
            ->schema(DeleteAccountForm::get())
            ->model($this->getUser())
            ->statePath('deleteAccountData');
    }
}
