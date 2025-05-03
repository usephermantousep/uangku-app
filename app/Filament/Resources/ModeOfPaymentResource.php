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
    protected static ?string $navigationGroup = 'Settings';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->label('Name')
                            ->placeholder('Enter the name of the mode of payment')
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
                            ->label('Type')
                            ->options(
                                DbMapping::getIsTransactionOrLater()
                            )
                            ->default(1)
                            ->required()
                            ->native(false)
                            ->placeholder('Select type'),
                        TextInput::make('description')
                            ->label('Description')
                            ->placeholder('Enter a description for the mode of payment')
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
                    ->label('Name')
                    ->searchable(),
                TextColumn::make('is_transaction')
                    ->label('Type')
                    ->state(fn(ModeOfPayment $record) => DbMapping::getIsTransactionOrLater()[$record->is_transaction])
            ])
            ->filters([
                SelectFilter::make('is_transaction')
                    ->label('Type')
                    ->options(DbMapping::getIsTransactionOrLater())
                    ->placeholder('Select type')
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
