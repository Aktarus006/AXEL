<?php

namespace App\Filament\Resources\JewelResource\Pages;

use App\Filament\Resources\JewelResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditJewel extends EditRecord
{
    protected static string $resource = JewelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
