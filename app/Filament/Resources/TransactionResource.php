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

    protected static ?int $navigationSort = 0;

    public static function getLabel(): ?string
    {
        return __('global.transactions');
    }

    public static function getPluralLabel(): ?string
    {
        return __('global.transactions');
    }

    public static function getNavigationLabel(): string
    {
        return __('global.transactions');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('global.transactions');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Select::make('is_income')
                            ->label(__('global.type'))
                            ->options(
                                DbMapping::getSelectIsIncome(app()->getLocale())
                            )
                            ->default(0)
                            ->live()
                            ->required()
                            ->native(false)
                            ->afterStateUpdated(
                                fn(Set $set) => $set('category_id', null)
                            ),
                        Select::make('category_id')
                            ->label(__('global.category'))
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
                            ->label(__('global.mode_of_payment'))
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
                            ->label(__('global.transaction_date'))
                            ->required()
                            ->native(false)
                            ->date()
                            ->format('Y-m-d')
                            ->default(now()),
                        TextInput::make('amount')
                            ->label(__('global.amount'))
                            ->prefix('Rp')
                            ->mask(RawJs::make("\$money(\$input, ',', '.')"))
                            ->placeholder(__('global.enter_amount')),
                        TextInput::make('description')
                            ->label(__('global.description'))
                            ->required()
                            ->columnSpanFull()
                            ->placeholder(__('global.enter_description', ['attribute' => __('global.transactions')]))
                            ->maxLength(255),
                    ])
                    ->columns(3),
                Section::make()
                    ->schema([
                        Select::make('created_by')
                            ->label(__('global.created_by'))
                            ->relationship('createdBy', 'name'),
                        Select::make('updated_by')
                            ->label(__('global.updated_by'))
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
                    ->label(__('global.transaction_date'))
                    ->date('d F Y'),
                TextColumn::make('category.name')
                    ->label(__('global.category')),
                TextColumn::make('modeOfPayment.name')
                    ->label(__('global.mode_of_payment')),
                TextColumn::make('amount')
                    ->formatStateUsing(fn(string $state): string =>
                    'Rp ' . number_format($state, thousands_separator: '.'))
                    ->label(__('global.amount')),
            ])
            ->filters([
                SelectFilter::make('is_income')
                    ->label(__('global.type'))
                    ->preload()
                    ->searchable()
                    ->options(DbMapping::getSelectIsIncome(app()->getLocale()))
                    ->native(false)
                    ->placeholder('Select type'),
                SelectFilter::make('category_id')
                    ->label(__('global.category'))
                    ->preload()
                    ->native(false)
                    ->searchable()
                    ->relationship(
                        'category',
                        'name',
                    )
                    ->placeholder('Select category'),
                SelectFilter::make('mode_of_payment_id')
                    ->label(__('global.mode_of_payment'))
                    ->preload()
                    ->native(false)
                    ->searchable()
                    ->relationship('modeOfPayment', 'name', fn(Builder $query) =>
                    $query->where('mode_of_payments.is_transaction', 1))
                    ->placeholder('Select mode of payment'),
                Filter::make('transaction_date')
                    ->form([
                        DatePicker::make('transaction_from')
                            ->label(__('global.transaction_from'))
                            ->format('Y-m-d')
                            ->native(false),
                        DatePicker::make('transaction_to')
                            ->label(__('global.transaction_to'))
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

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->orderBy('transaction_date', 'desc');
    }
}
