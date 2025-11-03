<?php

namespace App\Providers;

use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;
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
        FilamentColor::register([
            'danger' =>'#CC0000',
            //'gray' => '#CBDEDC',
            'info' => '#80C2DB',
            'primary' => '#07A0C4',
            'success' => '#2E7D32',
            'warning' => '#FF6B35',
        ]);
    }
}
