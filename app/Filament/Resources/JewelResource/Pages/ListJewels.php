<?php

namespace App\Filament\Resources\JewelResource\Pages;

use App\Filament\Resources\JewelResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Jewel;

class ListJewels extends ListRecords
{
    protected static string $resource = JewelResource::class;

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Tous les bijoux')
                ->badge(Jewel::query()->count()),
            'online' => Tab::make('Bijoux en ligne')
                ->badge(Jewel::query()->where('online', true)->count())
                ->modifyQueryUsing(fn(Builder $query) => $query->where('online', true)),
            'offline' => Tab::make('Bijoux hors ligne')
                ->badge(Jewel::query()->where('online', false)->count())
                ->modifyQueryUsing(fn(Builder $query) => $query->where('online', false)),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
