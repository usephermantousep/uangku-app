<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;
use App\Models\Transaction;
use App\Util\DbMapping;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Pages\Page;
use Filament\Resources\Pages\ViewRecord;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Select::make('is_income')
                            ->label('Type')
                            ->options(
                                DbMapping::getSelectIsIncome()
                            )
                            ->default(0)
                            ->required()
                            ->native(false),
                        Select::make('category_id')
                            ->relationship('category', 'name')
                            ->native(false)
                            ->preload()
                            ->required(),
                        Select::make('mode_of_payment_id')
                            ->relationship('modeOfPayment', 'name')
                            ->native(false)
                            ->preload()
                            ->required(),
                        DatePicker::make('transaction_date')
                            ->required()
                            ->native(false)
                            ->date()
                            ->format('Y-m-d')
                            ->default(now()),
                        TextInput::make('amount')
                            ->prefix('Rp')
                            ->mask(RawJs::make("\$money(\$input, ',', '.')")),
                        TextInput::make('description')
                            ->required()
                            ->maxLength(255),
                    ])
                    ->columns(3),
                Section::make()
                    ->schema([
                        Select::make('created_by')
                            ->relationship('createdBy', 'name'),
                        Select::make('updated_by')
                            ->relationship('updatedBy', 'name'),
                    ])
                    ->columns(2)
                    ->hidden(fn(Page $livewire) => !($livewire instanceof ViewRecord))
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('transaction_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('category.name')
                    ->label('Category'),
                TextColumn::make('modeOfPayment.name')
                    ->label('Mode of Payment'),
                TextColumn::make('amount')
                    ->formatStateUsing(fn(string $state): string =>
                    'Rp ' . number_format($state, thousands_separator: '.'))
                    ->sortable(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'view' => Pages\ViewTransaction::route('/{record}'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
}
