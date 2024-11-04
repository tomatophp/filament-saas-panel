<?php

namespace TomatoPHP\FilamentSaasPanel\Filament\Pages;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use TomatoPHP\FilamentSaasPanel\Filament\Pages\EditProfile\HasBrowserSessions;
use TomatoPHP\FilamentSaasPanel\Filament\Pages\EditProfile\HasDeleteAccount;
use TomatoPHP\FilamentSaasPanel\Filament\Pages\EditProfile\HasEditPassword;
use TomatoPHP\FilamentSaasPanel\Filament\Pages\EditProfile\HasEditProfile;

class EditProfile extends Page implements HasForms
{
    use HasBrowserSessions;
    use HasDeleteAccount;
    use HasEditPassword;
    use HasEditProfile;
    use InteractsWithForms;

    protected static string $view = 'filament-saas-panel::teams.edit-profile';

    protected ?string $maxWidth = '6xl';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public function getTitle(): string
    {
        return trans('filament-saas-panel::messages.profile.title');
    }

    public static function getNavigationLabel(): string
    {
        return trans('filament-saas-panel::messages.profile.title');
    }

    public static function canAccess(): bool
    {
        return true;
    }

    public static function shouldShowDeleteAccountForm()
    {
        return true;
    }

    public static function shouldShowBrowserSessionsForm()
    {
        return true;
    }

    public static function shouldShowSanctumTokens()
    {
        return true;
    }

    public ?array $profileData = [];

    public ?array $passwordData = [];

    public function mount(): void
    {
        $this->fillForms();
    }

    protected function getForms(): array
    {
        return [
            'editProfileForm',
            'editPasswordForm',
            'deleteAccountForm',
            'browserSessionsForm',
        ];
    }

    protected function fillForms(): void
    {
        $data = $this->getUser()->attributesToArray();

        $this->editProfileForm->fill($data);
        $this->editPasswordForm->fill();
    }

    public function getUser()
    {
        return auth(filament()->getPlugin('filament-saas-panel')->authGuard)->user();
    }

    public function sendSuccessNotification()
    {
        Notification::make()
            ->title('Success')
            ->success()
            ->send();
    }
}
