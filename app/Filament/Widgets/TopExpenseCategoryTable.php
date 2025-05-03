<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TopExpenseCategoryTable extends BaseWidget
{
    use InteractsWithPageFilters;

    protected static ?string $pollingInterval = null;

    public function getTableRecordKey(Model $record): string
    {
        return (string) $record->getKey();
    }
    public function table(Table $table): Table
    {
        $filters = $this->filters ?? [];
        return $table
            ->query(
                Transaction::query()
                    ->select('category_id', DB::raw('SUM(amount) as total_amount'))
                    ->where('is_income', 0)
                    ->whereBetween('transaction_date', [
                        $filters['startDate'] ?? now()->startOfMonth(),
                        $filters['endDate'] ?? now()->endOfMonth()
                    ])
                    ->groupBy('category_id')
                    ->orderBy('total_amount', 'desc')
            )
            ->columns([
                TextColumn::make('category.name')
                    ->label('Category'),
                TextColumn::make('total_amount')
                    ->label('Total Amount')
                    ->state(function ($record) {
                        return 'Rp ' . number_format($record->total_amount, thousands_separator: '.');
                    })
            ]);
    }
}
