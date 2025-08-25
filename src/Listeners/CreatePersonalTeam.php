<?php

namespace TomatoPHP\FilamentSaasPanel\Listeners;

use Filament\Auth\Events\Registered;

class CreatePersonalTeam
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Registered $event): void {}
}
