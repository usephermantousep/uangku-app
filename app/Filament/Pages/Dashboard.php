<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;

class Dashboard extends BaseDashboard
{
    use HasFiltersForm;

    public function filtersForm(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        DatePicker::make('startDate')
                            ->label(__('global.transaction_from'))
                            ->placeholder(__('global.transaction_from'))
                            ->native(false),
                        DatePicker::make('endDate')
                            ->label(__('global.transaction_to'))
                            ->placeholder(__('global.transaction_to'))
                            ->native(false),
                    ])
                    ->columns(3),
            ]);
    }
}
