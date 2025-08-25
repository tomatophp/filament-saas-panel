<?php

namespace TomatoPHP\FilamentSaasPanel\Filament\Resources;

use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use TomatoPHP\FilamentSaasPanel\Models\Team;

class TeamResource extends Resource
{
    protected static ?string $model = Team::class;

    protected static ?string $recordTitleAttribute = 'name';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user-group';

    public static function getLabel(): ?string
    {
        return trans('filament-accounts::messages.team.single');
    }

    public static function getNavigationLabel(): string
    {
        return trans('filament-accounts::messages.team.title');
    }

    public static function getPluralLabel(): ?string
    {
        return trans('filament-accounts::messages.team.title');
    }

    public static function getNavigationGroup(): ?string
    {
        return trans('filament-accounts::messages.group');
    }

    public static function form(Schema $form): Schema
    {
        return $form
            ->schema([
                Forms\Components\SpatieMediaLibraryFileUpload::make('avatar')
                    ->label(trans('filament-accounts::messages.team.columns.avatar'))
                    ->hiddenLabel()
                    ->alignCenter()
                    ->avatar()
                    ->collection('avatar')
                    ->image(),
                Forms\Components\TextInput::make('name')
                    ->label(trans('filament-accounts::messages.team.columns.name'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('account_id')
                    ->label(trans('filament-accounts::messages.team.columns.owner'))
                    ->relationship('owner', 'name')
                    ->preload()
                    ->searchable(),
                Forms\Components\Toggle::make('personal_team')
                    ->label(trans('filament-accounts::messages.team.columns.personal_team')),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('owner.name')
                    ->label(trans('filament-accounts::messages.team.columns.owner'))
                    ->sortable(),
                Tables\Columns\ImageColumn::make('avatar')
                    ->circular()
                    ->label(trans('filament-accounts::messages.team.columns.avatar'))
                    ->toggleable(),
                Tables\Columns\TextColumn::make('name')
                    ->label(trans('filament-accounts::messages.team.columns.name'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\IconColumn::make('personal_team')
                    ->label(trans('filament-accounts::messages.team.columns.personal_team'))
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('owner')
                    ->label(trans('filament-accounts::messages.team.columns.owner'))
                    ->searchable()
                    ->relationship('owner', 'name'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->defaultSort('id', 'desc')
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => \TomatoPHP\FilamentSaasPanel\Filament\Resources\TeamResource\Pages\ListTeams::route('/'),
        ];
    }
}
