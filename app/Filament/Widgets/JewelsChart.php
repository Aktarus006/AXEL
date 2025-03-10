<?php

namespace App\Filament\Widgets;

use App\Models\Jewel;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class JewelsChart extends ChartWidget
{
    protected static ?string $heading = 'Bijoux ajoutés par mois';
    protected static ?int $sort = 3;
    protected static ?int $columns = 1;
    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $data = Jewel::query()
            ->selectRaw('COUNT(*) as count, DATE_FORMAT(created_at, "%Y-%m") as month')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->toArray();

        $labels = [];
        $values = [];

        foreach ($data as $month => $count) {
            $labels[] = Carbon::createFromFormat('Y-m', $month)->format('M Y');
            $values[] = $count;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Bijoux ajoutés',
                    'data' => $values,
                    'backgroundColor' => '#36A2EB',
                    'borderColor' => '#36A2EB',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
