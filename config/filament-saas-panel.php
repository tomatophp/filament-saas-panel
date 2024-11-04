<?php

return [
    /**
     * --------------------------------------------------------------
     * Panel ID
     * --------------------------------------------------------------
     *
     * This is the ID of the panel.
     */
    'id' => 'app',

    /**
     * --------------------------------------------------------------
     * Panel Pages
     * --------------------------------------------------------------
     *
     * This is where you can define the pages that will be used in the panel.
     */
    'pages' => [
        'teams' => [
            'create' => \TomatoPHP\FilamentSaasPanel\Filament\Pages\CreateTeam::class,
            'edit' => \TomatoPHP\FilamentSaasPanel\Filament\Pages\EditTeam::class,
        ],
        'profile' => [
            'edit' => \TomatoPHP\FilamentSaasPanel\Filament\Pages\EditProfile::class,
        ],
        'auth' => [
            'login' => \TomatoPHP\FilamentSaasPanel\Filament\Pages\Auth\LoginAccount::class,
            'register' => \TomatoPHP\FilamentSaasPanel\Filament\Pages\Auth\RegisterAccount::class,
            'register-without-otp' => \TomatoPHP\FilamentSaasPanel\Filament\Pages\Auth\RegisterAccountWithoutOTP::class,
        ],
    ],
];
