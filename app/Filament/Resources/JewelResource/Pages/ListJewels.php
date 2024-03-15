<?php

namespace App\Filament\Resources\JewelResource\Pages;

use App\Filament\Resources\JewelResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;

class ListJewels extends ListRecords
{
    protected static string $resource = JewelResource::class;

    public function getTabs(): array
    {
        return [
            "all" => Tab::make("Tous les bijoux"),
            "online" => Tab::make("Bijoux en ligne"),
            "offline" => Tab::make("Bijoux hors ligne"),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
