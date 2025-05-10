<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Filament\Resources\UserResource\RelationManagers\FamilyRelationManager;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function getLabel(): ?string
    {
        return __('global.user');
    }

    public static function getPluralLabel(): ?string
    {
        return __('global.user');
    }

    public static function getNavigationLabel(): string
    {
        return __('global.user');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('global.settings');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->label(__('global.name'))
                            ->placeholder(__('global.enter_placeholder', ['attribute' => __('global.name')])),
                        TextInput::make('email')
                            ->required()
                            ->label('Email')
                            ->placeholder(__('global.enter_placeholder', ['attribute' => 'Email']))
                            ->email(),
                        TextInput::make('password')
                            ->type('password')
                            ->required()
                            ->label('Password')
                            ->placeholder(__('global.enter_placeholder', ['attribute' => 'Password']))
                            ->dehydrated(fn($state) => ! blank($state)),
                    ])
                    ->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('global.name'))
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),

            ])
            ->bulkActions([]);
    }

    public static function getRelations(): array
    {
        return [
            FamilyRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
