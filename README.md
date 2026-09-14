![Screenshot](https://raw.githubusercontent.com/tomatophp/filament-saas-panel/master/arts/fadymondy-tomato-saas-panel.jpg)

# Filament SaaS Panel

[![Dependabot Updates](https://github.com/tomatophp/filament-saas-panel/actions/workflows/dependabot/dependabot-updates/badge.svg)](https://github.com/tomatophp/filament-saas-panel/actions/workflows/dependabot/dependabot-updates)
[![PHP Code Styling](https://github.com/tomatophp/filament-saas-panel/actions/workflows/fix-php-code-styling.yml/badge.svg)](https://github.com/tomatophp/filament-saas-panel/actions/workflows/fix-php-code-styling.yml)
[![Tests](https://github.com/tomatophp/filament-saas-panel/actions/workflows/tests.yml/badge.svg)](https://github.com/tomatophp/filament-saas-panel/actions/workflows/tests.yml)
[![Latest Stable Version](https://poser.pugx.org/tomatophp/filament-saas-panel/version.svg)](https://packagist.org/packages/tomatophp/filament-saas-panel)
[![License](https://poser.pugx.org/tomatophp/filament-saas-panel/license.svg)](https://packagist.org/packages/tomatophp/filament-saas-panel)
[![Downloads](https://poser.pugx.org/tomatophp/filament-saas-panel/d/total.svg)](https://packagist.org/packages/tomatophp/filament-saas-panel)

Ready to use SaaS panel for your customers with Jetstream teams: registration with OTP, team management and invitations, profile, browser sessions and API tokens. Works with your own User model (Filament Accounts is optional).

## Features

- [x] Login Page
- [x] Register with OTP
- [x] Login Check if Account Active or Blocked
- [x] Create Team Page
- [x] Edit Team Page
- [x] Team Members List
- [x] Team Invitation
- [x] Delete Team
- [x] Edit Profile
- [x] Change Profile Password
- [x] Browser Session Manager
- [x] Delete Account
- [x] API Tokens
- [x] Team Resource
- [ ] Integration With Filament Social Login
- [ ] Integration With Filament Two Factory Authentication
- [ ] Integration With Wave Themes/Plugins

## Screenshots

![Teams Resource](https://raw.githubusercontent.com/tomatophp/filament-saas-panel/master/arts/teams-light.png)
![Teams Resource Dark](https://raw.githubusercontent.com/tomatophp/filament-saas-panel/master/arts/teams-dark.png)
![Customer Panel](https://raw.githubusercontent.com/tomatophp/filament-saas-panel/master/arts/panel-light.png)
![Customer Panel Dark](https://raw.githubusercontent.com/tomatophp/filament-saas-panel/master/arts/panel-dark.png)
![Team Settings](https://raw.githubusercontent.com/tomatophp/filament-saas-panel/master/arts/team-settings-light.png)
![Team Settings Dark](https://raw.githubusercontent.com/tomatophp/filament-saas-panel/master/arts/team-settings-dark.png)
![Edit Profile](https://raw.githubusercontent.com/tomatophp/filament-saas-panel/master/arts/edit-profile-light.png)
![Edit Profile Dark](https://raw.githubusercontent.com/tomatophp/filament-saas-panel/master/arts/edit-profile-dark.png)
![API Tokens](https://raw.githubusercontent.com/tomatophp/filament-saas-panel/master/arts/api-tokens-light.png)
![API Tokens Dark](https://raw.githubusercontent.com/tomatophp/filament-saas-panel/master/arts/api-tokens-dark.png)
![Create Team](https://raw.githubusercontent.com/tomatophp/filament-saas-panel/master/arts/create-team-light.png)
![Create Team Dark](https://raw.githubusercontent.com/tomatophp/filament-saas-panel/master/arts/create-team-dark.png)

## Compatibility

| Package | Filament | Laravel | PHP |
|---------|----------|---------|-----|
| 5.x | 5.x | 12.x / 13.x | 8.3+ |
| 4.x | 4.x | 11.x / 12.x | 8.2+ |

## Requirements

The SaaS panel is built on [Laravel Jetstream](https://jetstream.laravel.com) teams and [Laravel Sanctum](https://laravel.com/docs/sanctum). Both are installed with the package; you do **not** need to run `php artisan jetstream:install`.

[Filament Accounts](https://github.com/tomatophp/filament-accounts) is **not** required. By default the panel works with your `App\Models\User` on the `users` table and the `web` guard; if your users live somewhere else (for example a Filament Accounts `accounts` table and guard) change `user_model`, `user_table`, `team_id_column` and `auth_guard` in the config (see [Config](#config)).

Before installing, make sure your app has:

1. a Filament panel for your customers, separate from your admin panel

```bash
php artisan filament:panel app
```

2. the Spatie media library `media` table (team and profile avatars). The package creates it when it is missing, so do not publish the media library migration again afterwards (a second `create_media_table` migration fails with `table media already exists` on a fresh database). If you want to publish it yourself, do it before installing this package:

```bash
php artisan vendor:publish --provider="Spatie\MediaLibrary\MediaLibraryServiceProvider" --tag="medialibrary-migrations"
```

3. the `sessions` table when you use the browser sessions manager (`SESSION_DRIVER=database`, the Laravel default)

## Installation

```bash
composer require tomatophp/filament-saas-panel
```

publish the team migrations (`teams`, `team_user`, `team_invitations` and the `current_team_id` / `profile_photo_path` columns on your user table). They use the configured user table, `users` by default, and skip tables and columns that already exist, so they are safe on an app that already has Jetstream teams.

```bash
php artisan vendor:publish --tag="filament-saas-teams-migrations"
```

publish the team models to `app/Models` (`Team`, `TeamInvitation`, `Membership`)

```bash
php artisan vendor:publish --tag="filament-saas-teams-models"
```

then run the install command, it runs the migrations (team tables, Sanctum `personal_access_tokens`, OTP columns)

```bash
php artisan filament-saas-panel:install
```

now prepare your user model: add the `InteractsWithTenant` trait (Jetstream teams, Sanctum tokens, profile photo, media) and the Filament contracts

```php
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Models\Contracts\HasTenants;
use Spatie\MediaLibrary\HasMedia;
use TomatoPHP\FilamentSaasPanel\Traits\InteractsWithTenant;

class User extends Authenticatable implements FilamentUser, HasAvatar, HasMedia, HasTenants
{
    use InteractsWithTenant;
}
```

`InteractsWithTenant::canAccessPanel()` allows every panel; override it in your model to keep customers out of your admin panel.

finally register the plugin on `/app/Providers/Filament/AppPanelProvider.php`

```php
->plugin(
    \TomatoPHP\FilamentSaasPanel\FilamentSaasPanelPlugin::make()
        ->editTeam()
        ->deleteTeam()
        ->showTeamMembers()
        ->teamInvitation()
        ->allowTenants()
        ->checkAccountStatusInLogin()
        ->APITokenManager()
        ->editProfile()
        ->editPassword()
        ->browserSessionManager()
        ->deleteAccount()
        ->editProfileMenu()
        ->registration()
        ->useOTPActivation()
)
```

on your admin panel provider if you like to have Team resource and features register this

```php
->plugin(
    \TomatoPHP\FilamentSaasPanel\FilamentSaasTeamsPlugin::make()
)
```

> Upgrading from 1.x / 4.x: the panel no longer needs Filament Accounts. If you saw `Route [filament.admin.resources.accounts.index] not defined` or `no such table: accounts`, publish the config and point `user_model` / `user_table` at the model and table you really use.

## Config

```bash
php artisan vendor:publish --tag="filament-saas-panel-config"
```

```php
'auth_guard' => 'web',
'user_model' => \App\Models\User::class,
'user_table' => 'users',
'team_model' => \App\Models\Team::class,
'team_id_column' => 'user_id',
'team_invitation_model' => \App\Models\TeamInvitation::class,
'membership_model' => \App\Models\Membership::class,
```

## Change Panel ID

if you like to change the panel name on your config just change `id` and `name` on `config/filament-saas-panel.php`

```php
return [
    "id" => "user"
];
```

you can publish it from this command

```bash
php artisan vendor:publish --tag="filament-saas-panel-config"
```

## Custom Pages

you can change any page you want on the panel using the config like this

```php
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
```

## Publish Assets

you can publish config file by use this command

```bash
php artisan vendor:publish --tag="filament-saas-panel-config"
```

you can publish views file by use this command

```bash
php artisan vendor:publish --tag="filament-saas-panel-views"
```

you can publish languages file by use this command

```bash
php artisan vendor:publish --tag="filament-saas-panel-lang"
```

you can publish migrations file by use this command

```bash
php artisan vendor:publish --tag="filament-saas-panel-migrations"
```

## Testing

if you like to run `PEST` testing just use this command

```bash
composer test
```

## Code Style

if you like to fix the code style just use this command

```bash
composer format
```

## PHPStan

if you like to check the code by `PHPStan` just use this command

```bash
composer analyse
```

## Other Filament Packages

Checkout our [Awesome TomatoPHP](https://github.com/tomatophp/awesome)
