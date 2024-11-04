<?php

namespace TomatoPHP\FilamentSaasPanel\Tests\Models;

use Filament\Models\Contracts\HasAvatar;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Laravel\Jetstream\Events\TeamCreated;
use Laravel\Jetstream\Events\TeamDeleted;
use Laravel\Jetstream\Events\TeamUpdated;
use Laravel\Jetstream\Team as JetstreamTeam;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use TomatoPHP\FilamentAccounts\Models\Account;
use TomatoPHP\FilamentSaasPanel\Tests\Database\Factories\TeamFactory;

class Team extends JetstreamTeam implements HasAvatar, HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'account_id',
        'name',
        'personal_team',
    ];

    /**
     * The event map for the model.
     *
     * @var array<string, class-string>
     */
    protected $dispatchesEvents = [
        'created' => TeamCreated::class,
        'updated' => TeamUpdated::class,
        'deleted' => TeamDeleted::class,
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'personal_team' => 'boolean',
        ];
    }

    protected $appends = [
        'avatar',
    ];

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->getFirstMediaUrl('avatar') ?: null;
    }

    public function getAvatarAttribute(): string
    {
        return $this->getFirstMediaUrl('avatar') ?: 'https://ui-avatars.com/api/?name='.str($this->name)->replace(' ', '+')->toString().'&color=FFFFFF&background=020617';
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    public function accounts(): BelongsToMany
    {
        return $this->belongsToMany(Account::class, 'team_user', 'team_id', 'account_id');
    }

    protected static function newFactory(): TeamFactory
    {
        return TeamFactory::new();
    }
}
