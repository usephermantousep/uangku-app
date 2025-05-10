<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ModeOfPaymentResource\Pages;
use App\Filament\Resources\ModeOfPaymentResource\RelationManagers;
use App\Models\ModeOfPayment;
use App\Util\DbMapping;
use Filament\Facades\Filament;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Validation\Rules\Unique;

class ModeOfPaymentResource extends Resource
{
    protected static ?string $model = ModeOfPayment::class;

    protected static ?string $navigationIcon = 'heroicon-c-credit-card';

    public static function getLabel(): ?string
    {
        return __('global.mode_of_payment');
    }

    public static function getPluralLabel(): ?string
    {
        return __('global.mode_of_payment');
    }

    public static function getNavigationLabel(): string
    {
        return __('global.mode_of_payment');
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
                            ->placeholder(__('global.enter_placeholder', ['attribute' => __('global.name')]))
                            ->unique(
                                ignoreRecord: true,
                                modifyRuleUsing: fn(Unique $rule, Get $get)
                                => $rule->where(
                                    'family_id',
                                    Filament::getTenant()->id
                                )
                                    ->where('is_transaction', $get('is_transaction'))
                            ),
                        Select::make('is_transaction')
                            ->label(__('global.type'))
                            ->options(
                                DbMapping::getIsTransactionOrLater()
                            )
                            ->default(1)
                            ->required()
                            ->native(false),
                        TextInput::make('description')
                            ->label(__('global.description'))
                            ->placeholder(__('global.enter_placeholder', ['attribute' => __('global.description')]))
                            ->columnSpanFull(),
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
                TextColumn::make('is_transaction')
                    ->label(__('global.type'))
                    ->state(fn(ModeOfPayment $record) => DbMapping::getIsTransactionOrLater()[$record->is_transaction])
            ])
            ->filters([
                SelectFilter::make('is_transaction')
                    ->label(__('global.type'))
                    ->options(DbMapping::getIsTransactionOrLater())
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
            'index' => Pages\ListModeOfPayments::route('/'),
            'create' => Pages\CreateModeOfPayment::route('/create'),
            'view' => Pages\ViewModeOfPayment::route('/{record}'),
            'edit' => Pages\EditModeOfPayment::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['family']);
    }
}
