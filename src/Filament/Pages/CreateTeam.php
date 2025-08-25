<?php

namespace TomatoPHP\FilamentSaasPanel\Filament\Pages;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Tenancy\RegisterTenant;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;

class CreateTeam extends RegisterTenant
{
    public static function registerNavigationItems()
    {
        return [];
    }

    public static function getCluster() {}

    public static function getLabel(): string
    {
        return 'Create Team';
    }

    public function form(Schema $form): Schema
    {
        return $form
            ->schema([
                FileUpload::make('avatar')
                    ->disk(config('filesystems.default'))
                    ->alignCenter()
                    ->avatar(),
                TextInput::make('name')
                    ->default(auth(config('filament-saas-panel.auth_guard'))->user()->teams()->count() > 0 ? null : auth(config('filament-saas-panel.auth_guard'))->user()->name."'s Team"),
            ]);
    }

    protected function handleRegistration(array $data): Model
    {
        $newTeam = app(\TomatoPHP\FilamentSaasPanel\Actions\Jetstream\CreateTeam::class)
            ->create(auth(config('filament-saas-panel.auth_guard'))->user(), $data);

        if (isset($data['avatar'])) {
            $newTeam->addMediaFromDisk($data['avatar'], config('filesystems.default'))
                ->usingName($data['name'])
                ->toMediaCollection('avatar');
        }

        return $newTeam;
    }
}
