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

    /**
     * --------------------------------------------------------------
     * Auth Guard
     * --------------------------------------------------------------
     *
     * This is the guard that will be used to authenticate the user.
     */
    'auth_guard' => 'web',

    /**
     * --------------------------------------------------------------
     * User Model
     * --------------------------------------------------------------
     *
     * This is the model that will be used to interact with the user.
     */
    'user_model' => \App\Models\User::class,

    /**
     * --------------------------------------------------------------
     * User Table
     * --------------------------------------------------------------
     *
     * This is the table that will be used to interact with the user.
     */
    'user_table' => 'users',

    /**
     * --------------------------------------------------------------
     * Team Model
     * --------------------------------------------------------------
     *
     * This is the model that will be used to interact with the team.
     */
    'team_model' => \App\Models\Team::class,

    /**
     * --------------------------------------------------------------
     * Team ID Column
     * --------------------------------------------------------------
     *
     * This is the column that will be used to identify the team owner.
     */
    'team_id_column' => 'user_id',

    /**
     * --------------------------------------------------------------
     * Team Invitation Model
     * --------------------------------------------------------------
     *
     * This is the model that will be used to interact with the team invitation.
     */
    'team_invitation_model' => \App\Models\TeamInvitation::class,

    /**
     * --------------------------------------------------------------
     * Membership Model
     * --------------------------------------------------------------
     *
     * This is the model that will be used to interact with the membership.
     */
    'membership_model' => \App\Models\Membership::class,

    /**
     * --------------------------------------------------------------
     * Registration URL
     * --------------------------------------------------------------
     *
     * This is the URL that will be used to register a new user.
     */
    'registration_url' => 'register',

    /**
     * --------------------------------------------------------------
     * Registration URL
     * --------------------------------------------------------------
     *
     * This is the URL that will be used to register a new user.
     */
    'login_url' => 'login',

    /**
     * --------------------------------------------------------------
     * Team Invitation Mail
     * --------------------------------------------------------------
     *
     * This is the mail that will be used to send the team invitation.
     */
    'team_invitation_mail' => \TomatoPHP\FilamentSaasPanel\Mail\TeamInvitation::class,

    /**
     * --------------------------------------------------------------
     * Team Invitation Mail View
     * --------------------------------------------------------------
     *
     * This is the view that will be used to send the team invitation.
     */
    'team_invitation_mail_view' => 'filament-saas-panel::emails.team-invitation',

    /**
     * --------------------------------------------------------------
     * OTP Rate Limit
     * --------------------------------------------------------------
     *
     * This is the rate limit for the OTP.
     */
    'otp_rate_limit' => 5,

    /**
     * --------------------------------------------------------------
     * Throttle OTP
     * --------------------------------------------------------------
     *
     * This is the throttle for the OTP.
     */
    'throttle_otp' => 5,
];
