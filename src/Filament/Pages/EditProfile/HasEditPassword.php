<?php

namespace TomatoPHP\FilamentSaasPanel\Filament\Pages\EditProfile;

use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Schemas\Schema;
use Filament\Support\Exceptions\Halt;
use TomatoPHP\FilamentSaasPanel\Filament\Forms\EditPasswordForm;

trait HasEditPassword
{
    public function editPasswordForm(Schema $form): Schema
    {
        return $form
            ->schema(EditPasswordForm::get())
            ->model($this->getUser())
            ->statePath('passwordData');
    }

    protected function getUpdatePasswordFormActions(): array
    {
        return [
            Action::make('getUpdatePasswordFormActions')
                ->label(trans('filament-saas-panel::messages.save'))
                ->submit('editPasswordForm'),
        ];
    }

    public function updatePassword(): void
    {
        try {
            $data = $this->editPasswordForm->getState();

            $this->handleRecordUpdate($this->getUser(), $data);
        } catch (Halt $exception) {
            return;
        }

        if (request()->hasSession() && array_key_exists('password', $data)) {
            request()->session()->put([
                'password_hash_'.Filament::getAuthGuard() => $data['password'],
            ]);
        }

        $this->editPasswordForm->fill();

        $this->sendSuccessNotification();
    }
}
