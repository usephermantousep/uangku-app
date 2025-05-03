<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class FamilyTotalStatsOverview extends BaseWidget
{
    use InteractsWithPageFilters;
    protected static ?string $pollingInterval = null;
    protected function getStats(): array
    {
        $filters = $this->filters ?? [];
        $startDate = $filters['startDate'] ?? now()->startOfMonth();
        $endDate = $filters['endDate'] ?? now()->endOfMonth();
        $transactions = Transaction::whereBetween('transaction_date', [$startDate, $endDate])
            ->get();
        $totalIncome = $transactions->where('is_income', 1)->sum('amount');
        $totalExpense = $transactions->where('is_income', 0)->sum('amount');
        $totalLaterTransaction = $transactions->whereBetween(
            'transaction_date',
            [$startDate, $endDate]
        )
            ->sum('amount');
        $startDate = $startDate instanceof \Illuminate\Support\Carbon
            ? $startDate
            : \Illuminate\Support\Carbon::parse($startDate);
        $endDate = $endDate instanceof \Illuminate\Support\Carbon
            ? $endDate
            : \Illuminate\Support\Carbon::parse($endDate);
        $formatDate = 'd F Y';
        $periods = "For periods ";

        return [
            Stat::make('Total Income', 'Rp ' . number_format($totalIncome, thousands_separator: '.'))
                ->color('success')
                ->description($periods .
                    $startDate->format($formatDate) . ' - ' . $endDate->format($formatDate))
                ->icon('heroicon-o-arrow-trending-up'),
            Stat::make('Total Expense', 'Rp ' . number_format($totalExpense, thousands_separator: '.'))
                ->color('danger')
                ->description($periods .
                    $startDate->format($formatDate) . ' - ' . $endDate->format($formatDate))
                ->icon('heroicon-o-arrow-trending-down'),
            Stat::make('Income Vs Expense', 'Rp ' .
                number_format($totalIncome - $totalExpense, thousands_separator: '.'))
                ->color('info')
                ->description($periods .
                    $startDate->format($formatDate) . ' - ' . $endDate->format($formatDate))
                ->icon('heroicon-o-chart-bar'),
            Stat::make('Total Later Transaction', 'Rp ' .
                number_format($totalLaterTransaction, thousands_separator: '.'))
                ->color('warning')
                ->description($periods .
                    $startDate->format($formatDate) . ' - ' . $endDate->format($formatDate))
                ->icon('heroicon-o-clock'),
        ];
    }
}
