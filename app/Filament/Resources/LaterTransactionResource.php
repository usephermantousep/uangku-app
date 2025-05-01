<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LaterTransactionResource\Pages;
use App\Filament\Resources\LaterTransactionResource\RelationManagers;
use App\Models\LaterTransaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LaterTransactionResource extends Resource
{
    protected static ?string $model = LaterTransaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListLaterTransactions::route('/'),
            'create' => Pages\CreateLaterTransaction::route('/create'),
            'view' => Pages\ViewLaterTransaction::route('/{record}'),
            'edit' => Pages\EditLaterTransaction::route('/{record}/edit'),
        ];
    }
}
