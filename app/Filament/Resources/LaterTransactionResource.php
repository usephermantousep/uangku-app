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
                            ->label(__('global.category'))
                            ->relationship(
                                'category',
                                'name',
                                modifyQueryUsing: fn(Builder $query) => $query->where('categories.is_income', 0)
                            )
                            ->native(false)
                            ->preload()
                            ->required()
                            ->searchable(),
                        Select::make('mode_of_payment_id')
                            ->label(__('global.mode_of_payment'))
                            ->relationship(
                                'modeOfPayment',
                                'name',
                                fn(Builder $query) =>
                                $query->where('mode_of_payments.is_transaction', 0)
                            )
                            ->searchable()
                            ->native(false)
                            ->preload()
                            ->required(),
                        DatePicker::make('transaction_date')
                            ->label(__('global.transaction_date'))
                            ->required()
                            ->native(false)
                            ->date()
                            ->format('Y-m-d')
                            ->default(now()),
                        TextInput::make('amount')
                            ->label(__('global.amount'))
                            ->placeholder(__('global.enter_placeholder', ['attribute' => __('global.amount')]))
                            ->prefix('Rp')
                            ->required()
                            ->mask(RawJs::make("\$money(\$input, ',', '.')")),
                        TextInput::make('periods')
                            ->label(__('global.period'))
                            ->numeric()
                            ->required()
                            ->default(1),
                        TextInput::make('number_period')
                            ->label(__('global.number_period'))
                            ->numeric()
                            ->required()
                            ->default(1),
                        TextInput::make('description')
                            ->label(__('global.description'))
                            ->placeholder(__('global.enter_description', ['attribute' => __('global.transactions')]))
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                    ])
                    ->columns(3),
                Section::make('Payment')
                    ->schema([
                        Checkbox::make('is_paid')
                            ->label(__('global.is_paid'))
                            ->helperText(__('global.remark_is_paid'))
                            ->default(false),
                        DatePicker::make('paid_at')
                            ->label(__('global.paid_at'))
                            ->placeholder(__('global.enter_paid_at'))
                            ->date()
                            ->format('Y-m-d')
                            ->native(false)
                    ])->columns(2),
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
                    ->label(__('global.amount'))
                    ->formatStateUsing(fn(string $state): string =>
                    'Rp ' . number_format($state, thousands_separator: '.')),
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
                    ->label(__('global.category'))
                    ->preload()
                    ->native(false)
                    ->searchable()
                    ->relationship(
                        'category',
                        'name',
                        fn(Builder $query) => $query->where('categories.is_income', 0)
                    ),
                SelectFilter::make('mode_of_payment_id')
                    ->label(__('global.mode_of_payment'))
                    ->preload()
                    ->native(false)
                    ->searchable()
                    ->relationship('modeOfPayment', 'name', fn(Builder $query) =>
                    $query->where('mode_of_payments.is_transaction', 0)),
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
            'index' => Pages\ListLaterTransactions::route('/'),
            'create' => Pages\CreateLaterTransaction::route('/create'),
            'view' => Pages\ViewLaterTransaction::route('/{record}'),
            'edit' => Pages\EditLaterTransaction::route('/{record}/edit'),
        ];
    }
}
