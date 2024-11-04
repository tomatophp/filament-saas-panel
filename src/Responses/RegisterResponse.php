<?php

namespace TomatoPHP\FilamentSaasPanel\Responses;

use Filament\Http\Responses\Auth\Contracts\RegistrationResponse;

class RegisterResponse implements RegistrationResponse
{
    public function toResponse($request)
    {
        return redirect()->to(route('otp'));
    }
}
