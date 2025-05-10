<?php

namespace App\Providers;

use BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch;
use Filament\Facades\Filament;
use Filament\Navigation\NavigationGroup;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Filament::registerNavigationGroups([
            'Transactions' => NavigationGroup::make(fn() => 'Transactions'),
            'Settings' => NavigationGroup::make(fn() => 'Settings'),
        ]);

        LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
            $switch
                ->displayLocale('id')
                ->locales(['en', 'id']);
        });
    }
}
