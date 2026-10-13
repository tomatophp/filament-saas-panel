# V5.0.1

- Team invitation links are signed and require the logged in user to be the invitee (accept) or the invitee / team owner / team admin (cancel); the signed GET shows a confirmation page and the change is a POST
- Invitation notifications use signed links
- Resend and cancel invitation actions on the team page are limited to the current team
- Unsigned invitation links stop working, resend the invitation to get a new link

# V5.0.0

- Support Filament v5 and Laravel 12 / 13 (laravel/jetstream ^5.0, laravel/sanctum ^4.0)
- Works without tomatophp/filament-accounts: the Fortify / Jetstream actions use the configured `user_model`, `user_table` and `auth_guard` (#16)
- Published team migrations use the configured user table (users by default) and skip tables and columns that already exist (#21)
- Install instructions list the prerequisites: User model contract and trait, team migrations and models, media library table (#21)
- Teams resource uses its own translations instead of the filament-accounts ones
- Fix deleting the account from the profile page on the default `web` guard
- Fix the remove member button on the team page for the team owner
- Team action and bulk action use the v5 `Filament\Actions` API

# V1.0.0

First release of the package
