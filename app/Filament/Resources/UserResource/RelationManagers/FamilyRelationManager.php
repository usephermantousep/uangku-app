<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Models\Family;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FamilyRelationManager extends RelationManager
{
    protected static string $relationship = 'family';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('global.name'))
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('global.family');
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('global.name')),
            ])
            ->filters([])
            ->headerActions([
                AttachAction::make('family')
                    ->recordSelectOptionsQuery(fn(Builder $query) =>
                    $query->whereAttachedTo(auth()->user()))
                    ->preloadRecordSelect()
            ])
            ->actions([
                Tables\Actions\DetachAction::make()
                    ->hidden(fn(Family $record) =>
                    $record->family_id == Filament::getTenant()->id &&
                        $this->getOwnerRecord()->id == auth()->id()),
            ])
            ->bulkActions([]);
    }
}
