<?php

namespace App\Filament\Widgets;

use App\Models\LaterTransaction;
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
        $locale = app()->getLocale();
        // Get start and end dates, ensuring they are Carbon instances
        $startDate = isset($filters['startDate']) ?
            \Illuminate\Support\Carbon::parse($filters['startDate']) :
            now()->startOfMonth();
        $endDate = isset($filters['endDate']) ?
            \Illuminate\Support\Carbon::parse($filters['endDate']) :
            now()->endOfMonth();
        // Fetch transactions within the date range
        $transactions = Transaction::whereBetween('transaction_date', [$startDate, $endDate])
            ->get(['is_income', 'amount']);
        // Calculate total income and expenses
        $totalIncome = $transactions->where('is_income', 1)->sum('amount');
        $totalExpense = $transactions->where('is_income', 0)->sum('amount');
        $totalLaterTransaction = LaterTransaction::whereBetween('transaction_date', [$startDate, $endDate])
            ->get('amount')
            ->sum('amount');
        // Define the date format
        $formatDate = 'd F Y';
        // Format the start and end dates according to the locale
        $startDateFormatted = $startDate->locale($locale)->translatedFormat($formatDate);
        $endDateFormatted = $endDate->locale($locale)->translatedFormat($formatDate);
        $periods = __('global.period');

        return [
            Stat::make(__('global.total_income'), 'Rp ' . number_format($totalIncome, thousands_separator: '.'))
                ->color('success')
                ->description($periods .
                    $startDateFormatted . ' - ' . $endDateFormatted)
                ->icon('heroicon-o-arrow-trending-up'),
            Stat::make(__('global.total_expense'), 'Rp ' . number_format($totalExpense, thousands_separator: '.'))
                ->color('danger')
                ->description($periods .
                    $startDateFormatted . ' - ' . $endDateFormatted)
                ->icon('heroicon-o-arrow-trending-down'),
            Stat::make(__('global.income_vs_expense'), 'Rp ' .
                number_format($totalIncome - $totalExpense, thousands_separator: '.'))
                ->color('info')
                ->description($periods .
                    $startDateFormatted . ' - ' . $endDateFormatted)
                ->icon('heroicon-o-chart-bar'),
            Stat::make(__('global.total_later_transaction'), 'Rp ' .
                number_format($totalLaterTransaction, thousands_separator: '.'))
                ->color('warning')
                ->description($periods .
                    $startDateFormatted . ' - ' . $endDateFormatted)
                ->icon('heroicon-o-clock'),
        ];
    }
}
