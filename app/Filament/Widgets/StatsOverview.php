<?php

namespace App\Filament\Widgets;

use App\Models\Jewel;
use App\Models\Collection;
use App\Models\Creator;
use App\Enums\Status;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;
    protected static ?string $pollingInterval = null;
    protected static ?int $columns = 3;

    protected function getStats(): array
    {
        return [
            Stat::make('Bijoux en ligne', Jewel::where('online', Status::ONLINE)->count())
                ->description('Sur ' . Jewel::count() . ' bijoux au total')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->chart([7, 3, 4, 5, 6, 3, 5, 3])
                ->color('success'),

            Stat::make('Collections actives', Collection::where('online', true)->count())
                ->description('Sur ' . Collection::count() . ' collections')
                ->descriptionIcon('heroicon-m-rectangle-stack')
                ->color('warning'),

            Stat::make('Créateurs', Creator::where('online', true)->count())
                ->description('Créateurs actifs')
                ->descriptionIcon('heroicon-m-users')
                ->color('info'),
        ];
    }
}
