![Screenshot](https://raw.githubusercontent.com/tomatophp/filament-saas-panel/master/arts/3x1io-tomato-saas-panel.jpg)

# Filament SaaS Panel

[![Dependabot Updates](https://github.com/tomatophp/filament-saas-panel/actions/workflows/dependabot/dependabot-updates/badge.svg)](https://github.com/tomatophp/filament-saas-panel/actions/workflows/dependabot/dependabot-updates)
[![PHP Code Styling](https://github.com/tomatophp/filament-saas-panel/actions/workflows/fix-php-code-styling.yml/badge.svg)](https://github.com/tomatophp/filament-saas-panel/actions/workflows/fix-php-code-styling.yml)
[![Tests](https://github.com/tomatophp/filament-saas-panel/actions/workflows/tests.yml/badge.svg)](https://github.com/tomatophp/filament-saas-panel/actions/workflows/tests.yml)
[![Latest Stable Version](https://poser.pugx.org/tomatophp/filament-saas-panel/version.svg)](https://packagist.org/packages/tomatophp/filament-saas-panel)
[![License](https://poser.pugx.org/tomatophp/filament-saas-panel/license.svg)](https://packagist.org/packages/tomatophp/filament-saas-panel)
[![Downloads](https://poser.pugx.org/tomatophp/filament-saas-panel/d/total.svg)](https://packagist.org/packages/tomatophp/filament-saas-panel)

Ready to use SaaS panel with integration of Filament Accounts Builder and JetStream teams

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
- [x] Teams Account Table Column
- [x] Teams Account Table Action
- [x] Teams Account Table Bulk Action
- [x] Teams Account Table Filter
- [x] Teams Account Form Component
- [ ] Integration With Filament Social Login
- [ ] Integration With Filament Two Factory Authentication
- [ ] Integration With Wave Themes/Plugins

## Screenshot Teams

![Team List](https://raw.githubusercontent.com/tomatophp/filament-saas-panel/master/arts/teams-list.png)
![Create Team](https://raw.githubusercontent.com/tomatophp/filament-saas-panel/master/arts/create-team.png)
![Edit Team](https://raw.githubusercontent.com/tomatophp/filament-saas-panel/master/arts/edit-team.png)

## Screenshot Account Team Components

![Account Team Form Component](https://raw.githubusercontent.com/tomatophp/filament-saas-panel/master/arts/team-form.png)
![Account Team Table Column](https://raw.githubusercontent.com/tomatophp/filament-saas-panel/master/arts/team-table.png)
![Account Team Table Action](https://raw.githubusercontent.com/tomatophp/filament-saas-panel/master/arts/teams-action.png)


## Screenshots Auth Process

![Login](https://raw.githubusercontent.com/tomatophp/filament-saas-panel/master/arts/login.png)
![OTP Screen](https://raw.githubusercontent.com/tomatophp/filament-saas-panel/master/arts/otp.png)
![Register](https://raw.githubusercontent.com/tomatophp/filament-saas-panel/master/arts/register.png)
![Create Tenant](https://raw.githubusercontent.com/tomatophp/filament-saas-panel/master/arts/create-tenant.png)

## Screenshot Panel

![Panel](https://raw.githubusercontent.com/tomatophp/filament-saas-panel/master/arts/panel.png)
![Panel Tenant Menu](https://raw.githubusercontent.com/tomatophp/filament-saas-panel/master/arts/panel-tenant-menu.png)

## Screenshot Edit Teams

![Team Invite](https://raw.githubusercontent.com/tomatophp/filament-saas-panel/master/arts/team-invite.png)
![Team Members](https://raw.githubusercontent.com/tomatophp/filament-saas-panel/master/arts/team-members.png)
![Team Settings](https://raw.githubusercontent.com/tomatophp/filament-saas-panel/master/arts/team-settings.png)
![Team Settings Not Owner](https://raw.githubusercontent.com/tomatophp/filament-saas-panel/master/arts/team-settings-not-owner.png)

## Screenshots Edit Profile

![Edit Profile](https://raw.githubusercontent.com/tomatophp/filament-saas-panel/master/arts/edit-profile.png)
![Change Password](https://raw.githubusercontent.com/tomatophp/filament-saas-panel/master/arts/change-password.png)
![Delete Modal](https://raw.githubusercontent.com/tomatophp/filament-saas-panel/master/arts/delete-modal.png)
![Logout Modal](https://raw.githubusercontent.com/tomatophp/filament-saas-panel/master/arts/logout-modal.png)
![Session & Delete Account](https://raw.githubusercontent.com/tomatophp/filament-saas-panel/master/arts/session-delete.png)

## Screenshot API Tokens

![API Tokens](https://raw.githubusercontent.com/tomatophp/filament-saas-panel/master/arts/api-tokens.png)
![Create Token](https://raw.githubusercontent.com/tomatophp/filament-saas-panel/master/arts/create-token.png)
![Token Modal](https://raw.githubusercontent.com/tomatophp/filament-saas-panel/master/arts/token-modal.png)

## Installation

```bash
composer require tomatophp/filament-saas-panel
```

now you need to publish teams migration

```bash
php artisan vendor:publish --tag="filament-saas-teams-migrations"
```

after install your package please run this command

```bash
php artisan filament-saas-panel:install
```

now you need to publish teams models and account model with injection of teams

```bash
php artisan vendor:publish --tag="filament-saas-teams-models"
php artisan vendor:publish --tag="filament-saas-account-model"
```

create a new panel for `app`

```bash
php artisan filament:panel app
```

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
        ->allowAccountTeamTableAction()
        ->allowAccountTeamTableBulkAction()
        ->allowAccountTeamFilter()
        ->allowAccountTeamFormComponent()
        ->allowAccountTeamTableColumn()
)
```

## Use On Existing Account Model

if you have `Account.php` published on your `/app/Models` folder and you don't need to publish it again just add this trait to your model

```php
use \TomatoPHP\FilamentSaasPanel\Traits\InteractsWithTenant;
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
