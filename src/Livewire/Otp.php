<?php

namespace TomatoPHP\FilamentSaasPanel\Livewire;

use Carbon\Carbon;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Pages\SimplePage;
use Filament\Schemas\Schema;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use TomatoPHP\FilamentSaasPanel\Events\SendOTP;

class Otp extends SimplePage implements HasActions
{
    use InteractsWithActions;
    use InteractsWithFormActions;
    use WithRateLimiting;

    protected string $view = 'filament-saas-panel::livewire.otp';

    /**
     * @var array<string, mixed> | null
     */
    public ?array $data = [];

    public function mount(): void
    {
        if (auth(config('filament-saas-panel.auth_guard'))->check()) {
            redirect()->intended(config('filament-saas-panel.id'));
        }
        if (! session()->has('user_email')) {
            redirect()->to('/login');
        }

        $this->data['email'] = session('user_email');
        $this->form->fill([
            'email' => session('user_email'),
        ]);
    }

    public function form(Schema $form): Schema
    {
        return $form->schema([
            Hidden::make('email'),
            TextInput::make('otp')
                ->hint('Don\'t receive the code?')
                ->hintAction($this->getResendFormAction())
                ->label('OTP Code')
                ->numeric()
                ->maxLength(6)
                ->autocomplete('current-password')
                ->required()
                ->extraInputAttributes(['tabindex' => 2]),
        ])->statePath('data');
    }

    public function resend()
    {
        try {
            $this->rateLimit(5);
        } catch (TooManyRequestsException $exception) {
            Notification::make()
                ->title(__('filament-panels::pages/auth/login.notifications.throttled.title', [
                    'seconds' => $exception->secondsUntilAvailable,
                    'minutes' => ceil($exception->secondsUntilAvailable / 60),
                ]))
                ->body(array_key_exists('body', __('filament-panels::pages/auth/login.notifications.throttled') ?: []) ? __('filament-panels::pages/auth/login.notifications.throttled.body', [
                    'seconds' => $exception->secondsUntilAvailable,
                    'minutes' => ceil($exception->secondsUntilAvailable / 60),
                ]) : null)
                ->danger()
                ->send();

            return null;
        }

        $findAccountWithEmail = config('filament-saas-panel.user_model')::query()
            ->where('email', $this->data['email'])
            ->first();

        if (! $findAccountWithEmail) {
            $this->throwFailureOtpException();
        }

        $findAccountWithEmail->otp_code = rand(100000, 999999);
        $findAccountWithEmail->save();

        event(new SendOTP(config('filament-saas-panel.user_model'), $findAccountWithEmail->id));

        Notification::make()
            ->title('OTP Send')
            ->body('OTP code has been sent to your email address.')
            ->success()
            ->send();

        return null;
    }

    public function authenticate()
    {
        try {
            $this->rateLimit(config('filament-saas-panel.otp_rate_limit'));
        } catch (TooManyRequestsException $exception) {
            Notification::make()
                ->title(__('filament-panels::pages/auth/login.notifications.throttled.title', [
                    'seconds' => $exception->secondsUntilAvailable,
                    'minutes' => ceil($exception->secondsUntilAvailable / 60),
                ]))
                ->body(array_key_exists('body', __('filament-panels::pages/auth/login.notifications.throttled') ?: []) ? __('filament-panels::pages/auth/login.notifications.throttled.body', [
                    'seconds' => $exception->secondsUntilAvailable,
                    'minutes' => ceil($exception->secondsUntilAvailable / 60),
                ]) : null)
                ->danger()
                ->send();

            return null;
        }

        $data = $this->form->getState();

        $findAccountWithEmailAndOTP = config('filament-saas-panel.user_model')::query()
            ->where('email', $data['email'])
            ->where('otp_code', $data['otp'])
            ->first();

        if (! $findAccountWithEmailAndOTP) {
            $this->throwFailureOtpException();
        }

        Auth::guard(config('filament-saas-panel.auth_guard'))->login($findAccountWithEmailAndOTP);

        if ($findAccountWithEmailAndOTP) {
            $findAccountWithEmailAndOTP->otp_code = null;
            $findAccountWithEmailAndOTP->otp_activated_at = Carbon::now();
            $findAccountWithEmailAndOTP->is_active = true;
            $findAccountWithEmailAndOTP->is_login = true;
            $findAccountWithEmailAndOTP->last_login = Carbon::now();
            $findAccountWithEmailAndOTP->save();
        }

        session()->regenerate();

        return redirect()->to(config('filament-saas-panel.id'));
    }

    protected function throwFailureOtpException(): never
    {
        throw ValidationException::withMessages([
            'data.otp' => trans('filament-saas-panel::messages.otp.otp_not_correct'),
        ]);
    }

    public function getTitle(): string|Htmlable
    {
        return trans('filament-saas-panel::messages.otp.title');
    }

    public function getSubHeading(): string
    {
        return trans('filament-saas-panel::messages.otp.subheading');
    }

    public function getHeading(): string|Htmlable
    {
        return trans('filament-saas-panel::messages.otp.heading');
    }

    /**
     * @return array<Action | ActionGroup>
     */
    protected function getFormActions(): array
    {
        return [
            $this->getAuthenticateFormAction(),
        ];
    }

    protected function getAuthenticateFormAction(): Action
    {
        return Action::make('authenticate')
            ->label('Verify OTP')
            ->submit('authenticate');
    }

    protected function getResendFormAction(): Action
    {
        return Action::make('getResendFormAction')
            ->link()
            ->label(trans('filament-saas-panel::messages.otp.resend_otp'))
            ->color('warning')
            ->action('resend');
    }

    protected function hasFullWidthFormActions(): bool
    {
        return true;
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function getCredentialsFromFormData(array $data): array
    {
        return [
            'email' => $data['email'],
            'otp_code' => $data['otp'],
        ];
    }
}
