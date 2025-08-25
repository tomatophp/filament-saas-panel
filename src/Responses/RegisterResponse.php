<?php

namespace TomatoPHP\FilamentSaasPanel\Responses;

use Filament\Auth\Http\Responses\Contracts\RegistrationResponse;

class RegisterResponse implements RegistrationResponse
{
    public function toResponse($request)
    {
        return redirect()->to(route('otp'));
    }
}
