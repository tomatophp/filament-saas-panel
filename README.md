![Screenshot](https://raw.githubusercontent.com/tomatophp/filament-saas-panel/master/art/screenshot.jpg)

# Filament saas panel

[![Latest Stable Version](https://poser.pugx.org/tomatophp/filament-saas-panel/version.svg)](https://packagist.org/packages/tomatophp/filament-saas-panel)
[![License](https://poser.pugx.org/tomatophp/filament-saas-panel/license.svg)](https://packagist.org/packages/tomatophp/filament-saas-panel)
[![Downloads](https://poser.pugx.org/tomatophp/filament-saas-panel/d/total.svg)](https://packagist.org/packages/tomatophp/filament-saas-panel)

Ready to use SaaS panel with integration of Filament Accounts Builder and JetStream teams

## Installation

```bash
composer require tomatophp/filament-saas-panel
```
after install your package please run this command

```bash
php artisan filament-saas-panel:install
```

finally register the plugin on `/app/Providers/Filament/AdminPanelProvider.php`

```php
->plugin(
    \TomatoPHP\FilamentSaasPanel\FilamentSaasPanelPlugin::make()
        ->checkAccountStatusInLogin()
        ->APITokenManager()
        ->editProfile()
        ->editPassword()
        ->browserSessionManager()
        ->deleteAccount()
        ->editProfileMenu()
        ->registration()
        ->useOTPActivation(),
)
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
