<?php

namespace App\Filament\Widgets;

use App\Models\Company;
use App\Models\Invoice;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class Overview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Invoice', Invoice::count()),
            Stat::make('Invoice Dibayar', Invoice::where('status', 'paid')->count()),
            Stat::make('Invoice Belum Dibayar', Invoice::where('status', 'unpaid')->count()),
            Stat::make('Total Perusahaan', Company::count()),
        ];
    }
}
