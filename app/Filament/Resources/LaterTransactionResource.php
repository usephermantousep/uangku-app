<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LaterTransactionResource\Pages;
use App\Filament\Resources\LaterTransactionResource\RelationManagers;
use App\Models\LaterTransaction;
use Filament\Forms;
use Filament\Forms\Components\Checkbox;
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
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LaterTransactionResource extends Resource
{
    protected static ?string $model = LaterTransaction::class;

    protected static ?string $navigationIcon = 'heroicon-s-credit-card';
    protected static ?int $navigationSort = 1;

    public static function getLabel(): ?string
    {
        return __('global.later_transaction');
    }

    public static function getPluralLabel(): ?string
    {
        return __('global.later_transaction');
    }

    public static function getNavigationLabel(): string
    {
        return __('global.later_transaction');
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
                        Select::make('category_id')
                            ->relationship(
                                'category',
                                'name',
                                modifyQueryUsing: fn(Builder $query) => $query->where('categories.is_income', 0)
                            )
                            ->native(false)
                            ->preload()
                            ->placeholder('Select Category')
                            ->required()
                            ->searchable(),
                        Select::make('mode_of_payment_id')
                            ->relationship(
                                'modeOfPayment',
                                'name',
                                fn(Builder $query) =>
                                $query->where('mode_of_payments.is_transaction', 0)
                            )
                            ->searchable()
                            ->native(false)
                            ->preload()
                            ->placeholder('Select Mode Of Payment')
                            ->required(),
                        DatePicker::make('transaction_date')
                            ->required()
                            ->native(false)
                            ->date()
                            ->format('Y-m-d')
                            ->default(now()),
                        TextInput::make('amount')
                            ->placeholder('Input Amount')
                            ->prefix('Rp')
                            ->mask(RawJs::make("\$money(\$input, ',', '.')")),
                        TextInput::make('periods')
                            ->numeric()
                            ->required()
                            ->default(1),
                        TextInput::make('number_period')
                            ->numeric()
                            ->required()
                            ->default(1),
                        TextInput::make('description')
                            ->placeholder('description for detail transaction')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                    ])
                    ->columns(3),
                Section::make('Payment')
                    ->schema([
                        Checkbox::make('is_paid')
                            ->helperText('Remark for paid or not')
                            ->default(false),
                        DatePicker::make('paid_at')
                            ->placeholder('Input Paid Date')
                            ->date()
                            ->format('Y-m-d')
                            ->native(false)
                    ])->columns(2),
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
                IconColumn::make('is_paid')
                    ->label('Status')
                    ->boolean()
            ])
            ->filters([
                SelectFilter::make('is_paid')
                    ->label('Status')
                    ->preload()
                    ->searchable()
                    ->options([
                        '1' => 'Paid',
                        '0' => 'Unpaid'
                    ])
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
                        fn(Builder $query) => $query->where('categories.is_income', 0)
                    )
                    ->placeholder('Select category'),
                SelectFilter::make('mode_of_payment_id')
                    ->label('Mode of Payment')
                    ->preload()
                    ->native(false)
                    ->searchable()
                    ->relationship('modeOfPayment', 'name', fn(Builder $query) =>
                    $query->where('mode_of_payments.is_transaction', 0))
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
            'index' => Pages\ListLaterTransactions::route('/'),
            'create' => Pages\CreateLaterTransaction::route('/create'),
            'view' => Pages\ViewLaterTransaction::route('/{record}'),
            'edit' => Pages\EditLaterTransaction::route('/{record}/edit'),
        ];
    }
}
