<?php

namespace TomatoPHP\FilamentSaasPanel\Filament\Pages\Auth;

use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Auth\Events\Registered;
use Filament\Auth\Http\Responses\Contracts\RegistrationResponse;
use Filament\Auth\Pages\Register;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use TomatoPHP\FilamentSaasPanel\Events\SendOTP;
use TomatoPHP\FilamentSaasPanel\Responses\RegisterResponse;

class RegisterAccount extends Register
{
    protected static ?int $navigationSort = 2;

    public static function isShouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function getLabel(): string
    {
        return 'Create Account';
    }

    /**
     * @return array<int | string, string | Form>
     */
    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        $this->getNameFormComponent(),
                        $this->getEmailFormComponent(),
                        TextInput::make('phone')->label('Phone')->required()->unique(),
                        TextInput::make('username')->label('Username')->required()->unique(),
                        Hidden::make('loginBy')->default('email'),
                        $this->getPasswordFormComponent(),
                        $this->getPasswordConfirmationFormComponent(),
                    ])
                    ->statePath('data'),
            ),
        ];
    }

    public function register(): ?RegistrationResponse
    {
        try {
            $this->rateLimit(2);
        } catch (TooManyRequestsException $exception) {
            Notification::make()
                ->title(__('filament-panels::pages/auth/register.notifications.throttled.title', [
                    'seconds' => $exception->secondsUntilAvailable,
                    'minutes' => ceil($exception->secondsUntilAvailable / 60),
                ]))
                ->body(array_key_exists('body', __('filament-panels::pages/auth/register.notifications.throttled') ?: []) ? __('filament-panels::pages/auth/register.notifications.throttled.body', [
                    'seconds' => $exception->secondsUntilAvailable,
                    'minutes' => ceil($exception->secondsUntilAvailable / 60),
                ]) : null)
                ->danger()
                ->send();

            return null;
        }

        $user = DB::transaction(function () {
            $data = $this->form->getState();

            return $this->getUserModel()::create($data);
        });

        event(new Registered($user));

        $user->otp_code = substr(number_format(time() * rand(), 0, '', ''), 0, 6);
        $user->save();

        event(new SendOTP(config('filament-saas-panel.user_model'), $user->id));

        session()->put('user_email', $user->email);

        return app(RegisterResponse::class);
    }
}
