<?php

namespace TomatoPHP\FilamentSaasPanel\Traits;

use Filament\Panel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\InteractsWithMedia;

trait InteractsWithTenant
{
    use HasApiTokens;
    use HasProfilePhoto;
    use HasTeams;
    use InteractsWithMedia;
    use TwoFactorAuthenticatable;

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    public function getTenants(Panel $panel): Collection
    {
        return $this->teams;
    }

    public function canAccessTenant(Model $tenant): bool
    {
        return $this->teams()->whereKey($tenant)->exists();
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return (! empty($this->getFirstMediaUrl('avatar')) ? url($this->getFirstMediaUrl('avatar')) : null) ?: 'https://ui-avatars.com/api/?name='.str($this->name)->replace(' ', '+')->toString().'&color=FFFFFF&background=020617';
    }
}
