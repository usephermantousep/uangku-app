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
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Pages\Page;
use Filament\Resources\Pages\ViewRecord;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-m-banknotes';
    protected static ?string $navigationGroup = 'Transactions';

    protected static ?int $navigationSort = 0;

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
                            ->live()
                            ->required()
                            ->native(false)
                            ->afterStateUpdated(
                                fn(Set $set) => $set('category_id', null)
                            ),
                        Select::make('category_id')
                            ->relationship(
                                'category',
                                'name',
                                fn(Builder $query, Get $get) =>
                                $query->where('categories.is_income', $get('is_income'))
                            )
                            ->native(false)
                            ->preload()
                            ->reactive()
                            ->searchable()
                            ->required(),
                        Select::make('mode_of_payment_id')
                            ->relationship(
                                'modeOfPayment',
                                'name',
                                fn(Builder $query) =>
                                $query->where('mode_of_payments.is_transaction', 1)
                            )
                            ->native(false)
                            ->preload()
                            ->required()
                            ->searchable(),
                        DatePicker::make('transaction_date')
                            ->required()
                            ->native(false)
                            ->date()
                            ->format('Y-m-d')
                            ->default(now()),
                        TextInput::make('amount')
                            ->prefix('Rp')
                            ->mask(RawJs::make("\$money(\$input, ',', '.')"))
                            ->placeholder('Enter Amount'),
                        TextInput::make('description')
                            ->required()
                            ->placeholder('Description for detail transaction')
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
                SelectFilter::make('is_income')
                    ->label('Type')
                    ->preload()
                    ->searchable()
                    ->options(DbMapping::getSelectIsIncome())
                    ->native(false)
                    ->placeholder('Select type'),
                SelectFilter::make('category_id')
                    ->label('Category')
                    ->preload()
                    ->native(false)
                    ->searchable()
                    ->relationship(
                        'category',
                        'name',
                    )
                    ->placeholder('Select category'),
                SelectFilter::make('mode_of_payment_id')
                    ->label('Mode of Payment')
                    ->preload()
                    ->native(false)
                    ->searchable()
                    ->relationship('modeOfPayment', 'name', fn(Builder $query) =>
                    $query->where('mode_of_payments.is_transaction', 1))
                    ->placeholder('Select mode of payment'),
                Filter::make('transaction_date')
                    ->form([
                        DatePicker::make('transaction_from')
                            ->placeholder('From')
                            ->format('Y-m-d')
                            ->native(false),
                        DatePicker::make('transaction_to')
                            ->placeholder('To')
                            ->format('Y-m-d')
                            ->native(false),
                    ])
                    ->query(
                        fn(Builder $query, array $data): Builder
                        => $query
                            ->when(
                                $data['transaction_from'],
                                fn(Builder $query, $date): Builder =>
                                $query->whereDate('transaction_date', '>=', $date),
                            )
                            ->when(
                                $data['transaction_to'],
                                fn(Builder $query, $date): Builder =>
                                $query->whereDate('transaction_date', '<=', $date),
                            )
                    )
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
