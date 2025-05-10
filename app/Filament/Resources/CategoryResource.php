<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers;
use App\Models\Category;
use App\Util\DbMapping;
use Filament\Facades\Filament;
use Filament\Forms;
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

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getLabel(): ?string
    {
        return __('global.category');
    }

    public static function getPluralLabel(): ?string
    {
        return __('global.category');
    }

    public static function getNavigationLabel(): string
    {
        return __('global.category');
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
                                    ->where('is_income', $get('is_income'))
                            ),
                        Select::make('is_income')
                            ->label(__('global.type'))
                            ->options(
                                DbMapping::getSelectIsIncome(app()->getLocale())
                            )
                            ->default(0)
                            ->required()
                            ->native(false),
                        TextInput::make('description')
                            ->label(__('global.description'))
                            ->placeholder(__('global.enter_description', ['attribute' => __('global.category')]))
                            ->columnSpanFull(),
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        $locale = app()->getLocale();
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('global.name'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('is_income')
                    ->label(__('global.type'))
                    ->state(fn(Category $record) => DbMapping::getSelectIsIncome($locale)[$record->is_income])
            ])
            ->filters([
                SelectFilter::make('is_income')
                    ->label(__('global.type'))
                    ->options(DbMapping::getSelectIsIncome($locale))
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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'view' => Pages\ViewCategory::route('/{record}'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
