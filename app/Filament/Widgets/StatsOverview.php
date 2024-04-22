<?php

namespace App\Filament\Widgets;

use App\Models\Customer;
use App\Models\Hotel;
use App\Models\Kamar;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total', Customer::where('user_type', 'customer')->count())
                ->description('Customers')
                ->descriptionIcon('heroicon-s-user')
                ->color('success'),
            Stat::make('Total', Hotel::count())
                ->description('Hotels')
                ->descriptionIcon('heroicon-o-building-office-2')
                ->color('danger'),
            Stat::make('Total', Kamar::count())
                ->description('Rooms')
                ->descriptionIcon('heroicon-o-home')
                ->color('warning'),
        ];
    }
}
