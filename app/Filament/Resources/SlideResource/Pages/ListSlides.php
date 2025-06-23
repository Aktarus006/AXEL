<?php
namespace App\Filament\Resources\SlideResource\Pages;

use App\Filament\Resources\SlideResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions;


class ListSlides extends ListRecords
{
    protected static string $resource = SlideResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
