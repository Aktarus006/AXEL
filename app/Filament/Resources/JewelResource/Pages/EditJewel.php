<?php

namespace App\Filament\Resources\JewelResource\Pages;

use App\Filament\Resources\JewelResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;

class EditJewel extends EditRecord
{
    use WithFileUploads;

    protected static string $resource = JewelResource::class;

    public $files = [
        'jewels/packshots' => [],
        'jewels/lifestyle' => [],
    ];

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function updatedFiles($value, $key)
    {
        foreach ($value as $file) {
            $this->record->addMedia($file->getRealPath())
                ->usingName($file->getClientOriginalName())
                ->toMediaCollection($key);
        }

        $this->files[$key] = [];
        Notification::make()
            ->success()
            ->title('Images ajoutées')
            ->send();

        $this->dispatch('media-uploaded');
    }

    public function moveMedia($mediaId, $toCollection)
    {
        $media = $this->record->media()->find($mediaId);
        if ($media) {
            $media->move($this->record, $toCollection);
            Notification::make()
                ->success()
                ->title('Image déplacée')
                ->send();

            $this->dispatch('media-moved');
        }
    }

    public function deleteMedia($mediaId)
    {
        $media = $this->record->media()->find($mediaId);
        if ($media) {
            $media->delete();
            Notification::make()
                ->success()
                ->title('Image supprimée')
                ->send();

            $this->dispatch('media-deleted');
        }
    }
}
